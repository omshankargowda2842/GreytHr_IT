<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\IT;
use Livewire\Component;

class OldItMembers extends Component
{
    public $itMembers = [];
    public $itRelatedEmye = [];
    public $showLogoutModal =false;
     public $searchFilters = true;
     public $searchEmp = '';

    public function mount()
    {
        $this->itMembers = EmployeeDetails::where('sub_dept_id', '9915')
        ->where('dept_id', '8803')
        ->get();
        $this->itRelatedEmye = IT::where('status', 0)->get();

    }
    public $recordId;
    public function restore($id)
    {
        $this->recordId = $id;
        $itMember = IT::where('it_emp_id',  $this->recordId )->first();
        if ($itMember) {
            $itMember->status = 1;
            $itMember->save();
            FlashMessageHelper::flashSuccess("IT member Restored successfully!");
            $this->showLogoutModal = false;
            $this->itRelatedEmye = IT::where('status', 1)->get();
        }
    }



    public function cancelLogout()
    {
         $this->showLogoutModal = true;
    }

    public function cancel(){
        $this->showLogoutModal = false;
    }


    public $sortColumn = 'emp_id'; // default sorting column
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


    public function filter()
{
    $trimmedEmpId = trim($this->searchEmp); // Trimmed search input

    return EmployeeDetails::with('its') // Eager load the 'its' relationship
        ->whereHas('its', function ($query) {
            $query->where('status', 0) // Add condition for status = 1
            ->whereColumn('employee_details.emp_id', 'i_t.emp_id');
        })
        ->when($trimmedEmpId, function ($query) use ($trimmedEmpId) {
            // Apply the search filters based on input
            $query->where(function ($query) use ($trimmedEmpId) {
                $query->where('emp_id', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhereHas('its', function ($query) use ($trimmedEmpId) {
                        // Filtering IT employee details as well
                        $query->where('it_emp_id', 'like', '%' . $trimmedEmpId . '%');
                    })
                    ->orWhere('first_name', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhere('last_name', 'like', '%' . $trimmedEmpId . '%')
                    ->orWhere('email', 'like', '%' . $trimmedEmpId . '%');
            });
        })
        ->orderBy($this->sortColumn, $this->sortDirection)
        ->get();
}



    public function render()
    {

        $this->itRelatedEmye = $this->filter();
       return view('livewire.old-it-members');
    }
}
