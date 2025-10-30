<?php

namespace App\Livewire\Admin\Admins;

use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]

class RoleDetails extends Component
{
    use LivewireOperations;

    public $id,$current;
    public $data = [],$permissions = [];
    public $permissionsList = [];

    function mount() {
        $this->current = Role::find($this->id);
        $this->permissionsList = defaultPermissionsList();

        if($this->current){
            $this->data = $this->current->toArray();
            $this->permissions = $this->current->permissions()->pluck('name')->mapWithKeys(fn($q)=>[$q=>true])->toArray();
        }else{
            $this->data = [];
        }
    }

    function setPermission($key,$value) {
        $this->permissions[$key] = $value;

        $this->alert('success','تم التعديل بنجاح');
    }

    function save() {
        if(!$this->validator($this->data,['name'=>'required'])) return;

        $role = $this->current;

        $slug = \Str::slug($this->data['name']);

        if(!$role){
            $role = new Role();
            $role->guard_name = 'admin';
        }

        $role->fill($this->data);
        $role->name = $slug;
        $role->save();

        $permissions = array_keys(array_filter($this->permissions,fn($p)=>!!$p));

        $role->syncPermissions($permissions);

        $this->popup('success','تم التعديل بنجاح','center');

        $this->redirectWithTimeout(route('admin.roles'));
    }

    public function render()
    {
        // read $permissions from /tenant-permissions.json
        return view('livewire.admin.admins.role-details');
    }
}
