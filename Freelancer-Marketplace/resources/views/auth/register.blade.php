@extends('layouts.app')

@section('title', 'Join FreelancerHub')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-4">
        <div class="col-md-10 col-lg-8 col-xl-7">
            <!-- Logo/Brand -->
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        <i class="bi bi-briefcase-fill me-2"></i>FreelancerHub
                    </h1>
                </a>
                <p class="text-muted">Join thousands of freelancers and clients on our platform.</p>
            </div>

            <!-- Registration Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <ul class="nav nav-pills nav-justified mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('login') }}" class="nav-link fw-semibold text-muted" id="pills-login-tab">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab" aria-selected="true">
                                <i class="bi bi-person-plus me-2"></i>Register
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-register" role="tabpanel">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <!-- Name Field -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium text-muted mb-2">
                                        <i class="bi bi-person me-2"></i>Full Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" id="name-addon">
                                            <i class="bi bi-person text-primary"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control bg-light border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Enter your full name"
                                               aria-describedby="name-addon"
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email Field -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium text-muted mb-2">
                                        <i class="bi bi-envelope me-2"></i>Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" id="email-addon">
                                            <i class="bi bi-envelope text-primary"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="Enter your email"
                                               aria-describedby="email-addon"
                                               required>
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium text-muted mb-2">
                                        <i class="bi bi-lock me-2"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" id="password-addon">
                                            <i class="bi bi-lock text-primary"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control bg-light border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Create a password"
                                               aria-describedby="password-addon"
                                               required>
                                        <button class="btn btn-light border" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%;"></div>
                                    </div>
                                    <small class="text-muted">Password must be at least 8 characters</small>
                                </div>

                                <!-- Confirm Password Field -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium text-muted mb-2">
                                        <i class="bi bi-lock-fill me-2"></i>Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" id="confirm-addon">
                                            <i class="bi bi-lock-fill text-primary"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control bg-light border-start-0 ps-0" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirm your password"
                                               aria-describedby="confirm-addon"
                                               required>
                                    </div>
                                </div>

                                <!-- Role Selection -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium text-muted mb-2">
                                        <i class="bi bi-person-badge me-2"></i>I want to join as:
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="form-check card-radio p-3 border rounded-3 {{ old('role') == 'freelancer' ? 'border-primary bg-light' : '' }}">
                                                <input class="form-check-input" type="radio" name="role" id="freelancer" value="freelancer" {{ old('role') == 'freelancer' ? 'checked' : '' }} checked>
                                                <label class="form-check-label w-100" for="freelancer">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                                            <i class="bi bi-person-workspace text-success"></i>
                                                        </div>
                                                        <div>
                                                            <span class="fw-bold d-block">Freelancer</span>
                                                            <small class="text-muted">I want to work on projects</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check card-radio p-3 border rounded-3 {{ old('role') == 'client' ? 'border-primary bg-light' : '' }}">
                                                <input class="form-check-input" type="radio" name="role" id="client" value="client" {{ old('role') == 'client' ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="client">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                            <i class="bi bi-briefcase text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <span class="fw-bold d-block">Client</span>
                                                            <small class="text-muted">I want to post projects</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('role')
                                        <div class="text-danger small mt-2">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                        <label class="form-check-label text-muted small" for="terms">
                                            I agree to the <a href="#" class="text-primary text-decoration-none">Terms of Service</a> and 
                                            <a href="#" class="text-primary text-decoration-none">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary-modern btn-lg py-3">
                                        <i class="bi bi-person-plus me-2"></i>Create Your Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light bg-opacity-50 border-0 text-center p-4">
                    <p class="text-muted mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">
                            Sign in here <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-radio {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .card-radio:hover {
        border-color: #667eea !important;
        background-color: #f8f9fa;
    }
    
    .card-radio .form-check-input:checked + .form-check-label .card-radio {
        border-color: #667eea;
        background-color: #f0f4ff;
    }
    
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .nav-pills .nav-link {
        color: #4a5568;
        border-radius: 50px;
        padding: 10px 20px;
    }
    
    .nav-pills .nav-link:hover:not(.active) {
        background: #f8f9fa;
    }
    
    .input-group-text {
        border-radius: 10px 0 0 10px;
    }
    
    .form-control {
        border-radius: 0 10px 10px 0;
        border-left: none;
    }
    
    .form-control:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }
    
    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        border-radius: 10px;
    }
    
    .input-group:focus-within .input-group-text {
        border-color: #86b7fe;
    }
    
    .input-group:focus-within .form-control {
        border-color: #86b7fe;
    }
    
    #togglePassword {
        border-radius: 0 10px 10px 0;
        border-left: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });

    // Simple password strength indicator
    document.getElementById('password').addEventListener('input', function() {
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

    // Highlight selected role card
    document.querySelectorAll('.card-radio').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.card-radio').forEach(c => {
                c.classList.remove('border-primary', 'bg-light');
            });
            this.classList.add('border-primary', 'bg-light');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
</script>
@endpush