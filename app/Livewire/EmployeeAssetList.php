<?php

namespace App\Livewire;

use App\Models\AssignAssetEmp;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class EmployeeAssetList extends Component
{
    public $searchEmpId = '';
    public $searchAssetId = '';
    public $filteredEmployeeAssets = [];
    public $assetsFound = false;
    public $showEditDeleteEmployeeAsset = true;

    public $showViewEmployeeAsset = false;
public $currentVendorId = null;

public function viewDetails($employeeAssetList)
{
    $this->currentVendorId = $employeeAssetList;
    $this->showViewEmployeeAsset = true;
    $this->showEditDeleteEmployeeAsset = false;
    // $this->editMode = false;
}

public function closeViewVendor()
{
    $this->showViewEmployeeAsset = false;
    $this->showEditDeleteEmployeeAsset = true;
    $this->currentVendorId = null;
}

    public function render()
    {

        // Use the filtered data if available, otherwise fetch all
        $employeeAssetLists = !empty($this->filteredEmployeeAssets)
            ? $this->filteredEmployeeAssets
            : AssignAssetEmp::with(['vendorAsset.vendor'])->get();

        return view('livewire.employee-asset-list', compact('employeeAssetLists'));
    }

    public function filter()
    {
        try {
            $trimmedEmpId = trim($this->searchEmpId);
            $trimmedAssetId = trim($this->searchAssetId);

            $this->filteredEmployeeAssets = AssignAssetEmp::query()
                ->when($trimmedEmpId, function ($query) use ($trimmedEmpId) {
                    $query->where('emp_id', 'like', '%' . $trimmedEmpId . '%');
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
        $this->searchEmpId = '';
        $this->searchAssetId = '';
        $this->filteredEmployeeAssets = [];
        $this->assetsFound = false;

    }
}
