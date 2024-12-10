<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-5">
        <div class="row m-0 text-center">
            <img class="m-auto mb-4" src="{{ asset('images/it_xpert_logo.png') }}" alt="Company Logo" style="width: 14em;">
        </div>

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="alert alert-success d-flex justify-content-between align-items-center">
                <span class="w-100 text-center">
                    {{ session('success') }}
                    <a href="{{ route('itlogin') }}" class="btn btn-link">Login</a>
                </span>
            </div>
        @endif

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="alert alert-danger d-flex justify-content-between align-items-center">
                <span class="w-100 text-center" style="white-space: normal;">
                    {{ session('error') }}
                    <a href="{{ route('itlogin') }}" class="btn btn-link">Login</a>
                </span>
            </div>
        @endif

        <!-- Conditionally render the form if passwordResetSuccessful is false -->
        @if ($isValidToken && !$passwordResetSuccessful)
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header text-center text-white" style="background-color: #02114f">
                    <h5>{{ __('Reset Password') }}</h5>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="resetPassword">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- New Password Input -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" id="password" wire:model.lazy="newPassword"
                                wire:keydown.debounce.500ms="validateField('newPassword')"
                                class="form-control @error('newPassword') is-invalid @enderror"
                                placeholder="Enter your new password" aria-label="New password">
                            @error('newPassword')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" id="password_confirmation" wire:model.lazy="confirmNewPassword"
                                wire:keydown.debounce.500ms="validateField('confirmNewPassword')"
                                class="form-control @error('confirmNewPassword') is-invalid @enderror"
                                placeholder="Confirm your new password" aria-label="Confirm password">
                            @error('confirmNewPassword')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>


                        <!-- Submit Button -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-sm text-center text-white"
                                wire:loading.attr="disabled" style="background-color: #02114f">
                                <span wire:loading.remove>
                                    {{ __('Reset Password') }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    {{ __(' Resetting...') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
