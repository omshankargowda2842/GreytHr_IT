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
    public $addItmember = false;
    public $itRelatedEmye = [];
    public $assetSelectEmp = [];
    public $empDetails = null;
    public $selectedEmployee = "";
    public $selectedRole = "";
    public $searchFilters = true;
    public $searchEmp = '';
    public $searchAssetId = '';
    public $assetsFound = false;
    public $showLogoutModal = false;
    public $filteredEmployeeAssets = [];
    public $reason = [];

    protected function rules()
    {
        return [

            'selectedEmployee' => 'required|string|max:255',
            'selectedRole' => 'required|string|max:255',
        ];
    }

    protected $messages = [
        'selectedEmployee.required' => 'Employee ID is required.',
        'selectedRole.required' => 'Role is required.',
    ];

    public function addMember()
    {
        $this->resetForm();
        $this->empDetails = null;
        $this->addItmember = true;
        $this->itmember = false;
        $this->searchFilters = false;
        $this->resetErrorBag('selectedEmployee');
        $this->resetErrorBag('selectedRole');
    }

    public function Cancel()
    {
        $this->addItmember = false;
        $this->showLogoutModal = false;
        $this->itmember = true;
        $this->empDetails = null;
        $this->searchFilters = true;
        $this->resetErrorBag('selectedEmployee');
        $this->resetErrorBag('selectedRole');
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


    public function fetchEmployeeDetails()
    {
        try {
            if ($this->selectedEmployee !== "" && $this->selectedEmployee !== null) {
                // Fetch the employee details using the selected employee ID
                $this->empDetails = EmployeeDetails::find($this->selectedEmployee);
                $this->resetErrorBag('selectedEmployee');
            } else {
                $this->empDetails = null;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching employee details: ' . $e->getMessage());
            $this->empDetails = null;
            FlashMessageHelper::flashError("An error occurred while fetching employee details. Please try again.");
        }
    }


    public function mount()
    {
        try {
            // Call the method to load assets and employees
            $this->loadAssetsAndEmployees();
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error during component initialization: ' . $e->getMessage());
            // Optionally, reset properties to avoid undefined behavior
            $this->assetSelectEmp = collect();
            $this->assignedEmployeeIds = [];

            // Flash a user-friendly error message
            FlashMessageHelper::flashError("An error occurred while initializing the component. Please try again.");
        }
    }


    public $assignedEmployeeIds;
    public function loadAssetsAndEmployees()
    {
        try {
            $this->assetSelectEmp = EmployeeDetails::where('sub_dept_id', '9915')
                ->where('dept_id', '8803')
                ->where('status', 1)
                ->orderBy('first_name', 'asc')
                ->get();

            $this->assignedEmployeeIds = IT::pluck('emp_id')->toArray();
        } catch (\Exception $e) {
            Log::error('Error loading assets and employees: ' . $e->getMessage());
            $this->assetSelectEmp = collect(); // Empty collection for employees
            $this->assignedEmployeeIds = []; // Empty array for employee IDs
            FlashMessageHelper::flashError("An error occurred while loading data. Please try again.");
        }
    }

    private function resetForm()
    {
        $this->selectedEmployee = "";
        $this->selectedRole = "";
    }


    public function cancelLogout()
    {
        $this->showLogoutModal = true;
    }

    public $recordId;
    public function confirmDelete($id)
    {

        $this->recordId = $id; // Assign the ID first
        $this->showLogoutModal = true; // Show the modal after assigning the ID
    }


    public function delete()
    {
        $this->validate([
            'reason' => 'required|string|max:255', // Validate the remark input
        ], [
            'reason.required' => 'Reason is required.',
        ]);

        try {
            // Find the IT member record by ID
            $itMember = IT::where('it_emp_id', $this->recordId)->first();

            if ($itMember) {
                $itMember->update([
                    'delete_itmember_reason' => $this->reason,
                    'status' => 0
                ]);

                if ($itMember->it_emp_id === Auth::guard('it')->user()->it_emp_id) {

                    Auth::guard('it')->logout();
                       // Redirect the user to the login page
                FlashMessageHelper::flashSuccess("Your account has been deactivated. Please contact support.");
                return redirect()->route('itlogin'); // Redirect to the login route

                }



                // Flash success message
                FlashMessageHelper::flashSuccess("IT member deactivated successfully!");

                // Reset the modal state and form data
                $this->showLogoutModal = false;
                $this->recordId = null;
                $this->reason = '';
            } else {
                // Flash error if the record is not found
                FlashMessageHelper::flashError("IT member not found.");
            }
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error deactivating IT member: ' . $e->getMessage());

            // Flash error message for the user
            FlashMessageHelper::flashError("An error occurred while deactivating the IT member. Please try again.");
        }
    }






    public function submit()
    {
        $this->validate();
        try {


            if ($this->selectedRole == 'super_admin') {
                // Check how many members already have the "super_admin" role
                $superAdminCount = IT::where('role', 'super_admin')->count();

                // If there are already 2 super admins, prevent adding another
                if ($superAdminCount >= 4 ) {
                    // Flash an error message
                    FlashMessageHelper::flashError("You can only assign the Super Admin role to a maximum of 2 members.");
                    return; // Stop further execution
                }
            }

            // Attempt to create a new IT record
            IT::create([
                'emp_id' => $this->empDetails->emp_id,
                'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                'email' => $this->empDetails->email,
                'password' => bcrypt('ags@123'),
                'role' => $this->selectedRole,
            ]);

            // Flash success message if everything works
            FlashMessageHelper::flashSuccess("IT member added successfully!");

            // Reset the form or any related state (if needed)
            $this->resetForm();
            return redirect()->route('itMembers');
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error adding IT member: ' . $e->getMessage());
            // Flash an error message to the user
            FlashMessageHelper::flashError("There was an error adding the IT member. Please try again.");
        }
    }

    public function resetValidationForField($field)
    {

        // Reset error for the specific field when typing
        $this->resetErrorBag($field);

    }

    public function filter()
    {
        try {
            $trimmedEmpId = trim($this->searchEmp); // Trimmed search input

            $employees = EmployeeDetails::with('its') // Eager load the 'its' relationship
                ->whereHas('its', function ($query) {
                    $query->where('status', 1) // Add condition for status = 1
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
                ->when($this->sortColumn && $this->sortDirection, function ($query) {
                    if ($this->sortColumn == 'it_emp_id') {
                        $query->join('it_employees', 'employee_details.emp_id', '=', 'it_employees.emp_id')
                              ->orderBy('it_employees.it_emp_id', $this->sortDirection);
                    } else {
                        $query->orderBy($this->sortColumn, $this->sortDirection);
                    }
                })
                ->get();

            return $employees; // Return the filtered collection
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error filtering employees: ' . $e->getMessage());

            // Optionally, return an empty collection or handle the error gracefully
            FlashMessageHelper::flashError("An error occurred while filtering employees. Please try again.");
            return collect(); // Return an empty collection to avoid breaking the caller
        }
    }



    public function render()
    {
        try {
            $this->itRelatedEmye = $this->filter();
        } catch (\Exception $e) {
            Log::error('Error rendering IT Add Member component: ' . $e->getMessage());
            $this->itRelatedEmye = collect();
            FlashMessageHelper::flashError("An error occurred while loading data. Please try again.");
        }

        return view('livewire.it-add-member');
    }

}
