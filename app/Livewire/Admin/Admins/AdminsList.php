<?php

namespace App\Livewire\Admin\Admins;

use App\Enums\AuditLogActionEnum;
use App\Models\Tenant\AuditLog;
use App\Services\AdminService;
use App\Services\BranchService;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class AdminsList extends Component
{
    use LivewireOperations,WithPagination;

    private $adminService, $branchService;
    public $current;
    public $data = [
        'active' => false,
    ];

    public $collapseFilters = false;
    public $filters = [];
    public $export = null;

    public $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email|max:255',
        'phone' => 'required|string|unique:admins,phone|max:50',
        'password' => 'required|string|max:500',
        'type' => 'required|string|in:super_admin,admin|max:255',
    ];

    function boot() {
        $this->adminService = app(AdminService::class);
        $this->branchService = app(BranchService::class);
    }

    function setCurrent($id) {
        $this->current = $this->adminService->find($id);
        if($this->current) {
            $this->data = $this->current->toArray();
            unset($this->data['password']);
            $this->data['active'] = (bool)$this->data['active'];
            $this->data['role_id'] = $this->current->roles->first()?->id;
        }else{
            $this->reset('data');
        }

        $this->dispatch('iCheck-load');
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        AuditLog::log(AuditLogActionEnum::DELETE_ADMIN_TRY, ['id' => $id]);

        $this->confirm('delete','warning','Are you sure?','You want to delete this Admin','Yes, delete it!');
    }

    function delete() {
        if(!$this->current) {
            $this->popup('error','Admin not found');
            return;
        }
        $id = $this->current->id;

        $this->adminService->delete($id);

        AuditLog::log(AuditLogActionEnum::DELETE_ADMIN, ['id' => $id]);

        $this->popup('success','Admin deleted successfully');

        $this->dismiss();

        $this->reset('current','data');
    }

    function save() {
        if($this->current){
            $this->rules['email'] = 'required|email|max:255|unique:admins,email,'.$this->current->id;
            $this->rules['phone'] = 'required|string|max:50|unique:admins,phone,'.$this->current->id;
            $this->rules['password'] = 'nullable|string|max:500';
            $action = AuditLogActionEnum::UPDATE_ADMIN;
        }else{
            $limit = subscriptionFeatureLimit('erp_admins', 999999);
            $totalAdmins = $this->adminService->count();

            if($totalAdmins >= $limit){
                $this->popup('error','Admin limit reached. Please upgrade your subscription to add more admins.');
                return;
            }
            $action = AuditLogActionEnum::CREATE_ADMIN;
        }

        if(!$this->validator())return;

        try{
            DB::beginTransaction();
            $admin = $this->adminService->save($this->current?->id,$this->data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->popup('error','Error occurred while saving admin: '.$e->getMessage());
            return;
        }

        AuditLog::log($action, ['id' => $admin->id]);

        if($this->data['role_id']??false){
            $role = Role::find($this->data['role_id']);
            $admin->syncRoles([$role->name]);
        }

        $this->popup('success','Admin saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $admins = $this->adminService->activeList(filter : $this->filters);

        if ($this->export == 'excel') {
            #	Name	Phone	Email	Type	Branch	Active
            $data = $admins->map(function ($admin, $loop) {
                return [
                    'loop' => $loop + 1,
                    'name' => $admin->name,
                    'phone' => $admin->phone,
                    'email' => $admin->email,
                    'type' => ucfirst(str_replace('_', ' ', $admin->type)),
                    'branch' => $admin->branch?->name ?? 'N/A',
                    'active' => $admin->active ? 'Active' : 'Inactive',
                ];
            })->toArray();
            $columns = ['loop', 'name', 'phone', 'email','type', 'branch', 'active'];
            $headers = ['#', 'Name', 'Phone', 'Email', 'Type', 'Branch', 'Status'];

            $fullPath = exportToExcel($data, $columns, $headers, 'branches');

            AuditLog::log(AuditLogActionEnum::EXPORT_ADMINS, ['type' => 'Admin', 'url' => $fullPath]);

            $this->redirectToDownload($fullPath);
        }

        $branches = $this->branchService->activeList();
        $roles = Role::whereActive(1)->get();

        return layoutView('admins.admins-list', get_defined_vars());
    }
}
