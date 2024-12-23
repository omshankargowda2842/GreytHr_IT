<?php

namespace App\Livewire;

use App\Models\ticket_notifications;
use Livewire\Component;

class MainLayout extends Component
{
    public $employeeInitials;
    public $employeeName;
    private function getInitials($name)
    {
        $nameParts = explode(' ', $name);

        if (count($nameParts) < 2) {
            // If the name has less than 2 parts, just return the first letter of the first part
            return strtoupper(substr($nameParts[0], 0, 1));
        }

        // Extract the first letter of the first part and the last part
        $firstInitial = strtoupper(substr($nameParts[0], 0, 1));
        $lastInitial = strtoupper(substr($nameParts[count($nameParts) - 1], 0, 1));

        return $firstInitial . $lastInitial;
    }

    public function mount()
    {

        $this->fetchUnreadCount();
        $employee = auth()->guard('it')->user();

        if ($employee) {
            $this->employeeName = $employee->employee_name;
            $this->employeeInitials = $this->getInitials($this->employeeName);
        } else {
            $this->employeeInitials = 'N/A'; // Default or placeholder initials
            $this->employeeName = 'N/A';
        }
    }

    public $isVisible = false;

    public function toggleNotifications()
    {
        $this->isVisible = !$this->isVisible;
    }


    public $unreadCount;

    protected $listeners = ['notificationMarkedAsRead' => 'fetchUnreadCount'];


    public function fetchUnreadCount()
    {
        $this->unreadCount = ticket_notifications::where('is_read', false)->count();

    }


    public function incRequest(){
        return redirect()->route('incidentRequests');
    }

    public function serRequest(){
        return redirect()->route('serviceRequests');
    }

    public function getActiveTab()
    {
        $routeName = request()->route()->getName();
        $tabs = [
            'dashboard' => 'dashboard',
            'requests' => 'itRequest',
            'itMembers' => 'itMembers',
            'oldItMembers' => 'oldRecords',
            'vendor' => 'vendor',
            'vendorAssets' => 'vendorAssets',
        ];

        return array_search($routeName, $tabs);
    }


    public function dashboard()
    {
        return redirect()->route('dashboard');
    }


    public function itRequest()
    {
        return redirect()->route('requests');
    }

    public function itMembers()
    {
        return redirect()->route('itMembers');
    }
    public function oldRecords()
    {
        return redirect()->route('oldItMembers');
    }

    public function vendor()
    {
        return redirect()->route('vendors');
    }

    public function vendorAssets()
    {
        return redirect()->route('vendorAssets');
    }
    public function assets()
    {
        return redirect()->route('assetsList');
    }


    public function assignAsset()
    {
        return redirect()->route('employeeAssetList');
    }


    public function render()
    {
        return view('livewire.main-layout');
    }
}
