<?php

namespace App\Livewire;

use App\Models\asset_types_table;
use App\Models\VendorAsset;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class AssetsList extends Component
{

    public $searchEmp = '';
    public $assetDetails;
    public $totalAssetDetails;
    public $totalAsset;
    public $sortColumn = 'asset_id'; // default sorting column
    public $sortDirection = 'asc'; // default sorting direction
    public $asset_types;
    public $selectedAsset_type='';
    public $selectedStatus='';
    public $perPage = 10; // Number of records per page
    public $currentPage = 1;
    public $all_Assets;

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
        $this->getAssetList();
    }

    public function mount()
    {
        $this->asset_types = asset_types_table::all();
        $this->getAssetList();
    }

    public function getAssetList()
    {
        try {
            // dd('ok');

            // Initialize the query
            $query = VendorAsset::join('vendors', 'vendor_assets.vendor_id', '=', 'vendors.vendor_id') // Join with the vendor table
                ->join('asset_types_tables', 'vendor_assets.asset_type', '=', 'asset_types_tables.id')
                ->whereIn('vendors.is_active', ['0', '1']); // Filter by active vendors


                $this->all_Assets=$query->count();
            // Apply search filter
            $trimmedEmpId = trim($this->searchEmp);
            if (!empty($trimmedEmpId)) {
                $query->where(function ($query) use ($trimmedEmpId) {
                    $query->where('vendor_assets.asset_id', 'like', '%' . $trimmedEmpId . '%')
                        ->orWhere('vendor_assets.manufacturer', 'like', '%' . $trimmedEmpId . '%')
                        ->orWhere('vendor_assets.asset_type', 'like', '%' . $trimmedEmpId . '%')
                        ->orWhere('vendor_assets.invoice_number', 'like', '%' . $trimmedEmpId . '%')
                        ->orWhere('vendor_assets.serial_number', 'like', '%' . $trimmedEmpId . '%');
                });
                $this->currentPage = 1;
            }
            if (!empty($this->selectedAsset_type)) {
                $query->where('vendor_assets.asset_type', '=', $this->selectedAsset_type);
                $this->currentPage = 1;
            }

            // Apply status filter if selected
            if ($this->selectedStatus) {
                if ($this->selectedStatus == 'null') {
                    // Check for null status if 'N/A' is selected
                    $query->whereNull('vendor_assets.status');
                    $this->currentPage = 1;
                } else {
                    // Apply filter based on selected status
                    $query->where('vendor_assets.status', $this->selectedStatus);
                    $this->currentPage = 1;
                }
            }

            // Ordering and fetching results
            $this->totalAsset =$query->count();
           $this->totalAssetDetails= $query->orderBy($this->sortColumn, $this->sortDirection)->get()->toArray();


            // Logging the data for debugging purposes (can be commented out in production)
            // dd($this->assetDetails);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error fetching asset details: ' . $e->getMessage());

            $this->assetDetails = []; // Set assetDetails to an empty array
        }
    }



//     public function showViewVendor($asset_id) {
// dd($asset_id);
//     }
    public function setPage($page)
    {
        $this->currentPage = max(1, min($page, ceil($this->totalAsset/ $this->perPage)));
        $this->getPaginatedAssets();
    }
    public function getPaginatedAssets()
    {
        return array_slice($this->totalAssetDetails, ($this->currentPage - 1) * $this->perPage, $this->perPage);
    }

    public function render()
    {
        $this->assetDetails=$this->getPaginatedAssets();
        // dd(  $this->assetDetails);


        return view('livewire.assets-list',[
            'totalPages' => ceil($this->totalAsset / $this->perPage),
        ]
            );
    }
}
