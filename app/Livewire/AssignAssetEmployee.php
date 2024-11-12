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

        $this->categories = asset_types_table::select('id', 'asset_names')->get();

        $this->loadAssetsAndEmployees();

    }

    public function loadAssets()
    {
        // Check if a category is selected
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
    }


    public function loadAssetsAndEmployees()
    {
        // Fetch asset and employee data, including already assigned ones
        // $this->assetSelect = VendorAsset::join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
        // ->where('vendor_assets.is_active', 1)
        // ->where('asset_types_tables.id', $this->selectedCategory)
        // ->select('vendor_assets.asset_id', 'asset_types_tables.asset_names') // Select asset_id and asset_name
        // ->get();

        $this->assetSelectEmp = EmployeeDetails::where('status', 1)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

        $this->assignedAssetIds = AssignAssetEmp::where('is_active', 1)->pluck('asset_id')->toArray();
        $this->assignedEmployeeIds = AssignAssetEmp::where('is_active', 1)->pluck('emp_id')->toArray();

    }

    public function fetchEmployeeDetails()
    {

        if ($this->selectedEmployee !== "" && $this->selectedEmployee !== null) {
            $this->empDetails = EmployeeDetails::find($this->selectedEmployee);
        } else {

            $this->empDetails = null;
        }
    }

    public function fetchAssetDetails()
    {


        if ($this->selectedAsset !== "" && $this->selectedAsset !== null) {

            // $this->assetDetails = VendorAsset::where('asset_id', $this->selectedAsset)->first();

            $this->assetDetails = VendorAsset::join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
            ->where('vendor_assets.asset_id', $this->selectedAsset)
            ->select('vendor_assets.*', 'asset_types_tables.asset_names as asset_type_name')
            ->first();

        } else {

            $this->assetDetails = null;
        }
    }


    public function edit($id)
{
    // Set flags for creating/updating mode
    $this->showEMployeeAssetBtn = true;
    $this->showOldEMployeeAssetBtn = false;
    $this->showAssignAssetBtn = false;
    $this->assetEmpCreateUpdate = true;
    $this->employeeAssetListing = false;
    $this->showSystemUpdateForm =false;
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
    // $this->validate();

    // Get the asset type of the currently assigned asset for the selected employee
    $selectedAssetType = AssignAssetEmp::where('emp_id', $this->selectedEmployee)
    ->where('is_active', 1)  // Add the condition for 'is_active'
    ->pluck('asset_type')  // Get the 'asset_type' column
    ->toArray();  // Convert to an array

    $existingAssignment = VendorAsset::where('asset_id', $this->selectedAsset)->value('asset_type');


    $assetName = asset_types_table::where('id', $existingAssignment)->value('asset_names');


    if (in_array($existingAssignment, $selectedAssetType)) {

        // Show error message if the asset type already exists
        FlashMessageHelper::flashError("Asset type '{$assetName}' is already assigned to this Employee!");
        return;
    }


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

    public function confirmDelete($id)
    {
        $this->recordId = $id;
        $this->showLogoutModal = true;
    }

    public $deletionDate;
    public function delete()
    {
        $this->validate([

            'reason' => 'required|string|max:255', // Validate the remark input
        ], [
            'reason.required' => 'Reason is required.',
        ]);
        $this->resetErrorBag();

        $vendormember = AssignAssetEmp::find($this->recordId);
        if ($vendormember) {
            $vendormember->update([
                'delete_reason' => $this->reason,
                'deleted_at' => now(),
                'is_active' => 0
            ]);

            FlashMessageHelper::flashSuccess("Asset deactivated successfully!");

            $this->showLogoutModal = false;
            $this->recordId = null;
            $this->reason = '';
            //Refresh
            $employeeAssetLists =AssignAssetEmp::where('is_active', 1)->get();

             return view('livewire.assign-asset-employee',compact('employeeAssetLists'));

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
        if ($this->sortColumn == $column) {
            // If the column is the same, toggle the sort direction
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // If a different column is clicked, set it as the new sort column and default to ascending order
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {

        $assetTypes = asset_types_table::pluck('asset_names', 'id');

         // Use the filtered data if available, otherwise fetch all
         $employeeAssetLists = !empty($this->filteredEmployeeAssets)
         ? $this->filteredEmployeeAssets
         : AssignAssetEmp::with(['vendorAsset.vendor'])
         ->orderBy($this->sortColumn, $this->sortDirection)
         ->get();



         $employeeAssetLists = $employeeAssetLists->map(function ($employeeAssetList) use ($assetTypes) {
                $employeeAssetList['asset_type_name'] = $assetTypes[$employeeAssetList['asset_type']] ?? 'N/A';
                return $employeeAssetList; // Ensure you're returning the entire modified array

        });
        $this->loadAssets();
        $this->loadAssetsAndEmployees();
        return view('livewire.assign-asset-employee', compact('employeeAssetLists'));
    }


}
