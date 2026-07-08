<?php

namespace App\Livewire\Admin\Contracting;

use App\Traits\LivewireOperations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Generic metadata-driven CRUD base for the Contracting module.
 *
 * Subclasses declare:
 *  - $modelClass   : FQN of the Eloquent model
 *  - $permission   : base permission key (e.g. "contracting_tenders")
 *  - $viewPath     : livewire blade path (e.g. "contracting.tenders.tenders-list")
 *  - $titleKey     : translation key for the page title
 *  - $entityKey    : translation key base for page strings (e.g. "general.pages.contracting.tenders")
 *  - listColumns() : array of column specs
 *  - formFields()  : array of form field specs
 *  - validationRules() [optional]
 *
 * Column spec:
 *   ['field' => 'code', 'label_key' => 'code']
 *   ['field' => 'name', 'label_key' => 'name']
 *   ['field' => 'is_active', 'label_key' => 'status', 'type' => 'boolean']
 *   ['field' => 'start_date', 'label_key' => 'start_date', 'type' => 'date']
 *   ['field' => 'total_amount', 'label_key' => 'amount', 'type' => 'decimal']
 *   ['field' => 'parent.name', 'label_key' => 'parent', 'type' => 'relation']
 *
 * Form field spec:
 *   ['field' => 'code', 'label_key' => 'code', 'type' => 'text', 'required' => true]
 *   ['field' => 'type', 'label_key' => 'type', 'type' => 'select',
 *        'options' => ['asset' => 'Asset', ...]]
 *   ['field' => 'parent_id', 'label_key' => 'parent', 'type' => 'belongs_to',
 *        'model' => ChartOfAccount::class, 'display' => 'name']
 *   ['field' => 'is_active', 'label_key' => 'active', 'type' => 'boolean']
 *   ['field' => 'notes', 'label_key' => 'notes', 'type' => 'textarea']
 */
abstract class ContractingCrudComponent extends Component
{
    use LivewireOperations, WithPagination;

    protected string $modelClass = '';
    protected string $permission = '';
    protected string $viewPath = '';
    protected string $titleKey = '';
    protected string $entityKey = '';
    protected string $icon = 'fa fa-cube';

    public array $filters = [];
    public bool $collapseFilters = false;

    /** Currently-edited record id (null = creating). */
    public $editingId = null;

    /** Form state (keys match model columns). */
    public array $form = [];

    /** Expose metadata to the blade. */
    public array $columnsMeta = [];
    public array $fieldsMeta = [];

    public function mount(): void
    {
        if ($this->permission && !adminCan($this->permission . '.list')) {
            abort(403);
        }
        $this->columnsMeta = $this->listColumns();
        $this->fieldsMeta = $this->formFields();
        $this->resetForm();
    }

    abstract protected function listColumns(): array;
    abstract protected function formFields(): array;

    protected function validationRules(): array
    {
        $rules = [];
        foreach ($this->fieldsMeta as $field) {
            $f = $field['field'];
            $parts = [];
            if (!empty($field['required'])) {
                $parts[] = 'required';
            } else {
                $parts[] = 'nullable';
            }
            switch ($field['type'] ?? 'text') {
                case 'decimal':
                case 'number':
                    $parts[] = 'numeric';
                    break;
                case 'date':
                    $parts[] = 'date';
                    break;
                case 'boolean':
                    $parts[] = 'boolean';
                    break;
                case 'email':
                    $parts[] = 'email';
                    break;
                case 'belongs_to':
                    if (!empty($field['model'])) {
                        $modelFqn = $field['model'];
                        $m = new $modelFqn;
                        $parts[] = 'integer';
                        $parts[] = 'exists:' . $m->getTable() . ',id';
                    }
                    break;
                default:
                    $parts[] = 'string';
                    $parts[] = 'max:' . ($field['max'] ?? 255);
            }
            $rules['form.' . $f] = implode('|', $parts);
        }
        return $rules;
    }

    public function rules(): array
    {
        return $this->validationRules();
    }

    public function updatedFilters(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->form = [];
        foreach ($this->fieldsMeta as $field) {
            $default = $field['default'] ?? null;
            if (($field['type'] ?? null) === 'boolean') {
                $default = $field['default'] ?? true;
            }
            $this->form[$field['field']] = $default;
        }
        $this->editingId = null;
    }

    public function edit($id): void
    {
        if (!adminCan($this->permission . '.update')) {
            abort(403);
        }
        $model = $this->modelClass::find($id);
        if (!$model) {
            $this->popup('error', __('general.pages.contracting.messages.not_found'));
            return;
        }
        $this->editingId = $id;
        $this->form = [];
        foreach ($this->fieldsMeta as $field) {
            $f = $field['field'];
            $val = $model->{$f};
            if (($field['type'] ?? null) === 'date' && $val) {
                $val = $val instanceof \Carbon\CarbonInterface ? $val->format('Y-m-d') : (string) $val;
            }
            $this->form[$f] = $val;
        }
        $this->dispatch('contracting-open-modal');
    }

    public function create(): void
    {
        if (!adminCan($this->permission . '.create')) {
            abort(403);
        }
        $this->resetForm();
        $this->dispatch('contracting-open-modal');
    }

    public function save(): void
    {
        $isUpdate = (bool) $this->editingId;
        if (!adminCan($this->permission . '.' . ($isUpdate ? 'update' : 'create'))) {
            abort(403);
        }

        $this->validate();

        $data = [];
        foreach ($this->fieldsMeta as $field) {
            $f = $field['field'];
            $val = $this->form[$f] ?? null;
            if (($field['type'] ?? null) === 'boolean') {
                $val = (bool) $val;
            }
            $data[$f] = $val;
        }

        if ($isUpdate) {
            $model = $this->modelClass::find($this->editingId);
            $model->update($data);
        } else {
            $this->modelClass::create($data);
        }

        $this->popup('success', __('general.pages.contracting.messages.saved'));
        $this->dispatch('contracting-close-modal');
        $this->resetForm();
    }

    /**
     * Optional status actions — subclasses override to enable approve/post/etc.
     *
     * Returns array of actions, each spec:
     *   ['action' => 'approve', 'from' => ['draft','submitted'], 'to' => 'approved',
     *    'permission_suffix' => 'approve', 'timestamp_field' => 'approved_at',
     *    'icon' => 'fa fa-check', 'class' => 'emerald', 'label_key' => 'approve']
     */
    protected function statusActions(): array
    {
        return [];
    }

    /**
     * Execute a configured status action by key (e.g. "approve", "post").
     */
    public function runStatusAction(string $action, $id): void
    {
        $spec = collect($this->statusActions())->firstWhere('action', $action);
        if (!$spec) {
            return;
        }
        $permSuffix = $spec['permission_suffix'] ?? $action;
        if (!adminCan($this->permission . '.' . $permSuffix)) {
            abort(403);
        }
        $model = $this->modelClass::find($id);
        if (!$model) {
            $this->popup('error', __('general.pages.contracting.messages.not_found'));
            return;
        }
        $allowedFrom = $spec['from'] ?? [];
        if (!empty($allowedFrom) && !in_array($model->status, $allowedFrom, true)) {
            $this->popup('error', __('general.pages.contracting.messages.invalid_status'));
            return;
        }
        $updates = ['status' => $spec['to']];
        if (!empty($spec['timestamp_field'])) {
            $updates[$spec['timestamp_field']] = now();
        }
        $model->update($updates);
        $pastKey = $spec['message_key'] ?? match ($action) {
            'approve' => 'approved',
            'post' => 'posted',
            'submit' => 'submitted',
            'reject' => 'rejected',
            'pay' => 'paid',
            default => $action,
        };
        $this->popup('success', __('general.pages.contracting.messages.' . $pastKey));
    }

    public $deletingId = null;

    public function deleteAlert($id): void
    {
        if (!adminCan($this->permission . '.delete')) {
            abort(403);
        }
        $this->deletingId = $id;
        $this->confirm(
            'delete',
            'warning',
            __('general.messages.are_you_sure'),
            __('general.pages.contracting.messages.confirm_delete'),
            __('general.messages.yes_delete_it')
        );
    }

    public function delete(): void
    {
        if (!adminCan($this->permission . '.delete')) {
            abort(403);
        }
        if (!$this->deletingId) {
            return;
        }
        $model = $this->modelClass::find($this->deletingId);
        if ($model) {
            $model->delete();
            $this->popup('success', __('general.pages.contracting.messages.deleted'));
        }
        $this->dismiss();
        $this->deletingId = null;
    }

    /**
     * Search query — by default filters on "code" and "name" if present.
     */
    protected function applySearch($query)
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search === '') {
            return $query;
        }
        $searchable = $this->searchableFields();
        return $query->where(function ($q) use ($searchable, $search) {
            foreach ($searchable as $field) {
                $q->orWhere($field, 'like', "%{$search}%");
            }
        });
    }

    protected function searchableFields(): array
    {
        return ['code', 'name'];
    }

    /**
     * Provide eager-loaded relations (deduced from 'relation' columns).
     */
    protected function eagerRelations(): array
    {
        $relations = [];
        foreach ($this->columnsMeta as $col) {
            if (($col['type'] ?? null) === 'relation' && str_contains($col['field'], '.')) {
                $relations[] = strstr($col['field'], '.', true);
            }
        }
        return array_values(array_unique($relations));
    }

    #[On('re-render')]
    public function render()
    {
        $query = $this->modelClass::query()->with($this->eagerRelations());
        $query = $this->applySearch($query);
        $records = $query->latest('id')->paginate(15);

        $columnsMeta = $this->columnsMeta;
        $fieldsMeta = $this->fieldsMeta;
        $permission = $this->permission;
        $titleKey = $this->titleKey;
        $entityKey = $this->entityKey;
        $icon = $this->icon;

        // belongs_to option sources for the form
        $selectOptions = [];
        foreach ($this->fieldsMeta as $field) {
            if (($field['type'] ?? '') === 'belongs_to' && !empty($field['model'])) {
                $modelFqn = $field['model'];
                $display = $field['display'] ?? 'name';
                $selectOptions[$field['field']] = $modelFqn::query()
                    ->orderBy($display)
                    ->limit(500)
                    ->pluck($display, 'id')
                    ->toArray();
            }
        }
        return layoutView($this->viewPath, compact(
            'records',
            'columnsMeta',
            'fieldsMeta',
            'permission',
            'titleKey',
            'entityKey',
            'icon',
            'selectOptions'
        ))->with(['statusActions' => $this->statusActions()])
            ->title(__($this->titleKey));
    }
}
