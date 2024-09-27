<?php

namespace App\Livewire;

use App\Models\HelpDesks;
use App\Models\IT;
use App\Models\Request;
use App\Models\Vendor;
use App\Models\VendorAsset;
use Livewire\Component;

class Dashboard extends Component
{
    public $activeCount;
    public $categories;
    public $countRequests;
    public $helpDeskCategories;
    public $matchingCount;
    public $pendingCount;
    public $closedCount;
    public $activeItRelatedEmye = [];
    public $inactiveItRelatedEmye = [];
    public $vendors = [];
    public $activeAssets = [];
    public $inactiveAssets = [];
    public $sortedCategories; // Sorted categories
    public $sortOrder = 'asc'; // Default sort order is ascending

    public function mount()
    {
        $this->updateCounts();

    }

    // Toggle sorting order (asc/desc)
    public function toggleSortOrder()
    {
        $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        $this->sortCategories(); // Re-sort categories when toggling
    }

    // Sort the categories based on the current sort order
    public function sortCategories()
    {
        if ($this->sortOrder === 'asc') {
            $this->sortedCategories = $this->categories->sort();
        } else {
            $this->sortedCategories = $this->categories->sortDesc();
        }
    }


    public function updateCounts()
    {
     $requestCategories = Request::select('Request', 'category')
    ->where('Request', 'IT') // Adjust this to match the condition for IT requests
    ->pluck('category');

    $this->activeCount = HelpDesks::where('status', 'Open')
    ->whereIn('category',  $requestCategories)->count();


    $this->pendingCount = HelpDesks::where('status', 'Pending')
    ->whereIn('category',  $requestCategories)->count();

    $this->closedCount = HelpDesks::where('status', 'Completed')
    ->whereIn('category',  $requestCategories)->count();

    }

    public function itRequest(){

    return redirect()->route('requests');
    }

    public function itMemeber(){

        return redirect()->route('itMembers');
    }

    public function vendorMod(){

        return redirect()->route('vendor');
    }

    public function assetMod(){

        return redirect()->route('vendorAssets');
    }

    public function render()
    {
        $categories = Request::where('Request', 'IT')
        ->select('category')
        ->distinct() // Get unique categories
        ->orderBy('category', 'asc')
        ->pluck('category') // Get the categories as a collection
        ->map(function($category) {
           return trim($category); // Trim leading/trailing whitespace from categories
       });
        $this->categories = $categories;
        $this->sortCategories();

        $this->countRequests = HelpDesks::get()->map(function($request) {
        $request->category = trim($request->category); // Trim leading/trailing whitespace from the category
        return $request;
        });
        $this->activeAssets =VendorAsset::where('is_active', 1)->count();
        $this->inactiveAssets =VendorAsset::where('is_active', 0)->count();
        $this->vendors = Vendor::where('is_active', 1)->count();
        $this->activeItRelatedEmye = IT::where('is_active', 1)->count();
        $this->inactiveItRelatedEmye = IT::where('is_active', 0)->count();

        return view('livewire.dashboard');
    }
}
