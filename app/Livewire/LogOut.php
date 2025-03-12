<?php
#/ livewire
namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LogOut extends Component
{

    public $showLogoutModal = false;

    public function handleLogout()
    {
        $this->showLogoutModal = true;
    }
    public function confirmLogout()
    {

        try {
            Auth::logout();
            session()->invalidate();
            // session()->regenerateToken();
            session()->flush(); // Clear session data
            FlashMessageHelper::flashSuccess("You are logged out successfully!");
            // Flash success message
            return redirect()->route('itlogin');
        } catch (\Exception $exception) {
            // Handle exceptions
            session()->flash('error', "An error occurred while logging out.");
            return redirect()->back(); // Redirect back with an error message
        }
    }
    public function cancelLogout()
    {
        $this->showLogoutModal = false;
    }

    public function render()
    {
        return view('livewire.logout');
    }
}
