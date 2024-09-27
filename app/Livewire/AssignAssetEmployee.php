<?php

namespace App\Livewire;

use App\Models\asset_types_table;
use App\Models\AssetAssignments;
use App\Models\AssignAssetEmp;
use App\Models\EmployeeDetails;
use App\Models\VendorAsset;
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

    public $showViewEmployeeAsset = false;
public $currentVendorId = null;

    public function oldAssetlisting(){
        $this->oldAssetEmp = true;
        $this->assetEmpCreateUpdate = false;
        $this->employeeAssetListing = false;
        $this->showEMployeeAssetBtn = false;
        $this->showAssignAssetBtn = false;
        $this->showOldEMployeeAssetBtn = false;

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
        $this->isUpdateMode = false;
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
        $this->selectedAsset = null;
        $this->selectedEmployee = null;
        $this->assetDetails = null;
        $this->empDetails = null;
        $this->isUpdateMode = false;
        $this->assignmentId = null;
    }

    public function mount()
    {

        $this->loadAssetsAndEmployees();
    }

    public function loadAssetsAndEmployees()
    {
        // Fetch asset and employee data, including already assigned ones
        $this->assetSelect = VendorAsset::where('is_active', 1)->get();

        $this->assetSelectEmp = EmployeeDetails::all();
        $this->assignedAssetIds = AssignAssetEmp::where('is_active', 1)->pluck('asset_id')->toArray();
        $this->assignedEmployeeIds = AssignAssetEmp::pluck('emp_id')->toArray();
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

            $this->assetDetails = VendorAsset::where('asset_id', $this->selectedAsset)->first();
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
    $this->isUpdateMode = true;

    // Fetch the assignment record
    $assignment = AssignAssetEmp::findOrFail($id);

    // Populate the component properties with the assignment data
    $this->assignmentId = $assignment->id;
    $this->selectedAsset = $assignment->asset_id;
    $this->selectedEmployee = $assignment->emp_id;
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


    public function submit()
    {
        $this->validate();

        try {
            if ($this->isUpdateMode && $this->assignmentId) {
                // Archive the current assignment
                $currentAssignment = AssignAssetEmp::findOrFail($this->assignmentId);

                if ($currentAssignment->is_active) {




                    $currentAssignment->update([
                        'is_active' => false,
                    ]);
                }

                // Create new assignment
                AssignAssetEmp::create([
                    'asset_id' => $this->selectedAsset,
                    'emp_id' => $this->empDetails->emp_id,
                    'manufacturer' => $this->assetDetails->manufacturer,
                    'asset_type' => $this->assetDetails->asset_type,
                    'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                    'department' => $this->empDetails->job_role,
                    'is_active' => true,
                ]);

                session()->flash('updateMessage', 'Assignee updated successfully!');
            } else {
                // Create new assignment
                AssignAssetEmp::create([
                    'asset_id' => $this->selectedAsset,
                    'emp_id' => $this->empDetails->emp_id,
                    'manufacturer' => $this->assetDetails->manufacturer,
                    'asset_type' => $this->assetDetails->asset_type,
                    'employee_name' => $this->empDetails->first_name . ' ' . $this->empDetails->last_name,
                    'department' => $this->empDetails->job_role,
                    'is_active' => true,
                ]);

                session()->flash('createMessage', 'Asset Assigned to employee successfully!');
            }

            $this->resetForm();
            return redirect()->route('EmployeeAssetList');
        } catch (\Exception $e) {
            Log::error('Error while assigning asset: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while saving the details. Please try again.');
        }
    }


    public function filter()
    {
        try {
            $trimmedEmpId = trim($this->searchEmp);
            $trimmedAssetId = trim($this->searchAssetId);

            $this->filteredEmployeeAssets = AssignAssetEmp::query()
            ->when($trimmedEmpId, function ($query) use ($trimmedEmpId) {
                            $query->where(function ($query) use ($trimmedEmpId) {
                                $query->where('emp_id', 'like', '%' . $trimmedEmpId . '%')
                                    ->orWhere('employee_name', 'like', '%' . $trimmedEmpId . '%'); // Adjust the column name as needed
                            });
                        })
                ->when($trimmedAssetId, function ($query) use ($trimmedAssetId) {
                    $query->where('asset_id', 'like', '%' . $trimmedAssetId . '%');
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
        $this->searchEmp = '';
        $this->searchAssetId = '';
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


            // session()->flash('message', 'Vendor deleted successfully!');
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

    public function render()
    {

        $assetTypes = asset_types_table::pluck('asset_names', 'id');

         // Use the filtered data if available, otherwise fetch all
         $employeeAssetLists = !empty($this->filteredEmployeeAssets)
         ? $this->filteredEmployeeAssets
         : AssignAssetEmp::with(['vendorAsset.vendor']) ->get();


         $employeeAssetLists = $employeeAssetLists->map(function ($employeeAssetList) use ($assetTypes) {
                $employeeAssetList['asset_type_name'] = $assetTypes[$employeeAssetList['asset_type']] ?? 'N/A';
                return $employeeAssetList; // Ensure you're returning the entire modified array

        });

        $this->loadAssetsAndEmployees();
        return view('livewire.assign-asset-employee', compact('employeeAssetLists'));
    }


}
