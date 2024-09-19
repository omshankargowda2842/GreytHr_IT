<?php

use App\Livewire\AssignAssetEmployee;
use App\Livewire\Dashboard;
use App\Livewire\EmployeeAssetList;
use App\Livewire\ItAddMember;
use App\Livewire\ItLogin;
use App\Livewire\ItMembers;
use App\Livewire\OldItMembers;
use App\Livewire\RequestProcess;
use App\Livewire\TestPurpose;
use App\Livewire\VendorAssets;
use App\Livewire\Vendors;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['checkauth'])->group(function () {
    Route::get('/itlogin', ItLogin::class)->name('itlogin');
});

Route::middleware(['auth:it'])->group(function () {
    // Root route, protected by auth:hr middleware
    Route::get('/', Dashboard::class)->name('dashboard');
    // Group routes under the 'hr' prefix
    Route::prefix('it')->group(function () {
        //like this  Route: /hr/hello
                Route::get('/hello', Dashboard::class)->name('hello');
                Route::get('/itrequest',RequestProcess::class)->name('requests');
                Route::get('/itMembers',ItAddMember::class)->name('itMembers');
                Route::get('/test',TestPurpose::class)->name('test');
                Route::get('/oldItMembers',OldItMembers::class)->name('oldItMembers');
                Route::get('/vendor',Vendors::class)->name('vendor');
                Route::get('/vendorAssets',VendorAssets::class)->name('vendorAssets');
                Route::get('/EmployeeAssetList',AssignAssetEmployee::class)->name('EmployeeAssetList');
                // Route::get('/EmployeeAssetList',EmployeeAssetList::class)->name('EmployeeAssetList');

    });
});
