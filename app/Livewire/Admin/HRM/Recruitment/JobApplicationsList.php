<?php

namespace App\Livewire\Admin\HRM\Recruitment;

use App\Enums\JobApplicationStatusEnum;
use App\Models\Tenant\JobOpening;
use App\Models\Tenant\JobApplication;
use Livewire\Component;
use Livewire\WithPagination;

class JobApplicationsList extends Component
{
    use WithPagination;

    public $search = '';
    public $job_opening_filter = '';
    public $status_filter = '';

    public function updateStatus($id, $status)
    {
        $application = JobApplication::findOrFail($id);
        $application->update(['status' => $status]);

        session()->flash('success', __('hrm.application_status_updated'));
    }

    public function scheduleInterview($id, $date, $time)
    {
        $application = JobApplication::findOrFail($id);
        $application->update([
            'status' => JobApplicationStatusEnum::INTERVIEW_SCHEDULED,
            'interview_date' => $date,
            'interview_time' => $time,
        ]);

        session()->flash('success', __('hrm.interview_scheduled_successfully'));
    }

    public function render()
    {
        $applications = JobApplication::query()
            ->with(['jobOpening', 'assignedTo'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('application_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->job_opening_filter, fn($q) => $q->where('job_opening_id', $this->job_opening_filter))
            ->when($this->status_filter, fn($q) => $q->where('status', $this->status_filter))
            ->latest()
            ->paginate(25);

        return view('livewire.admin.hrm.recruitment.job-applications-list', [
            'applications' => $applications,
            'jobOpenings' => JobOpening::where('is_active', true)->get(),
            'statuses' => JobApplicationStatusEnum::cases(),
        ]);
    }
}
