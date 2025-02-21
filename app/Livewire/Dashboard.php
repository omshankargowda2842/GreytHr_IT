<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\HelpDesks;
use App\Models\IncidentRequest;
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
    public $newCount;
    public $activeServiceCount;
    public $activeIncidentCount;
    public $categories;
    public $countRequests;
    public $helpDeskCategories;
    public $matchingCount;
    public $pendingCount;
    public $closedCount;
    public $inprogressCount;
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
        try {
            // Toggle the sort order between 'asc' and 'desc'
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';

            // Re-sort categories when toggling
            $this->sortCategories();
            Log::info("Sorting order changed to: {$this->sortOrder}");

        } catch (\Exception $e) {
            Log::error("Error toggling sort order: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            FlashMessageHelper::flashError('An error occurred while toggling the sort order.');
            $this->sortOrder = 'asc';
        }
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
        try {
            // Fetch the categories for IT requests
            $requestCategories = Request::select('Request', 'category')
                ->where('Request', 'IT') // Adjust this to match the condition for IT requests
                ->pluck('category');

            // Fetch counts based on categories for various statuses in HelpDesks

            $this->newCount = HelpDesks::where('status_code', '8')
            ->whereIn('category', $requestCategories)->count();


            $this->activeCount = HelpDesks::where('status_code', '10')
                ->whereIn('category', $requestCategories)->count();

            $this->pendingCount = HelpDesks::where('status_code', '5')
                ->whereIn('category', $requestCategories)->count();

                $this->inprogressCount = HelpDesks::where('status_code', '16')
                ->whereIn('category', $requestCategories)->count();


            $this->closedCount = HelpDesks::where('status_code', ['11','15'])
                ->whereIn('category', $requestCategories)->count();

            // Log data fetching actions
            Log::info('Updated counts for HelpDesks', [
                'activeCount' => $this->activeCount,
                'inprogressCount' => $this->inprogressCount,
                'pendingCount' => $this->pendingCount,
                'closedCount' => $this->closedCount,
            ]);

        } catch (\Exception $e) {
            // Handle any exceptions that occur during data fetching
            Log::error('Error updating HelpDesks counts: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Optionally, flash an error message to the user
            FlashMessageHelper::flashError('An error occurred while updating HelpDesks counts.');

            // Optionally, you could reset the counts to zero or some default value
            $this->activeCount = 0;
            $this->inprogressCount = 0;
            $this->pendingCount = 0;
            $this->closedCount = 0;
        }
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

    public function incidentRequest()
    {
         return redirect()->route('incidentRequests');
    }

    public function serviceRequest()
    {
         return redirect()->route('serviceRequests');
    }

    public function vendorMod()
    {
         return redirect()->route('vendors');
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
    try {
        // Fetch distinct categories for IT requests and order them
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

        // Fetch dashboard metrics for requests
        $this->countRequests = HelpDesks::get()->map(function ($request) {
            $request->category = trim($request->category); // Clean category whitespace
            return $request;
        });

        $this->activeIncidentCount = IncidentRequest::with('emp')
        ->orderBy('created_at', 'desc')
        ->where('category', 'Incident Request')
        ->where('status_code', 10)
        ->count();

        $this->activeServiceCount = IncidentRequest::with('emp')
            ->orderBy('created_at', 'desc')
            ->where('category', 'Service Request')
            ->where('status_code', 10)
            ->count();

        // Fetch asset and vendor metrics
        $this->activeAssets = VendorAsset::where('is_active', 1)->count();
        $this->inactiveAssets = VendorAsset::where('is_active', 0)->count();
        $this->vendors = Vendor::where('is_active', 1)->count();

        // Fetch IT-related employee metrics
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

    } catch (\Exception $e) {
        // Handle any errors that occur during the data fetching process
        // Log the error message
        Log::error('Error in rendering dashboard: ' . $e->getMessage(), [
            'exception' => $e,
        ]);

        // Optionally, flash an error message to the user or handle the error gracefully
        FlashMessageHelper::flashError('An error occurred while fetching dashboard data.');

        // Return a fallback view or empty data in case of error
        return view('livewire.dashboard', [
            'categories' => collect(), // Provide empty collection if categories fetching fails
            'countRequests' => collect(), // Provide empty collection for requests
            'activeAssets' => 0, // Provide fallback values for other metrics
            'inactiveAssets' => 0,
            'vendors' => 0,
            'activeItRelatedEmye' => 0,
            'inactiveItRelatedEmye' => 0,
        ]);
    }
}

}
