@extends('layouts.app')

@section('title', 'Login to FreelancerHub')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <!-- Logo/Brand -->
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <h1 class="display-5 fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        <i class="bi bi-briefcase-fill me-2"></i>FreelancerHub
                    </h1>
                </a>
                <p class="text-muted">Welcome back! Please login to your account.</p>
            </div>

            <!-- Login Card -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <ul class="nav nav-pills nav-justified mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab" aria-selected="true">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{ route('register') }}" class="nav-link fw-semibold text-muted" id="pills-register-tab">
                                <i class="bi bi-person-plus me-2"></i>Register
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-login" role="tabpanel">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Field -->
                                <div class="mb-4">
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
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="password" class="form-label fw-medium text-muted mb-0">
                                            <i class="bi bi-lock me-2"></i>Password
                                        </label>
                                        <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">
                                            Forgot Password?
                                        </a>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" id="password-addon">
                                            <i class="bi bi-lock text-primary"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control bg-light border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Enter your password"
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
                                </div>

                                <!-- Remember Me -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-muted" for="remember">
                                            Remember me on this device
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary-modern btn-lg py-3">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Login to Your Account
                                    </button>
                                </div>
                            </form>

                            <!-- Social Login (Optional) -->
                            <div class="text-center mt-4">
                                <p class="text-muted small mb-3">Or continue with</p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="#" class="btn btn-outline-secondary rounded-circle p-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-google"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-secondary rounded-circle p-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-github"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-secondary rounded-circle p-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light bg-opacity-50 border-0 text-center p-4">
                    <p class="text-muted mb-0">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">
                            Create one now <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </p>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="row mt-4 text-center">
                <div class="col-4">
                    <i class="bi bi-shield-check text-success fs-4"></i>
                    <p class="small text-muted mt-1">Secure Login</p>
                </div>
                <div class="col-4">
                    <i class="bi bi-lock text-primary fs-4"></i>
                    <p class="small text-muted mt-1">Encrypted</p>
                </div>
                <div class="col-4">
                    <i class="bi bi-clock-history text-info fs-4"></i>
                    <p class="small text-muted mt-1">24/7 Support</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
</script>
@endpush