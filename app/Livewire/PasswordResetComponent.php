<?php

namespace App\Livewire;

use App\Models\IT;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PasswordResetComponent extends Component
{
    public $token;
    public $newPassword; // New password
    public $confirmNewPassword; // Confirm new password
    public $isValidToken = true; // To track token validity

    // Define validation rules
    protected $rules = [
        'newPassword' => [
            'required',
            'string',
            'min:8',
            'regex:/[A-Z]/',
            'regex:/[a-z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
        ],
        'confirmNewPassword' => 'required|same:newPassword',
    ];

    public function mount($token)
    {
        session()->forget('error');
        session()->forget('success');

        $this->token = $token;

        // Check if the token is valid
        $tokenData = DB::table('password_reset_tokens')->where('token', $this->token)->first();

        // If the token is not valid, set an error message and mark the token as invalid
        if (!$tokenData) {
            $this->isValidToken = false;
            session()->flash('error', 'The password reset link is invalid or has already been used. Click on link return to the login page.');
        }
    }

    public function resetPassword()
    {
        $this->validate();

        // Check if the token is valid
        $tokenData = DB::table('password_reset_tokens')->where('token', $this->token)->first();
        if (!$tokenData) {
            session()->flash('error', 'Invalid token or email.');
            return;
        }

        // Proceed with password reset
        $user = IT::where('email', $tokenData->email)->first();
        if ($user) {
            $user->password = bcrypt($this->newPassword);
            $user->save();

            // Optionally, delete the token
            DB::table('password_reset_tokens')->where('email', $tokenData->email)->delete();

            // Clear fields after reset
            $this->newPassword = '';
            $this->confirmNewPassword = '';

            session()->flash('success', 'Password reset successfully. Click below to log in.');
        } else {
            session()->flash('error', 'User not found.');
        }
    }

    public function render()
    {
        return view('livewire.password-reset-component');
    }
}