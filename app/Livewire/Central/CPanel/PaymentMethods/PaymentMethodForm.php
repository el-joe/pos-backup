<?php

namespace App\Livewire\Central\CPanel\PaymentMethods;

use App\Models\PaymentMethod;
use App\Traits\LivewireOperations;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class PaymentMethodForm extends Component
{
    use LivewireOperations;

    public ?PaymentMethod $paymentMethod = null;

    public array $data = [];

    public $iconFile = null;

    public string $requiredFieldsText = '';

    /**
     * Array of [ ['key' => string, 'value' => string|null], ... ]
     */
    public array $credentialsInputs = [];

    /**
        * Array of [
        *   ['key' => string, 'label_en' => string|null, 'label_ar' => string|null, 'value_en' => string|null, 'value_ar' => string|null],
        *   ...
        * ]
     */
    public array $detailsInputs = [];

    public function rules(): array
    {
        return [
            'data.name' => 'required|string|max:255',
            'data.provider' => [
                'required',
                'string',
                'max:255',
                Rule::unique('central.payment_methods', 'provider')->ignore($this->paymentMethod?->id),
            ],
            'iconFile' => 'nullable|image|max:2048',
            'data.fee_percentage' => 'nullable|numeric|min:0|max:100',
            'data.fixed_fee' => 'nullable|numeric|min:0',
            'data.active' => 'boolean',
            'data.manual' => 'boolean',
            'requiredFieldsText' => 'nullable|string',
            'credentialsInputs' => 'array',
            'credentialsInputs.*.value' => 'nullable|string|max:500',
            'detailsInputs' => 'array',
            'detailsInputs.*.key' => 'nullable|string|max:120',
            'detailsInputs.*.label_en' => 'nullable|string|max:120',
            'detailsInputs.*.label_ar' => 'nullable|string|max:120',
            'detailsInputs.*.value_en' => 'nullable|string|max:500',
            'detailsInputs.*.value_ar' => 'nullable|string|max:500',
        ];
    }

    public function mount(?int $id = null): void
    {
        $this->paymentMethod = $id ? PaymentMethod::findOrFail($id) : null;

        if ($this->paymentMethod) {
            $this->data = Arr::only($this->paymentMethod->toArray(), [
                'name',
                'icon_path',
                'provider',
                'manual',
                'credentials',
                'required_fields',
                'details',
                'fee_percentage',
                'fixed_fee',
                'active',
            ]);

            $this->data['active'] = (bool)($this->data['active'] ?? false);
            $this->data['manual'] = (bool)($this->data['manual'] ?? false);
            $this->data['fee_percentage'] = $this->data['fee_percentage'] ?? 0;
            $this->data['fixed_fee'] = $this->data['fixed_fee'] ?? 0;
        } else {
            $this->data = [
                'name' => '',
                'icon_path' => null,
                'provider' => '',
                'manual' => false,
                'credentials' => [],
                'required_fields' => [],
                'details' => null,
                'fee_percentage' => 0,
                'fixed_fee' => 0,
                'active' => true,
            ];
        }

        $this->requiredFieldsText = implode("\n", (array)($this->data['required_fields'] ?? []));

        $this->syncCredentialsInputs();
        $this->syncDetailsInputs();
    }

    public function updatedRequiredFieldsText(): void
    {
        $this->syncCredentialsInputs();
    }

    public function addDetailRow(): void
    {
        $this->detailsInputs[] = ['key' => '', 'label_en' => '', 'label_ar' => '', 'value_en' => '', 'value_ar' => ''];
    }

    public function removeDetailRow(int $index): void
    {
        unset($this->detailsInputs[$index]);
        $this->detailsInputs = array_values($this->detailsInputs);
    }

    private function parseRequiredFields(): array
    {
        $lines = preg_split("/\r\n|\n|\r/", (string)$this->requiredFieldsText);
        $fields = [];

        foreach ($lines as $line) {
            $key = trim((string)$line);
            if ($key === '') {
                continue;
            }
            if (!in_array($key, $fields, true)) {
                $fields[] = $key;
            }
        }

        return $fields;
    }

    private function syncCredentialsInputs(): void
    {
        $fields = $this->parseRequiredFields();

        $existing = [];
        foreach ($this->credentialsInputs as $row) {
            $existingKey = (string)($row['key'] ?? '');
            if ($existingKey !== '') {
                $existing[$existingKey] = $row['value'] ?? null;
            }
        }

        $stored = (array)($this->data['credentials'] ?? []);

        $next = [];
        foreach ($fields as $key) {
            $value = $existing[$key] ?? ($stored[$key] ?? null);
            $next[] = ['key' => $key, 'value' => $value];
        }

        $this->credentialsInputs = $next;
    }

    private function syncDetailsInputs(): void
    {
        $details = $this->data['details'] ?? null;

        $rows = [];

        // New format: list of rows
        if (is_array($details) && array_is_list($details)) {
            foreach ($details as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $rows[] = [
                    'key' => (string)($row['key'] ?? ''),
                    'label_en' => (string)($row['label']['en'] ?? ''),
                    'label_ar' => (string)($row['label']['ar'] ?? ''),
                    'value_en' => (string)($row['value']['en'] ?? ''),
                    'value_ar' => (string)($row['value']['ar'] ?? ''),
                ];
            }
        }

        // Backward compatible: object map: key => {en,ar}
        if (is_array($details) && !array_is_list($details)) {
            foreach ($details as $key => $value) {
                if (!is_array($value)) {
                    continue;
                }
                $rows[] = [
                    'key' => (string)$key,
                    'label_en' => (string)$key,
                    'label_ar' => (string)$key,
                    'value_en' => (string)($value['en'] ?? ''),
                    'value_ar' => (string)($value['ar'] ?? ''),
                ];
            }
        }

        $this->detailsInputs = $rows;

        if (count($this->detailsInputs) === 0) {
            $this->detailsInputs = [
                ['key' => '', 'label_en' => '', 'label_ar' => '', 'value_en' => '', 'value_ar' => ''],
            ];
        }
    }

    public function save(): void
    {
        $this->validate($this->rules());

        $requiredFields = $this->parseRequiredFields();

        $credentials = [];
        foreach ($requiredFields as $index => $key) {
            $credentials[$key] = trim((string)($this->credentialsInputs[$index]['value'] ?? '')) ?: null;
        }

        $details = null;
        if ((bool)($this->data['manual'] ?? false)) {
            $details = [];
            foreach ($this->detailsInputs as $row) {
                $key = trim((string)($row['key'] ?? ''));
                $labelEn = trim((string)($row['label_en'] ?? ''));
                $labelAr = trim((string)($row['label_ar'] ?? ''));
                $valueEn = trim((string)($row['value_en'] ?? ''));
                $valueAr = trim((string)($row['value_ar'] ?? ''));

                if ($key === '') {
                    continue;
                }

                if ($labelEn === '' && $labelAr === '' && $valueEn === '' && $valueAr === '') {
                    continue;
                }

                $details[] = [
                    'key' => $key,
                    'label' => [
                        'en' => $labelEn ?: null,
                        'ar' => $labelAr ?: null,
                    ],
                    'value' => [
                        'en' => $valueEn ?: null,
                        'ar' => $valueAr ?: null,
                    ],
                ];
            }

            if (count($details) === 0) {
                $details = null;
            }
        }

        $payload = [
            'name' => $this->data['name'] ?? '',
            'provider' => $this->data['provider'] ?? '',
            'manual' => (bool)($this->data['manual'] ?? false),
            'required_fields' => $requiredFields,
            'credentials' => $credentials,
            'details' => $details,
            'fee_percentage' => $this->data['fee_percentage'] ?? 0,
            'fixed_fee' => $this->data['fixed_fee'] ?? 0,
            'active' => (bool)($this->data['active'] ?? false),
        ];

        $paymentMethod = $this->paymentMethod ?: new PaymentMethod();
        $paymentMethod->fill($payload);
        $paymentMethod->save();

        if ($this->iconFile) {
            $path = $this->iconFile->store('payment-methods/icons', 'public');
            $paymentMethod->update(['icon_path' => $path]);
            $this->data['icon_path'] = $path;
        }

        $this->paymentMethod = $paymentMethod;

        $this->popup('success', 'Payment Method saved successfully');

        $this->redirect(route('cpanel.payment-methods.edit', ['id' => $paymentMethod->id]), navigate: true);
    }

    public function render()
    {
        return view('livewire.central.cpanel.payment-methods.payment-method-form', [
            'requiredFields' => $this->parseRequiredFields(),
        ]);
    }
}
