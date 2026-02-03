<?php

namespace App\Livewire\Admin\HRM\Departments;

use App\Models\Tenant\Department;
use App\Models\Tenant\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentsList extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $department_id;
    public $name;
    public $ar_name;
    public $description;
    public $ar_description;
    public $parent_id;
    public $manager_id;
    public $active = true;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'ar_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id',
            'active' => 'boolean',
        ];
    }

    public function openModal($id = null)
    {
        $this->resetFields();
        if ($id) {
            $this->department_id = $id;
            $this->loadDepartment();
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->department_id = null;
        $this->name = '';
        $this->ar_name = '';
        $this->description = '';
        $this->ar_description = '';
        $this->parent_id = null;
        $this->manager_id = null;
        $this->active = true;
        $this->resetValidation();
    }

    public function loadDepartment()
    {
        $department = Department::findOrFail($this->department_id);
        $this->name = $department->name;
        $this->ar_name = $department->ar_name;
        $this->description = $department->description;
        $this->ar_description = $department->ar_description;
        $this->parent_id = $department->parent_id;
        $this->manager_id = $department->manager_id;
        $this->active = $department->active;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'ar_name' => $this->ar_name,
            'description' => $this->description,
            'ar_description' => $this->ar_description,
            'parent_id' => $this->parent_id,
            'manager_id' => $this->manager_id,
            'active' => $this->active,
        ];

        if ($this->department_id) {
            Department::where('id', $this->department_id)->update($data);
            session()->flash('success', __('hrm.department_updated_successfully'));
        } else {
            Department::create($data);
            session()->flash('success', __('hrm.department_created_successfully'));
        }

        $this->closeModal();
    }

    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        session()->flash('success', __('hrm.department_deleted_successfully'));
    }

    public function render()
    {
        $departments = Department::query()
            ->with(['parent', 'manager', 'employees'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('ar_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(25);

        return view('livewire.admin.hrm.departments.departments-list', [
            'departments' => $departments,
            'parent_departments' => Department::where('active', true)->whereNull('parent_id')->get(),
            'employees' => Employee::where('active', true)->get(),
        ]);
    }
}
