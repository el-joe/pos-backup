<?php

namespace App\Livewire\Admin\HRM\Employees;

use App\Enums\EmployeeStatusEnum;
use App\Enums\EmploymentTypeEnum;
use App\Models\Tenant\Employee;
use App\Models\Tenant\Department;
use App\Models\Tenant\Designation;
use App\Models\Tenant\Branch;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddEditEmployee extends Component
{
    use WithFileUploads;

    public $employee_id;
    public $employee_code;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $mobile;
    public $date_of_birth;
    public $gender = 'male';
    public $national_id;
    public $passport_number;
    public $address;
    public $city;
    public $state;
    public $country;
    public $postal_code;

    public $department_id;
    public $designation_id;
    public $branch_id;
    public $manager_id;
    public $employment_type = 'full_time';
    public $joining_date;
    public $probation_end_date;
    public $status = 'active';

    public $bank_name;
    public $account_number;
    public $account_holder_name;
    public $ifsc_code;

    public $emergency_contact_name;
    public $emergency_contact_relationship;
    public $emergency_contact_phone;

    public $photo;
    public $bio;

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $this->employee_id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'branch_id' => 'required|exists:branches,id',
            'manager_id' => 'nullable|exists:employees,id',
            'employment_type' => 'required|string',
            'joining_date' => 'required|date',
            'status' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->employee_id = $id;
            $this->loadEmployee();
        }
    }

    public function loadEmployee()
    {
        $employee = Employee::findOrFail($this->employee_id);

        $this->employee_code = $employee->employee_code;
        $this->first_name = $employee->first_name;
        $this->last_name = $employee->last_name;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->mobile = $employee->mobile;
        $this->date_of_birth = $employee->date_of_birth?->format('Y-m-d');
        $this->gender = $employee->gender;
        $this->national_id = $employee->national_id;
        $this->passport_number = $employee->passport_number;
        $this->address = $employee->address;
        $this->city = $employee->city;
        $this->state = $employee->state;
        $this->country = $employee->country;
        $this->postal_code = $employee->postal_code;

        $this->department_id = $employee->department_id;
        $this->designation_id = $employee->designation_id;
        $this->branch_id = $employee->branch_id;
        $this->manager_id = $employee->manager_id;
        $this->employment_type = $employee->employment_type->value;
        $this->joining_date = $employee->joining_date?->format('Y-m-d');
        $this->probation_end_date = $employee->probation_end_date?->format('Y-m-d');
        $this->status = $employee->status->value;

        $this->bank_name = $employee->bank_name;
        $this->account_number = $employee->account_number;
        $this->account_holder_name = $employee->account_holder_name;
        $this->ifsc_code = $employee->ifsc_code;

        $this->emergency_contact_name = $employee->emergency_contact_name;
        $this->emergency_contact_relationship = $employee->emergency_contact_relationship;
        $this->emergency_contact_phone = $employee->emergency_contact_phone;

        $this->bio = $employee->bio;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'national_id' => $this->national_id,
            'passport_number' => $this->passport_number,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'department_id' => $this->department_id,
            'designation_id' => $this->designation_id,
            'branch_id' => $this->branch_id,
            'manager_id' => $this->manager_id,
            'employment_type' => $this->employment_type,
            'joining_date' => $this->joining_date,
            'probation_end_date' => $this->probation_end_date,
            'status' => $this->status,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_holder_name' => $this->account_holder_name,
            'ifsc_code' => $this->ifsc_code,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'bio' => $this->bio,
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('employees', 'public');
        }

        if ($this->employee_id) {
            Employee::where('id', $this->employee_id)->update($data);
            session()->flash('success', __('hrm.employee_updated_successfully'));
        } else {
            Employee::create($data);
            session()->flash('success', __('hrm.employee_created_successfully'));
        }

        return redirect()->route('admin.hrm.employees.list');
    }

    public function render()
    {
        return view('livewire.admin.hrm.employees.add-edit-employee', [
            'departments' => Department::where('active', true)->get(),
            'designations' => Designation::where('active', true)->get(),
            'branches' => Branch::where('active', true)->get(),
            'managers' => Employee::where('active', true)->get(),
            'statuses' => EmployeeStatusEnum::cases(),
            'employment_types' => EmploymentTypeEnum::cases(),
        ]);
    }
}
