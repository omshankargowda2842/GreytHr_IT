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


    public function mount(){
        $this->updateCounts();
        $categories = Request::where('Request', 'IT')
                     ->select('category')
                     ->distinct() // Get unique categories
                     ->pluck('category'); // Get the categories as a collection

    $this->categories = $categories;

    $this->countRequests = HelpDesks::get() ;

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

    public function render()
    {
        $this->activeAssets =VendorAsset::where('is_active', 1)->count();
        $this->inactiveAssets =VendorAsset::where('is_active', 0)->count();
        $this->vendors = Vendor::where('is_active', 1)->count();
        $this->activeItRelatedEmye = IT::where('is_active', 1)->count();
        $this->inactiveItRelatedEmye = IT::where('is_active', 0)->count();

        return view('livewire.dashboard');
    }
}
