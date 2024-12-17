<?php

namespace App\Livewire;

use App\Models\HelpDesks;
use App\Models\IncidentRequest;
use App\Models\ticket_notifications;
use Livewire\Component;

class AllTicketsNotifications extends Component
{
    public $unreadCount;
    public $notifications = [];
    public $isVisible = false;

    public function mount()
    {
        $this->fetchNotifications();
    }


    public function toggleNotifications()
    {
        $this->isVisible = !$this->isVisible; // Toggle visibility of the sidebar
    }


    public function fetchNotifications()
    {

        // Example: Fetch notifications for IncidentRequest or User based on a condition
        $this->notifications = ticket_notifications::whereIn('notifiable_type', [
            IncidentRequest::class,
            HelpDesks::class,
        ])->latest()->get();

        $this->unreadCount = ticket_notifications::where('is_read', false)->count();
    }

    public function markAsRead($id)
    {
        $notification = ticket_notifications::find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
            $notification->delete();
            $this->fetchNotifications();
            // $this->emit('notificationMarkedAsRead');
        }
    }

    public function render()
    {
        return view('livewire.all-tickets-notifications');
    }
}
