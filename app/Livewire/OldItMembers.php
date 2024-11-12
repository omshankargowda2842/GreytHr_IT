<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\IT;
use Illuminate\Support\Facades\Log;
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
    try {
        // Fetch IT members from EmployeeDetails
        $this->itMembers = EmployeeDetails::where('sub_dept_id', '9915')
            ->where('dept_id', '8803')
            ->get();

        // Fetch related IT employees with a status of 0
        $this->itRelatedEmye = IT::where('status', 0)->get();
    } catch (\Exception $e) {
        // Log the error for debugging purposes
        Log::error('Error during component initialization in mount: ' . $e->getMessage());

        // Reset properties to avoid undefined behavior
        $this->itMembers = collect();
        $this->itRelatedEmye = collect();

        // Flash a user-friendly error message
        FlashMessageHelper::flashError("An error occurred while initializing the component. Please try again.");
    }
}

    public $recordId;
    public function restore($id)
    {
        try {

            $this->recordId = $id;
            $itMember = IT::where('it_emp_id', $this->recordId)->first();

            if ($itMember) {
                // Restore the IT member's status
                $itMember->status = 1;
                $itMember->save();
                FlashMessageHelper::flashSuccess("IT member restored successfully!");
                $this->showLogoutModal = false;
                $this->itRelatedEmye = IT::where('status', 1)->get();
            } else {
                FlashMessageHelper::flashError("The IT member could not be found.");
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error restoring IT member: ' . $e->getMessage());

            // Flash an error message
            FlashMessageHelper::flashError("An error occurred while restoring the IT member. Please try again.");
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
        try {
            if ($this->sortColumn === $column) {
                // If the column is the same, toggle the sort direction
                $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                // If a different column is clicked, set it as the new sort column and default to ascending order
                $this->sortColumn = $column;
                $this->sortDirection = 'asc';
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error toggling sort order: ' . $e->getMessage());

            // Reset to default sorting as a fallback
            $this->sortColumn = 'emp_id'; // Replace with a relevant default column
            $this->sortDirection = 'asc';

            // Flash a user-friendly error message
            FlashMessageHelper::flashError("An error occurred while toggling the sort order. Please try again.");
        }
    }

    public function filter()
    {
        try {
            $trimmedEmpId = trim($this->searchEmp); // Trimmed search input

            return EmployeeDetails::with('its') // Eager load the 'its' relationship
                ->whereHas('its', function ($query) {
                    $query->where('status', 0) // Add condition for status = 1
                        ->whereColumn('employee_details.emp_id', 'it_employees.emp_id');
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
        } catch (\Exception $e) {
            // Log the exception message or handle as needed
            Log::error('Error in filter method: ' . $e->getMessage());

            // Optionally, return a default value or an error message
            return response()->json(['error' => 'An error occurred while filtering. Please try again later.'], 500);
        }
    }



    public function render()
    {
        try {
            $this->itRelatedEmye = $this->filter(); // Call to the filter method

            return view('livewire.old-it-members');
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error in render method: ' . $e->getMessage());

            // Optionally, you can return an error message or handle the error in another way
            return view('livewire.old-it-members')->with('error', 'An error occurred while loading the data.');
        }
    }

}
