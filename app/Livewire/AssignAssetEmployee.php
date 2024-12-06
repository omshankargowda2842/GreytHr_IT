<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\asset_types_table;
use App\Models\AssetAssignments;
use App\Models\AssignAssetEmp;
use App\Models\EmployeeDetails;
use App\Models\VendorAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AssignAssetEmployee extends Component
{
    public $assetSelect = [];
    public $assetSelectEmp = [];
    public $assignedAssetIds = [];
    public $assignedEmployeeIds = [];
    public $selectedAsset = null;
    public $assetDetails = null;
    public $empDetails = null;
    public $selectedEmployee = null;
    public $isUpdateMode = false;
    public $employeeAssetListing = true;
    public $searchFilters = true;
    public $assetEmpCreateUpdate = false;
    public $oldEmpAssetListing = false;
    public $assignmentId;
    public $searchEmp = '';
    public $searchAssetId = '';
    public $filteredEmployeeAssets = [];
    public $assetsFound = false;
    public $showEditDeleteEmployeeAsset = true;
    public $showEMployeeAssetBtn = false;
    public $showAssignAssetBtn = true;
    public $showOldEMployeeAssetBtn = true;
    public $showLogoutModal = false;
    public $oldAssetEmp = false;
    public $showSystemUpdateForm = false;
    public $systemName;
    public $macAddress;
    public $laptopReceived;
    public $sophosAntivirus ="";
    public $vpnCreation ="";
    public $teramind ="";
    public $systemUpgradation ="";
    public $oneDrive ="";
    public $screenshotPrograms ="";
    public $isRotated = false;
    public $showOverview = false;
    public $categories;
    public $selectedCategory="";
    public $showViewEmployeeAsset = false;
public $currentVendorId = null;
public $selectedStatus ='';
public $deleteAsset_id;

    public function oldAssetlisting(){

        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = false;
        $this->showEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = false;
        $this->showOldEMployeeAssetBtn = false;
        $this->searchEmp="";
        $this->oldAssetEmp = true;

    }

    public function assetlisting(){
         $this->showOldEMployeeAssetBtn =true;
        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = true;
        $this->showEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = true;
        $this->showOldEMployeeAssetBtn = false;


    }
    public function assignAsset()
    {       $this->resetForm();
        $this->assetEmpCreateUpdate = true;
        $this->oldAssetEmp = false;
        $this->employeeAssetListing = false;
        $this->showAssignAssetBtn = false;
        $this->showEMployeeAssetBtn = true;
        $this->showOldEMployeeAssetBtn =false;
        $this->showEditDeleteEmployeeAsset = false;
        $this->showSystemUpdateForm =false;
        $this->isUpdateMode = false;
        $this->resetErrorBag(['selectedAsset', 'selectedEmployee']);
    }

    protected function rules()
    {
        return [
            'selectedAsset' => 'required|string|max:255',
            'selectedEmployee' => 'required|string|max:255',
        ];
    }

    protected $messages = [
        'selectedAsset.required' => 'Asset Id is required.',
        'selectedEmployee.required' => 'Employee Id is required.',
    ];


    public function viewDetails($employeeAssetList)
{

    $this->searchFilters =false;
    $this->showOldEMployeeAssetBtn =false;
    $this->showAssignAssetBtn =false;
    $this->currentVendorId = $employeeAssetList;
    $this->showViewEmployeeAsset = true;
    $this->showEditDeleteEmployeeAsset = false;
    // $this->editMode = false;
}

public $oldAssetBackButton=true;
public function viewOldAssetDetails($employeeAssetList){
    $this->searchFilters =false;
    $this->oldAssetBackButton =false;
    $this->showOldEMployeeAssetBtn =false;
    $this->showAssignAssetBtn =false;
    $this->currentVendorId = $employeeAssetList;
    $this->showViewEmployeeAsset = true;
    $this->showEditDeleteEmployeeAsset = false;
}

public function closeViewVendor()
{
    $this->resetForm();
    $this->searchFilters =true;
    $this->showOldEMployeeAssetBtn =true;
    $this->assetEmpCreateUpdate = false;
    $this->employeeAssetListing = true;
    $this->showEMployeeAssetBtn = false;
    $this->showAssignAssetBtn = true;
    $this->showLogoutModal = false;
    $this->showViewEmployeeAsset = false;
    $this->showEditDeleteEmployeeAsset = true;
    $this->currentVendorId = null;
    $this->oldAssetEmp = false;
    $this->isUpdateMode = false;
    $this->searchEmp="";
    return redirect()->route('employeeAssetList');
}

public function backVendor()
{
    $this->resetForm();
    $this->searchFilters =true;
    $this->showOldEMployeeAssetBtn =true;
    $this->assetEmpCreateUpdate = false;
    $this->employeeAssetListing = true;
    $this->showEMployeeAssetBtn = false;
    $this->showAssignAssetBtn = true;
    $this->showLogoutModal = false;
    $this->showViewEmployeeAsset = false;
    $this->showEditDeleteEmployeeAsset = true;
    $this->currentVendorId = null;
    $this->oldAssetEmp = false;
    $this->isUpdateMode = false;
    $this->validateOnly('selectedAsset');
    $this->validateOnly('selectedEmployee');

    $this->resetErrorBag(['selectedAsset', 'selectedEmployee']);


}

public function closeViewEmpAsset()
{
    $this->resetForm();
    $this->searchFilters =true;
    $this->oldAssetBackButton =true;
    $this->showOldEMployeeAssetBtn =false;
    $this->assetEmpCreateUpdate = false;
    $this->employeeAssetListing = false;
    $this->showEMployeeAssetBtn = false;
    $this->showAssignAssetBtn = false;
    $this->showLogoutModal = false;
    $this->showViewEmployeeAsset = false;
    $this->showEditDeleteEmployeeAsset = true;
    $this->currentVendorId = null;
    $this->oldAssetEmp = true;
    $this->isUpdateMode = false;

}



    private function resetForm()
    {
        $this->sophosAntivirus = null;
        $this->vpnCreation = null;
        $this->teramind = null;
        $this->systemUpgradation = null;
        $this->oneDrive = null;
        $this->screenshotPrograms = null;
        $this->macAddress = '';
        $this->laptopReceived = null;
        $this->selectedAsset = null;
        $this->selectedEmployee = null;
        $this->assetDetails = null;
        $this->empDetails = null;
        $this->isUpdateMode = false;
        $this->assignmentId = null;
    }

    public function mount()
    {
        try {
            $this->categories = asset_types_table::select('id', 'asset_names')->get();
            $this->loadAssetsAndEmployees();

        } catch (\Exception $e) {
            Log::error("Error during mount method execution: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            FlashMessageHelper::flashError('An error occurred while loading categories or assets.');
            $this->categories = [];

        }
    }

    public function loadAssets()
    {
        try {
            if ($this->selectedCategory == null || $this->selectedCategory == '') {

                // Load all assets if no category is selected
                $this->assetSelect = VendorAsset::join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
                    ->where('vendor_assets.is_active', 1)
                    ->select('vendor_assets.asset_id', 'asset_types_tables.asset_names')
                    ->get();
            } else {

                // Load assets based on selected category
                $this->assetSelect = VendorAsset::join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
                    ->where('vendor_assets.is_active', 1)
                    ->where('asset_types_tables.id', $this->selectedCategory)
                    ->select('vendor_assets.asset_id', 'asset_types_tables.asset_names')
                    ->get();
            }

        } catch (\Exception $e) {
            // Log the error message
            Log::error("Error loading assets: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            FlashMessageHelper::flashError('An error occurred while loading assets.');
            $this->assetSelect = collect(); // Set to an empty collection if there's an error
        }
    }



    public function loadAssetsAndEmployees()
{
    try {
        // Fetching employee details
        $this->assetSelectEmp = EmployeeDetails::where('status', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // Fetching assigned asset IDs and employee IDs
        $this->assignedAssetIds = AssignAssetEmp::where('is_active', 1)->pluck('asset_id')->toArray();
        $this->assignedEmployeeIds = AssignAssetEmp::where('is_active', 1)->pluck('emp_id')->toArray();

    } catch (\Exception $e) {
        // Log the error
        Log::error("Error loading assets and employees: " . $e->getMessage(), [
            'exception' => $e,
        ]);

        FlashMessageHelper::flashError('An error occurred while loading assets and employees.');
        $this->assetSelectEmp = collect();
        $this->assignedAssetIds = [];
        $this->assignedEmployeeIds = [];
    }
}


    public function fetchEmployeeDetails()
    {
        try {
            if ($this->selectedEmployee !== "" && $this->selectedEmployee !== null) {
                // Fetch employee details by ID
                $this->resetErrorBag();
                $this->empDetails = EmployeeDetails::find($this->selectedEmployee);
            } else {
                $this->empDetails = null;
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error fetching employee details: " . $e->getMessage(), [
                'exception' => $e,
            ]);

            FlashMessageHelper::flashError('An error occurred while fetching employee details.');
            $this->empDetails = null;
        }
    }


    public function fetchAssetDetails()
    {
        try {
            if ($this->selectedAsset !== "" && $this->selectedAsset !== null) {
                $this->resetErrorBag();
                // Fetch asset details by asset ID
                $this->assetDetails = VendorAsset::join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
                    ->where('vendor_assets.asset_id', $this->selectedAsset)
                    ->select('vendor_assets.*', 'asset_types_tables.asset_names as asset_type_name')
                    ->first();
            } else {
                $this->assetDetails = null;
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error fetching asset details: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            FlashMessageHelper::flashError('An error occurred while fetching asset details.');
            $this->assetDetails = null;
        }
    }



    public function edit($id)
    {
        try {
            // Set flags for creating/updating mode
            $this->showEMployeeAssetBtn = true;
            $this->showOldEMployeeAssetBtn = false;
            $this->showAssignAssetBtn = false;
            $this->assetEmpCreateUpdate = true;
            $this->employeeAssetListing = false;
            $this->showSystemUpdateForm = false;
            $this->isUpdateMode = true;

            // Fetch the assignment record
            $assignment = AssignAssetEmp::findOrFail($id);

            // Populate the component properties with the assignment data
            $this->assignmentId = $assignment->id;
            $this->selectedAsset = $assignment->asset_id;
            $this->selectedEmployee = $assignment->emp_id;

            $this->sophosAntivirus = $assignment->sophos_antivirus;
            $this->vpnCreation = $assignment->vpn_creation;
            $this->teramind = $assignment->teramind;
            $this->systemUpgradation = $assignment->system_upgradation;
            $this->oneDrive = $assignment->one_drive;
            $this->screenshotPrograms = $assignment->screenshot_programs;
            $this->macAddress = $assignment->mac_address;
            $this->laptopReceived = $assignment->laptop_received;

            $this->validateOnly('selectedAsset');
            $this->validateOnly('selectedEmployee');

            $this->resetErrorBag(['selectedAsset', 'selectedEmployee']);

            // Fetch asset and employee details
            $this->fetchAssetDetails();
            $this->fetchEmployeeDetails();
        } catch (\Exception $e) {
            // Log the error with detailed information
            Log::error("Error editing asset assignment: " . $e->getMessage(), [
                'exception' => $e,
                'assignment_id' => $id,
            ]);

            FlashMessageHelper::flashError('An error occurred while fetching or updating the asset assignment.');

            $this->reset(['selectedAsset', 'selectedEmployee', 'sophosAntivirus', 'vpnCreation', 'teramind', 'systemUpgradation', 'oneDrive', 'screenshotPrograms', 'macAddress', 'laptopReceived']);
        }
    }


// public function getAssetOwners($assetId)
//     {
//         $currentOwner = AssignAssetEmp::where('asset_id', $assetId)
//             ->where('is_active', true)
//             ->first();

//         $previousOwners = AssetAssignments::where('asset_id', $assetId)
//             ->where('is_active', false)
//             ->get();

//         return response()->json([
//             'currentOwner' => $currentOwner,
//             'previousOwners' => $previousOwners
//         ]);
//     }


    public function toggleSystemUpdateForm()
    {
        $this->showSystemUpdateForm = !$this->showSystemUpdateForm;
        $this->isRotated = !$this->isRotated;
    }

    public function toggleOverview()
    {
        $this->showOverview = !$this->showOverview;
        $this->toggleSystemUpdateForm();
    }

public $selectedAssetType='';
    public function submit()
{

    $this->validate();

    // Get the asset type of the currently assigned asset for the selected employee
    $selectedAssetType = AssignAssetEmp::where('emp_id', $this->selectedEmployee)
    ->where('is_active', 1)  // Add the condition for 'is_active'
    ->pluck('asset_type')  // Get the 'asset_type' column
    ->toArray();  // Convert to an array


    $existingAssignment = VendorAsset::where('asset_id', $this->selectedAsset)->value('asset_type');


    $assetName = asset_types_table::where('id', $existingAssignment)->value('asset_names');

    $employeeDetails = EmployeeDetails::find($this->selectedEmployee);
    $empId = $employeeDetails->emp_id;

    $uniqueManagerDeptHeads = EmployeeDetails::select('manager_id', 'dept_head')
    ->distinct()
    ->get()
    ->pluck('manager_id')  // Get the unique manager_id
    ->merge(EmployeeDetails::distinct()->pluck('dept_head'))  // Get the unique dept_head
    ->unique()  // Merge both columns and ensure uniqueness
    ->toArray();  // Convert to an array


    // if (in_array($empId , $uniqueManagerDeptHeads)) {




    // }
    // elseif( !$selectedAssetType  )
    // {
    // }
    //  else {

    //     If the asset type is already assigned to the employee, prevent assigning it again
    //     if (in_array($existingAssignment, $selectedAssetType)) {
    //         Show error message if the asset type already exists
    //         FlashMessageHelper::flashError("Asset type '{$assetName}' is already assigned to this Employee!");
    //         return;
    //     }

    // }

    try {
        if ($this->isUpdateMode && $this->assignmentId) {
            // Retrieve the current assignment
            $currentAssignment = AssignAssetEmp::findOrFail($this->assignmentId);

            // Check if asset_id has changed
            if ($currentAssignment->asset_id !== $this->selectedAsset) {
                // Archive the current assignment by setting is_active to false
                $currentAssignment->update([
                    'is_active' => false,
                ]);

                // Create new assignment
                AssignAssetEmp::create([
                    'asset_id' => $this->selectedAsset,
                    'emp_id' => $this->empDetails->emp_id,
                    'manufacturer' => $this->assetDetails->manufacturer,
                    'asset_type' => $this->assetDetails->asset_type,
                    'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                    'department' => $this->empDetails->job_role,
                    'sophos_antivirus' => $this->sophosAntivirus,
                    'vpn_creation' => $this->vpnCreation,
                    'teramind' => $this->teramind,
                    'system_upgradation' => $this->systemUpgradation,
                    'one_drive' => $this->oneDrive,
                    'screenshot_programs' => $this->screenshotPrograms,
                    'mac_address' => $this->macAddress,
                    'laptop_received' => $this->laptopReceived,
                    'is_active' => true,
                ]);

                FlashMessageHelper::flashSuccess("Assignee updated successfully!");
            } else {
                // No change in asset_id, so just update the existing assignment without creating a new record
                $currentAssignment->update([
                    'emp_id' => $this->empDetails->emp_id,
                    'manufacturer' => $this->assetDetails->manufacturer,
                    'asset_type' => $this->assetDetails->asset_type,
                    'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                    'department' => $this->empDetails->job_role,
                    'sophos_antivirus' => $this->sophosAntivirus,
                    'vpn_creation' => $this->vpnCreation,
                    'teramind' => $this->teramind,
                    'system_upgradation' => $this->systemUpgradation,
                    'one_drive' => $this->oneDrive,
                    'screenshot_programs' => $this->screenshotPrograms,
                    'mac_address' => $this->macAddress,
                    'laptop_received' => $this->laptopReceived,
                    'is_active' => true,
                ]);

                FlashMessageHelper::flashSuccess("Assignment updated successfully!");
            }
        } else {
            // Create new assignment as there is no existing one
            AssignAssetEmp::create([
                'asset_id' => $this->selectedAsset,
                'emp_id' => $this->empDetails->emp_id,
                'manufacturer' => $this->assetDetails->manufacturer,
                'asset_type' => $this->assetDetails->asset_type,
                'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                'department' => $this->empDetails->job_role,
                'sophos_antivirus' => $this->sophosAntivirus,
                'vpn_creation' => $this->vpnCreation,
                'teramind' => $this->teramind,
                'system_upgradation' => $this->systemUpgradation,
                'one_drive' => $this->oneDrive,
                'screenshot_programs' => $this->screenshotPrograms,
                'mac_address' => $this->macAddress,
                'laptop_received' => $this->laptopReceived,
                'is_active' => true,
            ]);

            FlashMessageHelper::flashSuccess("Asset assigned to employee successfully!");
        }

        $this->resetForm();

        return redirect()->route('employeeAssetList');
    } catch (\Exception $e) {

        Log::error('Error while assigning asset: ' . $e->getMessage());

        FlashMessageHelper::flashError("An error occurred while saving the details. Please try again!");
    }
}



    public function filter()
    {
        try {
            $trimmedEmpId = trim($this->searchEmp);

            $this->filteredEmployeeAssets = AssignAssetEmp::query()
            ->when($trimmedEmpId, function ($query) use ($trimmedEmpId) {
                            $query->where(function ($query) use ($trimmedEmpId) {
                                $query->where('emp_id', 'like', '%' . $trimmedEmpId . '%')
                                    ->orWhere('employee_name', 'like', '%' . $trimmedEmpId . '%')
                                    ->orWhere('asset_id', 'like', '%' . $trimmedEmpId . '%')
                                    ->orWhere('manufacturer', 'like', '%' . $trimmedEmpId . '%')
                                    ->orWhere('asset_type', 'like', '%' . $trimmedEmpId . '%')
                                    ->orWhere('department', 'like', '%' . $trimmedEmpId . '%');
                            });
                        })

                ->get();

            $this->assetsFound = count($this->filteredEmployeeAssets) > 0;
        } catch (\Exception $e) {
            Log::error('Error in filter method: ' . $e->getMessage());
        }
    }

    // Define the updateSearch method if you need it
    public function updateSearch()
    {
        $this->filter();
    }

    public function clearFilters()
    {
        // Reset search fields and filtered results
        // $this->searchEmp = '';
        // $this->searchAssetId = '';
        $this->reset();
        $this->filteredEmployeeAssets = [];
        $this->assetsFound = false;

    }

    public $recordId;
    public $reason =[];

    public function confirmDelete($id,$asset_id  )
    {
        // dd($asset_id);
        $this->recordId = $id;
        $this->deleteAsset_id=$asset_id;
        $this->showLogoutModal = true;
        $this->resetErrorBag();

    }

    public $deletionDate;
    public function delete()
    {
        $this->validate([
            'reason' => 'required|string|max:255', // Validate the remark input
            'selectedStatus'=>'required'
        ],
         [
            'reason.required' => 'Reason is required.',
            'selectedStatus.required' => 'Asset status is required.',
        ]);

        try {
            // Validate the reason input

            $this->resetErrorBag();

            // Find the AssignAssetEmp record by the provided ID
            $vendormember = AssignAssetEmp::find($this->recordId);
            $vendorAsset = VendorAsset::where('asset_id',$this->deleteAsset_id)->first();
            // dd($vendorAsset->status);
            if ($vendorAsset) {
                $vendorAsset->status = $this->selectedAsset;
                $vendorAsset->save();
            }
            if ($vendormember) {
                // Update the record with delete reason, deactivation, and timestamp
                $vendormember->update([
                    'delete_reason' => $this->reason,
                    'deleted_at' => now(),
                    'is_active' => 0,
                    'status'=>$this->selectedAsset,
                ]);

                // Success flash message
                FlashMessageHelper::flashSuccess("Asset deactivated successfully!");

                // Reset modal state and input values
                $this->showLogoutModal = false;
                $this->recordId = null;
                $this->reason = '';

                // Fetch updated employee asset list
                $employeeAssetLists = AssignAssetEmp::where('is_active', 1)->get();

                // Return updated view
                return view('livewire.assign-asset-employee', compact('employeeAssetLists'));

            } else {
                // If the record does not exist, log an error and show a message
                Log::error("AssignAssetEmp record not found", ['record_id' => $this->recordId]);
                FlashMessageHelper::flashError('Asset not found.');
            }
        } catch (\Exception $e) {
            // Catch any exceptions and log the error
            Log::error("Error during asset deactivation: " . $e->getMessage(), [
                'exception' => $e,
                'record_id' => $this->recordId,
                'reason' => $this->reason,
            ]);

            // Flash error message for the user
            FlashMessageHelper::flashError('An error occurred while deactivating the asset.');

            // Optionally reset or set any default values
            $this->reset(['reason', 'recordId']);
        }
    }

    public function cancel(){
        $this->searchFilters =true;
        $this->showOldEMployeeAssetBtn =true;
         $this->showEMployeeAssetBtn = false;
        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = true;

        $this->showAssignAssetBtn = true;
        $this->showLogoutModal = false;
        $this->resetErrorBag();

    }

public $oldEmployeeAssetLists;

    public $sortColumn = 'emp_id'; // default sorting column
    public $sortDirection = 'asc'; // default sorting direction

    public function toggleSortOrder($column)
    {
        try {

        if ($this->sortColumn == $column) {
            // If the column is the same, toggle the sort direction
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // If a different column is clicked, set it as the new sort column and default to ascending order
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }

    } catch (\Exception $e) {
        // Log the error message
        Log::error('Error in toggleSortOrder: ' . $e->getMessage());

        // Optionally, set default sort direction or handle the error gracefully
        $this->sortColumn = 'emp_id'; // Example default sort column
        $this->sortDirection = 'asc'; // Example default sort direction

        // You may want to display an error message to the user, if needed
        session()->flash('error', 'An error occurred while changing the sort order.');
    }
    }

    public function render()
    {
        try {
            $assetTypes = asset_types_table::pluck('asset_names', 'id');
            $employeeAssetLists = !empty($this->filteredEmployeeAssets)
                ? $this->filteredEmployeeAssets
                : AssignAssetEmp::with(['vendorAsset.vendor'])
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->get();

            $employeeAssetLists = $employeeAssetLists->map(function ($employeeAssetList) use ($assetTypes) {
                $employeeAssetList['asset_type_name'] = $assetTypes[$employeeAssetList['asset_type']] ?? 'N/A';
                return $employeeAssetList; // Return the entire modified array
            });

            // Load assets and employees
            $this->loadAssets();
            $this->loadAssetsAndEmployees();
            return view('livewire.assign-asset-employee', compact('employeeAssetLists'));

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Error occurred while rendering the AssignAssetEmployee view", [
                'exception' => $e,
                'filteredEmployeeAssets' => $this->filteredEmployeeAssets,
                'sortColumn' => $this->sortColumn,
                'sortDirection' => $this->sortDirection,
            ]);

            FlashMessageHelper::flashError("An error occurred while loading employee assets.");
            return view('livewire.assign-asset-employee', ['employeeAssetLists' => collect()]);
        }
    }


}
