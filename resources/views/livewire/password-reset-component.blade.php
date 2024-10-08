<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-5"> <!-- Increased width for better fit -->
        @if (session()->has('success'))
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            <span class="w-100 text-center">{{ session('success') }} <a href="{{ route('itlogin') }}" class="btn btn-link">Login</a></span>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="alert alert-danger d-flex justify-content-between align-items-center">
            <span class="w-100 text-center" style="white-space: normal;">{{ session('error') }} <a href="{{ route('itlogin') }}" class="btn btn-link">Login</a></span> <!-- Centered the error message -->
        </div>
        @endif

        @if ($isValidToken)
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header text-center bg-primary text-white">
                <h5>{{ __('Reset Password') }}</h5>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="resetPassword">
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input type="password" id="password" wire:model.lazy="newPassword" class="form-control @error('newPassword') is-invalid @enderror" placeholder="Enter your new password">
                        @error('newPassword')
                        <div class="invalid-feedback">
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input type="password" id="password_confirmation" wire:model.lazy="confirmNewPassword" class="form-control @error('confirmNewPassword') is-invalid @enderror" placeholder="Confirm your new password">
                        @error('confirmNewPassword')
                        <div class="invalid-feedback">
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                {{ __('Reset Password') }}
                            </span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
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