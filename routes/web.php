<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\AssetsList;
use App\Livewire\AssignAssetEmployee;
use App\Livewire\Dashboard;
use App\Livewire\EmployeeAssetList;
use App\Livewire\IncidentRequest;
use App\Livewire\IncidentRequests;
use App\Livewire\ItAddMember;
use App\Livewire\ItLogin;
use App\Livewire\ItMembers;
use App\Livewire\OldItMembers;
use App\Livewire\PasswordResetComponent;
use App\Livewire\RequestProcess;
use App\Livewire\ServiceRequests;
use App\Livewire\TestPurpose;
use App\Livewire\VendorAssets;
use App\Livewire\Vendors;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['guest'])->group(function () {
    Route::get('/itlogin', ItLogin::class)->name('itlogin');
});

Route::middleware(['auth', 'auth.session'])->group(function () {
    // Root route, protected by auth:it middleware
    Route::get('/', Dashboard::class)->name('dashboard');

    // Group routes under the 'it' prefix


        // Super Admin Routes (accessible only to super_admin)
        Route::middleware(['role:super_admin'])->group(function () {
            Route::get('/itMembers', ItAddMember::class)->name('itMembers');
        });

        // Admin Routes (accessible to both admin and super_admin)
        Route::middleware(['role:admin|super_admin'])->group(function () {
            Route::get('/oldItMembers', OldItMembers::class)->name('oldItMembers');
            Route::get('/vendorAssets', VendorAssets::class)->name('vendorAssets');
            Route::get('/assetsList', AssetsList::class)->name('assetsList');
            Route::get('/employeeAssetList', AssignAssetEmployee::class)->name('employeeAssetList');
            Route::get('/vendors', Vendors::class)->name('vendors');
            Route::get('/itMembers', ItAddMember::class)->name('itMembers');
            Route::get('incidentRequests/{id?}', IncidentRequests::class)->name('incidentRequests');
            Route::get('serviceRequests/{id?}', ServiceRequests::class)->name('serviceRequests');
        });

        // User Routes (accessible to all roles: user, admin, and super_admin)
        Route::middleware(['role:user|admin|super_admin'])->group(function () {
            Route::get('/vendors', Vendors::class)->name('vendors');
            Route::get('/itrequest/{id?}', RequestProcess::class)->name('requests');
        });

});

Route::get('password/reset/{token}', PasswordResetComponent::class)->name('password.reset');

Route::get('/clear', function () {
    // Clear the contents of all log files
    $logFiles = File::glob(storage_path('logs/*.log'));
    foreach ($logFiles as $file) {
        File::put($file, ''); // This will empty the file without deleting it
    }

    // Perform other Artisan commands
    Artisan::call('optimize:clear');
    Artisan::call('optimize');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('auth:clear-resets');

    return 'Log contents cleared, and caches have been cleared and optimized!';
});
Route::get('/Privacy&Policy', function () {
    return view('privacy_poliy_view');
});

Route::get('/Terms&Services', function () {
    return view('terms_services_view');
});