<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use Livewire\Component;
use App\Models\IT;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ItAddMember extends Component
{
    public $itmember = true;
    public $itmember1 = false;
    public $itRelatedEmye = [];
    public $assetSelectEmp = [];
    public $empDetails = null;
    public $selectedEmployee = null;

    public function addMember()
    {

        $this->itmember1 = true;
        $this->itmember = false;
    }

    public function Cancel()
    {
        $this->itmember1 = false;
        $this->itmember = true;
    }

    public $sortColumn = 'employee_name'; // default sorting column
    public $sortDirection = 'asc'; // default sorting direction

    public function toggleSortOrder($column)
    {
        if ($this->sortColumn == $column) {
            // If the column is the same, toggle the sort direction
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // If a different column is clicked, set it as the new sort column and default to ascending order
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function fetchEmployeeDetails()
    {

        if ($this->selectedEmployee !== "" && $this->selectedEmployee !== null) {
            $this->empDetails = EmployeeDetails::find($this->selectedEmployee);
        } else {

            $this->empDetails = null;
        }
    }


    public function mount()
    {

        $this->loadAssetsAndEmployees();
    }

    public function loadAssetsAndEmployees()
    {
        $this->assetSelectEmp = EmployeeDetails::orderBy('first_name', 'asc')->get();
    }


    public function submit()
    {
        try {

            // Attempt to create a new IT record
            IT::create([
                'emp_id' => $this->empDetails->emp_id,
                'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                'email' => $this->empDetails->email,

            ]);

            // Flash success message if everything works
            FlashMessageHelper::flashSuccess("IT member added successfully!");

            // Reset the form or any related state (if needed)
            $this->resetForm();
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error adding IT member: ' . $e->getMessage());
            // Flash an error message to the user
            FlashMessageHelper::flashError("There was an error adding the IT member. Please try again.");
        }
    }



    public function render()
    {
        $this->itRelatedEmye = IT::where('status', 1)
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();


        return view('livewire.it-add-member');
    }
}
