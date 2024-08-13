<?php

namespace App\Livewire;

use Livewire\Component;

class MainLayout extends Component
{
    public $employeeInitials;

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

        $employeeName = auth()->guard('it')->user()->employee_name;
        if ($employeeName) {
            $this->employeeInitials = $this->getInitials($employeeName);
        } else {
            $this->employeeInitials = 'N/A'; // Default or placeholder initials
        }
    }

    public function dashboard(){
        return redirect()->route('dashboard');
    }


    public function itRequest(){
        return redirect()->route('requests');
    }

    public function itMembers(){
        return redirect()->route('itMembers');
    }

    public function render()
    {
        return view('livewire.main-layout');
    }
}
