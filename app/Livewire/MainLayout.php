<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Route;
use Livewire\Component;

class MainLayout extends Component
{
    public $employeeInitials;
    public $employeeName;

    public function mount()
    {
        $this->employeeName = auth()->guard('it')->user()->employee_name;

        if ($this->employeeName) {
            $this->employeeInitials = $this->getInitials($this->employeeName);
        } else {
            $this->employeeInitials = 'N/A'; // Default or placeholder initials
        }
    }

    private function getInitials($name)
    {
        $nameParts = explode(' ', $name);

        if (count($nameParts) < 2) {
            // If the name has less than 2 parts, just return the first letter of the first part
            return strtoupper(substr($nameParts[0], 0, 1));
        }

        // Extract the first letter of the first part and the last part
        $firstInitial = strtoupper(substr($nameParts[0], 0, 1));
        $lastInitial = strtoupper(substr($nameParts[count($nameParts) - 1], 0, 1));

        return $firstInitial . $lastInitial;
    }

    public function getActiveTab()
    {
        $routeName = request()->route()->getName();

        // Define base route names without repetition
        $baseRoutes = [
            'dashboard' => 'dashboard',
            'itRequest' => ['super.requests', 'admin.requests', 'user.requests'],
            'itMembers' => ['super.itMembers', 'admin.itMembers', 'user.itMembers'],
            'oldRecords' => ['super.oldItMembers', 'admin.oldItMembers', 'user.oldItMembers'],
            'vendor' => ['super.vendor', 'admin.vendor'],
            'vendorAssets' => ['super.vendorAssets', 'admin.vendorAssets'],
            'employeeAssetList' => ['user.EmployeeAssetList'],
        ];

        // Iterate through base routes to find the active tab
        foreach ($baseRoutes as $key => $routes) {
            if (in_array($routeName, $routes)) {
                return $key; // Return the key (tab) for the matched route
            }
        }

        return null; // Or return a default value if no match found
    }

    public function dashboard()
    {
        return redirect()->route('dashboard');
    }

    public function itRequest()
    {
        $this->redirectBasedOnRole('requests');
    }

    public function itMembers()
    {
        $this->redirectBasedOnRole('itMembers');
    }

    public function oldRecords()
    {
        $this->redirectBasedOnRole('oldItMembers');
    }

    public function vendor()
    {
        $this->redirectBasedOnRole('vendor');
    }

    public function vendorAssets()
    {
        $this->redirectBasedOnRole('vendorAssets');
    }

    public function employeeAssetList()
    {
        $this->redirectBasedOnRole('EmployeeAssetList');
    }

    public function assignAsset()
    {
        $this->redirectBasedOnRole('EmployeeAssetList');
    }

    private function redirectBasedOnRole($routeName)
    {
        // Get user role
        $role = auth()->guard('it')->user()->role; // Adjust this if necessary

        // Map roles to specific route names
        $roleRoutes = [
            2 => "super.$routeName", // Super Admin
            1 => "admin.$routeName",  // Admin
            0 => "user.$routeName",    // User
        ];

        // Get the route name for the user's role
        $route = $roleRoutes[$role] ?? 'dashboard'; // Default to dashboard
        // Check if the route exists before redirecting
        if (Route::has($route)) {
            return redirect()->route($route);
        } else {
            // Fallback if the route does not exist
            return redirect()->route('dashboard')->with('error', 'You dont have permissions to access.');
        }
    }

    public function render()
    {
        return view('livewire.main-layout');
    }
}
