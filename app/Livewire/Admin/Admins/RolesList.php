<?php

namespace App\Livewire\Admin\Admins;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolesList extends Component
{
    use LivewireOperations;
    public $current;

    #[Url]
    public $filter = [];

    #[Url]
    public $export;


    function setCurrent($id)
    {
        if (!$id) return;
        $this->current = Role::withCount('users')->find($id);
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        AuditLog::log(AuditLogActionEnum::DELETE_ROLE_TRY, ['id' => $id]);

        $this->confirm('delete', 'error', 'Do you want to delete this Role?', '', 'Cancel');
    }

    function delete()
    {
        $id = $this->current->id;
        $this->current->delete();
        AuditLog::log(AuditLogActionEnum::DELETE_ROLE, ['id' => $id]);
        $this->popup('success', 'Role deleted successfully', 'center');
    }
    public function render()
    {
        $roles = Role::all();

        return layoutView('admins.roles-list', get_defined_vars());
    }
}
