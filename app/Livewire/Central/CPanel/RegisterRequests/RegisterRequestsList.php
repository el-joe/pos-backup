<?php

namespace App\Livewire\Central\CPanel\RegisterRequests;

use App\Models\RegisterRequest;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class RegisterRequestsList extends Component
{
    use LivewireOperations, WithPagination;

    public $id, $registerRequest;
    public $current;

    function setCurrent($id)
    {
        $this->current = RegisterRequest::find($id);
    }

    function changeStatus($id, $status)
    {
        $this->current = RegisterRequest::find($id);
        $this->current->status = $status;

        if ($status == 'approved') {
            $this->current->update([
                'status' => 'approved'
            ]);
        } else {
            $this->current->update([
                'status' => 'rejected'
            ]);
        }
    }

    public function render()
    {

        $registerRequests = RegisterRequest::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.central.cpanel.register-requests.register-requests-list', get_defined_vars());
    }
}
