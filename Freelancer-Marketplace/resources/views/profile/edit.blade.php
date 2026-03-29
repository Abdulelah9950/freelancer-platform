@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Edit Profile Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="bg-gradient-primary p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h4 class="text-white fw-bold mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Profile
                    </h4>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 bg-success bg-opacity-10 text-success" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 bg-danger bg-opacity-10 text-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Avatar Preview -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 100px; height: 100px; font-size: 40px; font-weight: 600;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <button type="button" class="btn btn-sm btn-light rounded-circle position-absolute bottom-0 end-0" 
                                        style="width: 32px; height: 32px;" data-bs-toggle="tooltip" title="Change avatar coming soon">
                                    <i class="bi bi-camera"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-person me-2"></i>Full Name
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       placeholder="Enter your full name"
                                       required>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope me-2"></i>Email Address
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-primary"></i>
                                </span>
                                <input type="email" 
                                       class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       placeholder="Enter your email"
                                       required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Role Display -->
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-person-badge me-2"></i>Account Type
                            </label>
                            <div>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'freelancer' ? 'success' : 'info') }} px-4 py-2">
                                    <i class="bi bi-{{ $user->role === 'freelancer' ? 'person-workspace' : ($user->role === 'client' ? 'briefcase' : 'shield') }} me-2"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>Account type cannot be changed.
                            </small>
                        </div>

                        <hr class="my-4">

                        <!-- Password Change Section -->
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-shield-lock me-2 text-primary"></i>Change Password
                        </h5>
                        <p class="text-muted small mb-4">Leave blank if you don't want to change your password</p>

                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-semibold">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-primary"></i>
                                </span>
                                <input type="password" 
                                       class="form-control bg-light border-start-0 @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password"
                                       placeholder="Enter your current password">
                                <button class="btn btn-light border" type="button" id="toggleCurrentPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="new_password" class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-key text-primary"></i>
                                </span>
                                <input type="password" 
                                       class="form-control bg-light border-start-0 @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password"
                                       placeholder="Enter new password">
                                <button class="btn btn-light border" type="button" id="toggleNewPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i>Password must be at least 8 characters
                            </small>
                            @error('new_password')
                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-key-fill text-primary"></i>
                                </span>
                                <input type="password" 
                                       class="form-control bg-light border-start-0" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation"
                                       placeholder="Confirm your new password">
                                <button class="btn btn-light border" type="button" id="toggleConfirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 mt-5">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="bi bi-check-circle me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="card border-0 shadow-sm rounded-4 border-danger border-opacity-25">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-danger mb-1">Delete Account</h5>
                            <p class="text-muted small mb-0">Once deleted, this action cannot be undone</p>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning-emphasis rounded-3">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        This will permanently delete your account and all associated data.
                    </div>

                    <form method="POST" action="{{ route('profile.destroy') }}" 
                          onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-danger"></i>
                                </span>
                                <input type="password" 
                                       class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password to confirm"
                                       required>
                                <button class="btn btn-light border" type="button" id="toggleDeletePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger rounded-pill px-5 py-2">
                            <i class="bi bi-trash3 me-2"></i>Permanently Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function setupPasswordToggle(buttonId, inputId) {
        document.getElementById(buttonId).addEventListener('click', function() {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }

    setupPasswordToggle('toggleCurrentPassword', 'current_password');
    setupPasswordToggle('toggleNewPassword', 'new_password');
    setupPasswordToggle('toggleConfirmPassword', 'new_password_confirmation');
    setupPasswordToggle('toggleDeletePassword', 'password');

    // Password strength indicator
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrength');
        let strength = 0;
        
        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]+/)) strength += 25;
        if (password.match(/[A-Z]+/)) strength += 25;
        if (password.match(/[0-9]+/) || password.match(/[$@#&!]+/)) strength += 25;
        
        strengthBar.style.width = strength + '%';
        
        if (strength < 50) {
            strengthBar.className = 'progress-bar bg-danger';
        } else if (strength < 75) {
            strengthBar.className = 'progress-bar bg-warning';
        } else {
            strengthBar.className = 'progress-bar bg-success';
        }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush