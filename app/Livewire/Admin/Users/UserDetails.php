<?php

namespace App\Livewire\Admin\Users;

use App\Models\Tenant\User;
use App\Services\AccountService;
use App\Services\UserService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class UserDetails extends Component
{
    public $id,$user;
    private $userService,$accountService;

    #[Url]
    public $activeTab = 'details';

    function boot() {
        $this->userService = app(UserService::class);
        $this->accountService = app(AccountService::class);
    }

    function mount() {
        $this->user = $this->userService->findOrFail($this->id);
    }

    public function render()
    {
        $accounts = $this->accountService->list(['branch','paymentMethod'],[
            'model_type' => User::class,
            'model_id' => $this->user->id
        ],10,'id');

        return layoutView('users.user-details', get_defined_vars())
            ->title(__('general.titles.' . $this->user?->type?->value . '_details'));
    }
}
