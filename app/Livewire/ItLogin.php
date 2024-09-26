<?php

// File Name                       : EmpLogin.php
// Description                     : This file contains the implementation multi guard login
// Creator                         : Saragada Siva Kumar
// Email                           :
// Organization                    : PayG.
// Date                            : 2024-03-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : EmployeeDetails,Hr,Finance,Admin,IT

namespace App\Livewire;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\IT;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Mail\PasswordChanged;
use Illuminate\Support\Facades\Mail;

class ItLogin extends Component
{
    public $showDialog = false;
    public $email;
    public $company_email;
    public $dob;
    public $newPassword;
    public $newPassword_confirmation;
    public $verified = false;
    public $showSuccessModal = false;
    public $showErrorModal = false;
    public $showLoader = false;
    public $passwordChangedModal = false;
    public $form = [
        'emp_id' => '',
        'password' => '',
    ];
    public $error = '';
    public $verify_error = '';
    public $pass_change_error = '';
    protected $messages = [
        'form.emp_id.required' => 'ID / Mail is required.',
        'form.password.required' => 'Password is required.',
        'newPassword.required' => 'New Password is required.',
        'newPassword.min' => 'New Password should be at least 8 characters.',
        'newPassword.max' => 'New Password should not exceed 50 characters.',
        'newPassword.regex' => 'New Password must contain at least one uppercase letter and one special character, and one digit.',
        'newPassword_confirmation.required' => 'Confirm Password is required.',
        'newPassword_confirmation.same' => 'New Password and Confirm Password should be same.',
        'email.required_without' => 'Email is required.',
        'dob.required' => 'Date of Birth is required.',
        'email.email' => 'Please enter a valid email.',
    ];
    protected $forgotPasswordRules =  [
        'email' => ['nullable', 'email', 'required_without:company_email'],
        'company_email' => ['nullable', 'email', 'required_without:email'],
        'dob' => ['required', 'date'],
    ];
    protected $passwordRules = [
        'newPassword' => [
            'required',
            'min:8',
            'max:50',
            'regex:/[A-Z]/',
            'regex:/[!@#$%^&*(),.?":{}|<>]/',
            'regex:/[0-9]/'
        ],
        'newPassword_confirmation' => 'required|same:newPassword',
    ];

    public function jobs()
    {
        return redirect()->to('/Login&Register');
    }
    public function createCV()
    {
        return redirect()->to('/CreateCV');
    }

    public function resetValidationForField($field)
    {
        // Reset error for the specific field when typing
        $this->resetErrorBag($field);
    }

    public function itLogin()
    {
        // Manually trim the input values
        $this->form['emp_id'] = trim($this->form['emp_id']);
        $this->form['password'] = trim($this->form['password']);

        $this->validate([
            "form.emp_id" => 'required',
            "form.password" => "required"
        ]);

        try {
            // $this->showLoader = true;

            if (Auth::guard('it')->attempt(['it_emp_id' => $this->form['emp_id'] , 'password' => $this->form['password']])) {
                return redirect()->route('dashboard');
            } elseif (Auth::guard('it')->attempt(['email' => $this->form['emp_id'], 'password' => $this->form['password']])) {
                return redirect()->route('dashboard');
            }  else {
                $this->error = "Invalid ID or Password. Please try again.";
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            $this->showLoader = false; // Hide loader if validation fails
            $this->error = "There was a problem with your input. Please check and try again.";
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors

            $this->showLoader = false;
            $this->error = "We are experiencing technical difficulties. Please try again later.";
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            // Handle server errors
            $this->showLoader = false;
            $this->error = "There is a server error. Please try again later.";
        } catch (\Exception $e) {
            // Handle general errors
            $this->showLoader = false;
            $this->error = "An unexpected error occurred. Please try again.";
        }
    }

    public function resetForm()
    {
        $this->email = '';
        $this->dob = '';
        $this->newPassword = '';
        $this->newPassword_confirmation = '';
        $this->verified = false;
        $this->resetValidation();
    }

    public function show()
    {
        $this->resetForm();
        $this->resetValidation();
        $this->showDialog = true;
    }
    public function remove()
    {
        $this->resetForm();
        $this->showDialog = false;
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
    }
    public function closeErrorModal()
    {
        $this->showErrorModal = false;
    }
    public function closePasswordChangedModal()
    {
        $this->passwordChangedModal = false;
    }
    public function verifyEmailAndDOB()
    {
        $this->validate([
            'email' => ['nullable', 'email', 'required_without:company_email'],
            'company_email' => ['nullable', 'email', 'required_without:email'],
            'dob' => ['required', 'date'],
        ]);
        try {
            // Custom validation rule to ensure either email or company_email is present


            // Determine which email field is used
            $email = $this->email ?? $this->company_email;

            if (!$email) {
                throw new \Exception('Either email or company email must be provided.');
            }

            // Implement your logic to verify email and DOB here.
            // Example: Check if the email and DOB match a user's stored values in your database.
            // $user = EmployeeDetails::where(function ($query) use ($email) {
            //     $query->where('email', $email)
            //         ->orWhere('company_email', $email);
            // })->where('dob', $this->dob)->first();

            // Search for the user in HR table
            $userInIT = IT::where(function ($query) use ($email) {
                $query->where('email', $email)
                    ->orWhere('company_email', $email);
            })->where('date_of_birth', $this->dob)->first();


            // Combine the results of all queries
            $user =  $userInIT;
            if ($user) {
                $this->verified = true;
                if ($this->verified) {
                    $this->verified = false;
                    $this->showSuccessModal = true;
                }
            } else {
                // Invalid email or DOB, show an error message or handle accordingly.
                $this->addError('email', 'Invalid email or date of birth');
                $this->showErrorModal = true;
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            //$this->showErrorModal = true;
            // $this->addError('email', 'There was a problem with your input. Please check and try again.');
            $this->verify_error = "There was a problem with your input. Please check and try again.";
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            //$this->showErrorModal = true;
            // $this->addError('email', 'We are experiencing technical difficulties. Please try again later.');
            Log::error('Error approving leave: ' . $e->getMessage());
            $this->verify_error = 'We are experiencing technical difficulties. Please try again later.';
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            // Handle server errors
            // $this->showErrorModal = true;
            // $this->addError('email', 'There is a server error. Please try again later.');
            $this->verify_error = 'There is a server error. Please try again later.';
        } catch (\Exception $e) {
            // Handle general errors
            // $this->showErrorModal = true;
            // $this->addError('email', 'An unexpected error occurred. Please try again.');
            $this->verify_error = 'An unexpected error occurred. Please try again.';
        }
    }


    public function showPasswordChangeModal()
    {
        $this->verified = true;
        $this->showSuccessModal = false;
    }
    public function createNewPassword()
    {
        $this->validate([
            'newPassword' => ['required', 'min:8', 'max:50',],
            'newPassword_confirmation' => ['required', 'same:newPassword'],
        ]);

        try {
            // Validate the new password and its confirmation

            // Determine which email field is used
            $email = $this->email ?? $this->company_email;

            if (!$email) {
                throw new \Exception('Either email or company email must be provided.');
            }

            // Check if the passwords match
            if ($this->newPassword === $this->newPassword_confirmation) {
                // Find the user by either email or company email
                // $user = EmployeeDetails::where(function ($query) use ($email) {
                //     $query->where('email', $email)
                //           ->orWhere('company_email', $email);
                // })->first();

                // Search for the user in HR table
                $userInIT = IT::where(function ($query) use ($email) {
                    $query->where('email', $email)
                        ->orWhere('company_email', $email);
                })->first();


                // Combine the results of all queries
                $user = $userInIT ;


                if ($user) {

                    // Update the user's password in the database
                    $user->update(['password' => bcrypt($this->newPassword)]);
                    $this->passwordChangedModal = true;

                    // Reset form fields and state after successful password update
                    $this->reset(['newPassword', 'newPassword_confirmation', 'verified']);
                    //$this->passwordChangedModal = false;
                    $this->showDialog = false;
                } else {
                    // User not found, show an error message
                    $this->addError('newPassword', 'User not found.');
                    $this->passwordChangedModal = false;
                }
            } else {
                // Passwords do not match, show an error message
                $this->addError('newPassword', 'Passwords do not match.');
                $this->passwordChangedModal = false;
            }
        } catch (ValidationException $e) {
            // Handle validation errors
            // $this->passwordChangedModal = false;
            Log::error('Error approving leave: ' . $e->getMessage());
            $this->pass_change_error = 'There was a problem with your input. Please check and try again.';
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            // $this->passwordChangedModal = false;
            Log::error('Error approving leave: ' . $e->getMessage());
            $this->pass_change_error = 'We are experiencing technical difficulties. Please try again later.';
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            // Handle server errors
            // $this->passwordChangedModal = false;
            Log::error('Error approving leave: ' . $e->getMessage());
            $this->pass_change_error = 'There is a server error. Please try again later.';
        } catch (\Exception $e) {
            // Handle general errors
            //$this->passwordChangedModal = false;
            Log::error('Error approving leave: ' . $e->getMessage());
            $this->pass_change_error = 'An unexpected error occurred. Please try again.';
        }
    }

    public function validateField($field)
    {
        if (in_array($field, ['email', 'company_email', 'dob'])) {
            $this->validateOnly($field, $this->forgotPasswordRules);
        } elseif (in_array($field, ['newPassword', 'newPassword_confirmation'])) {
            $this->validateOnly($field, $this->passwordRules);
        } else {
            $this->validateOnly($field, $this->rules);
        }
    }
    public function forgotPassword()
    {
        $this->verify_error = '';
    }

    public function render()
    {
        return view('livewire.it-login');
    }
}
