<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\Company;
use App\Models\EmployeeDetails;
use App\Models\IT;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PasswordResetComponent extends Component
{
    public $token;
    public $newPassword;
    public $confirmNewPassword;
    public $isValidToken = true;
    public $passwordResetSuccessful = false; // Add this flag to track success
    public $companyName;
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

        $tokenData = DB::table('password_reset_tokens')->where('token', $this->token)->first();

        if (!$tokenData) {
            $this->isValidToken = false;
            session()->flash('error', 'The password reset link is invalid or has already been used.');
            // FlashMessageHelper::flashError('The password reset link is invalid or has already been used.');
        }
    }

    public function validateField($field)
    {
        if (in_array($field, ['newPassword', 'confirmNewPassword'])) {
            $this->validateOnly($field, $this->rules);
        } else {
            $this->validateOnly($field, $this->rules);
        }
    }

    public function resetPassword()
    {
        $this->validate();

        $tokenData = DB::table('password_reset_tokens')->where('token', $this->token)->first();
        if (!$tokenData) {
            FlashMessageHelper::flashError('Invalid token or email.');
            return;
        }

        $user = IT::where('email', $tokenData->email)->first();
        if ($user) {
            $empId = $user->emp_id;
            $employee = EmployeeDetails::where('emp_id', $empId)->first();
            $companyId = $employee->company_id;
            // Fetch the company details using company_id
            $company = Company::where('company_id', $companyId)->first();
            $this->companyName = $company->company_name;
            $user->password = bcrypt($this->newPassword);
            $user->save();
            // // Send password change notification
            if ($user && !empty($user->email)) {
                $user->notify(new \App\Notifications\PasswordChangedNotification($this->companyName));
            }
            DB::table('password_reset_tokens')->where('email', $tokenData->email)->delete();

            $this->newPassword = '';
            $this->confirmNewPassword = '';

            session()->flash('success', 'Password reset successfully. Click below to log in.');
            FlashMessageHelper::flashSuccess('Password reset successfully.');

            // Set the passwordResetSuccessful flag to true
            $this->passwordResetSuccessful = true;
        } else {
            FlashMessageHelper::flashError('User not found.');
        }
    }

    public function render()
    {
        return view('livewire.password-reset-component');
    }
}
