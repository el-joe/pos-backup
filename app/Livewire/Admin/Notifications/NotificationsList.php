<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;

class NotificationsList extends Component
{
    public function render()
    {
        $notifications = admin()->notifications()->latest()->paginate(20);
        return layoutView('notifications.notifications-list', get_defined_vars());
    }
}
