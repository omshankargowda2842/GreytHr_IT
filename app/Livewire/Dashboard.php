<?php

namespace App\Livewire;

use App\Models\HelpDesks;
use App\Models\IT;
use App\Models\Request;
use App\Models\Vendor;
use App\Models\VendorAsset;
use Illuminate\Support\Facades\Log; // Import the Log facade
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

        // Log the sorting order change
        Log::info("Sorting order changed to: {$this->sortOrder}");
    }

    // Sort the categories based on the current sort order
    public function sortCategories()
    {
        $this->sortedCategories = $this->sortOrder === 'asc'
            ? $this->categories->sort()
            : $this->categories->sortDesc();

        // Log the sorting action
        Log::info("Categories sorted in {$this->sortOrder} order.", ['categories' => $this->sortedCategories->toArray()]);
    }

    public function updateCounts()
    {
        $requestCategories = Request::select('Request', 'category')
            ->where('Request', 'IT') // Adjust this to match the condition for IT requests
            ->pluck('category');

        $this->activeCount = HelpDesks::where('status', 'Open')
            ->whereIn('category', $requestCategories)->count();

        $this->pendingCount = HelpDesks::where('status', 'Pending')
            ->whereIn('category', $requestCategories)->count();

        $this->closedCount = HelpDesks::where('status', 'Completed')
            ->whereIn('category', $requestCategories)->count();

        // Log data fetching actions
        Log::info('Updated counts for HelpDesks', [
            'activeCount' => $this->activeCount,
            'pendingCount' => $this->pendingCount,
            'closedCount' => $this->closedCount,
        ]);
    }

    // Redirection based on role and request type
    public function itRequest()
    {
         return redirect()->route('requests');
    }

    public function itMember()
    {
         return redirect()->route('itMembers');
    }

    public function vendorMod()
    {
         return redirect()->route('vendor');
    }

    public function assetMod()
    {
         return redirect()->route('vendorAssets');
    }

    // Adjusted role-based redirection based on ENUM values
    private function redirectBasedOnRole($routeName)
    {
        // Get the authenticated user
        $user = auth()->guard('it')->user();

        // Log the attempted redirection
        Log::info("User '{$user->it_emp_id}' attempting to access {$routeName}");

        // Determine the route based on the user's roles
        if ($user->hasRole('super_admin')) {
            $route = $routeName; // Super Admin can access any route
        } elseif ($user->hasRole('admin')) {
            $route = $this->getAdminRoute($routeName); // Map admin to specific routes
        } elseif ($user->hasRole('user')) {
            $route = $this->getUserRoute($routeName); // Map user to specific routes
        } else {
            $route = 'dashboard'; // Default route if no role matches
        }

        // Check if the route exists before redirecting
        if (Route::has($route)) {
            Log::info("Redirecting user '{$user->id}' to {$route}");
            return redirect()->route($route);
        } else {
            // Log an error if the route does not exist
            Log::warning("Route '{$route}' does not exist for user '{$user->id}'.");
            $this->dispatch('noPermissionAlert', [
                'message' => 'You don\'t have permissions to access this area.'
            ]);
        }
    }

    // Helper method to get admin-specific route
    private function getAdminRoute($routeName)
    {
        // Admin can access specific routes
        switch ($routeName) {
            case 'vendorAssets':
            case 'employeeAssetList':
                return $routeName; // Admin has access
            default:
                return null; // No access
        }
    }

    // Helper method to get user-specific route
    private function getUserRoute($routeName)
    {
        // User can only access specific routes
        switch ($routeName) {
            case 'vendor':
                return $routeName; // User has access
            default:
                return null; // No access
        }
    }


    public function render()
    {
        $categories = Request::where('Request', 'IT')
            ->select('category')
            ->distinct() // Get unique categories
            ->orderBy('category', 'asc')
            ->pluck('category')
            ->map(function ($category) {
                return trim($category); // Trim leading/trailing whitespace from categories
            });

        $this->categories = $categories;
        $this->sortCategories(); // Sort categories after fetching

        // Log the number of categories fetched
        Log::info('Fetched categories for IT requests', ['categories' => $categories->toArray()]);

        // Other data fetching for dashboard metrics
        $this->countRequests = HelpDesks::get()->map(function ($request) {
            $request->category = trim($request->category); // Clean category whitespace
            return $request;
        });

        $this->activeAssets = VendorAsset::where('is_active', 1)->count();
        $this->inactiveAssets = VendorAsset::where('is_active', 0)->count();
        $this->vendors = Vendor::where('is_active', 1)->count();
        $this->activeItRelatedEmye = IT::where('status', 1)->count();
        $this->inactiveItRelatedEmye = IT::where('status', 0)->count();

        // Log the fetched data for vendors, assets, and IT-related employees
        Log::info('Fetched dashboard metrics', [
            'activeAssets' => $this->activeAssets,
            'inactiveAssets' => $this->inactiveAssets,
            'activeVendors' => $this->vendors,
            'activeItRelatedEmye' => $this->activeItRelatedEmye,
            'inactiveItRelatedEmye' => $this->inactiveItRelatedEmye,
        ]);

        return view('livewire.dashboard');
    }
}
