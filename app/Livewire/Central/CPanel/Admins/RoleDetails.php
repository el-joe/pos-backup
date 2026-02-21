<?php

namespace App\Livewire\Admin\Admins;

use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleDetails extends Component
{
    use LivewireOperations;

    public $id,$current;
    public $data = [],$permissions = [];
    public $permissionsList = [];
    public $collapses = [];

    function mount() {
        $this->current = Role::find($this->id);
        $this->permissionsList = defaultPermissionsList();

        if($this->current){
            $this->data = $this->current->toArray();
            $this->data['roleName'] = $this->current->name;
            $this->permissions = $this->current->permissions()->pluck('name')->mapWithKeys(fn($q)=>[$q=>true])->toArray();
        }else{
            $this->data = [];
        }

    }

    function toggleCollapse($key) {
        $this->collapses[$key] = !($this->collapses[$key] ?? false);
    }

    function setPermission($permission,$event,$value) {
        $this->permissions["$permission.$event"] = $value;

        $this->alert('success','تم التعديل بنجاح');
    }

    function save() {
        $rules = [
            'roleName' => 'required|unique:roles,name'.($this->current ? ','.$this->current->id : ''),
        ];

        if(!$this->current){
            $rules = ['roleName'=>'required|unique:roles,name'];
        }
        if(!$this->validator($this->data,$rules)) return;

        $role = $this->current;

        if(!$role){
            $role = new Role();
            $role->guard_name = 'tenant_admin';
            $role->active = true;
        }

        $role->fill($this->data);
        $role->name = $this->data['roleName'];
        $role->save();

        $role->syncPermissions(array_keys(array_filter($this->permissions)));

        $this->popup('success','تم التعديل بنجاح','center');

        $this->redirectWithTimeout(route('admin.roles.list'));
    }

    public function render()
    {
        // read $permissions from /tenant-permissions.json

        return layoutView('admins.role-details');
    }
}
