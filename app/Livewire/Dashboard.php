<?php

namespace App\Livewire;

use App\Models\HelpDesks;
use App\Models\IT;
use App\Models\Request;
use App\Models\Vendor;
use App\Models\VendorAsset;
use Illuminate\Support\Facades\Route;
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

    public function itRequest()
    {

        $this->redirectBasedOnRole('requests');
    }

    public function itMemeber()
    {

        $this->redirectBasedOnRole('itMembers');
    }

    public function vendorMod()
    {

        $this->redirectBasedOnRole('vendor');
    }

    public function assetMod()
    {

        $this->redirectBasedOnRole('vendorAssets');
    }

    private function redirectBasedOnRole($routeName)
    {
        // Get user role
        $role = auth()->guard('it')->user()->role; // Adjust this if necessary

        // Map roles to specific route names
        $roleRoutes = [
            2 => "super.$routeName", // Super Admin
            1 => "admin.$routeName",  // Admin
            0 => "user.$routeName",   // User
        ];

        // Get the route name for the user's role
        $route = $roleRoutes[$role] ?? 'dashboard'; // Default to dashboard
        // Check if the route exists before redirecting
        if (Route::has($route)) {
            return redirect()->route($route);
        } else {
            $this->dispatch('noPermissionAlert', ['message' => 'You don\'t have permissions to access this area.']);
            // return redirect()->route('dashboard');
        }
    }
    public function render()
    {
        $categories = Request::where('Request', 'IT')
            ->select('category')
            ->distinct() // Get unique categories
            ->orderBy('category', 'asc')
            ->pluck('category') // Get the categories as a collection
            ->map(function ($category) {
                return trim($category); // Trim leading/trailing whitespace from categories
            });
        $this->categories = $categories;
        $this->sortCategories();

        $this->countRequests = HelpDesks::get()->map(function ($request) {
            $request->category = trim($request->category); // Trim leading/trailing whitespace from the category
            return $request;
        });
        $this->activeAssets = VendorAsset::where('is_active', 1)->count();
        $this->inactiveAssets = VendorAsset::where('is_active', 0)->count();
        $this->vendors = Vendor::where('is_active', 1)->count();
        $this->activeItRelatedEmye = IT::where('status', 1)->count();
        $this->inactiveItRelatedEmye = IT::where('status', 0)->count();

        return view('livewire.dashboard');
    }
}
