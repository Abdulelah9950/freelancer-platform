<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FreelancerHub - Find the Perfect Freelancer for Your Project</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            min-height: 100vh;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.5px;
        }
        
        .welcome-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            transition: transform 0.3s ease;
        }
        
        .welcome-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: white;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(13, 110, 253, 0.2);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.5);
            color: white;
        }
        
        .btn-outline-gradient {
            background: transparent;
            color: white;
            border: 2px solid white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-gradient:hover {
            background: white;
            color: #0d6efd;
            border-color: white;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 800;
            color: #0d6efd;
            margin-bottom: 5px;
        }
        
        .stats-label {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .step-circle {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            font-weight: 800;
            color: #0d6efd;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .step-circle:hover {
            transform: scale(1.1);
            background: #0d6efd;
            color: white;
        }
        
        .footer {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .rating {
            color: #ffc107;
        }
        
        .section-title {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100" style="z-index: 1000;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-briefcase-fill me-2"></i>FreelancerHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#testimonials">Testimonials</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ url('/dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('jobs.index') }}">Browse Jobs</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-light text-primary ms-2 px-4 rounded-pill" href="{{ route('register') }}">Sign Up</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container min-vh-100 d-flex align-items-center">
        <div class="row w-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center text-white mb-5 mb-lg-0">
                <h1 class="display-2 fw-bold mb-4">
                    Find the Perfect<br>
                    <span class="text-warning">Freelancer</span> for Your Project
                </h1>
                <p class="lead mb-4 fs-4">
                    Connect with top-rated freelancers and get your work done efficiently. 
                    From web development to design, find the right talent for your needs.
                </p>
                <div class="d-flex gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-gradient btn-lg px-5">
                            <i class="bi bi-rocket-takeoff me-2"></i>Get Started
                        </a>
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-gradient btn-lg px-5">
                            <i class="bi bi-search me-2"></i>Browse Jobs
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-gradient btn-lg px-5">
                            <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                        </a>
                        @if(auth()->user()->isClient())
                            <a href="{{ route('jobs.create') }}" class="btn btn-outline-gradient btn-lg px-5">
                                <i class="bi bi-plus-circle me-2"></i>Post a Job
                            </a>
                        @endif
                    @endguest
                </div>
                
                <!-- Trust badges -->
                <div class="d-flex gap-4 mt-5">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-check fs-3 me-2"></i>
                        <span>Secure Payments</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock-history fs-3 me-2"></i>
                        <span>24/7 Support</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-star-fill fs-3 me-2 text-warning"></i>
                        <span>Top Rated</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="welcome-card p-5">
                    <h2 class="text-center mb-4 fw-bold" style="color: #0d6efd;">
                        Join FreelancerHub Today
                    </h2>
                    
                    <!-- Statistics -->
                    <div class="row g-4 mb-4">
                        <div class="col-4">
                            <div class="stats-card">
                                <div class="stats-number">50K+</div>
                                <div class="stats-label">Freelancers</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-card">
                                <div class="stats-number">100K+</div>
                                <div class="stats-label">Projects</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-card">
                                <div class="stats-number">$20M+</div>
                                <div class="stats-label">Earned</div>
                            </div>
                        </div>
                    </div>

                    <!-- Features List -->
                    <div class="list-group list-group-flush bg-transparent">
                        <div class="list-group-item bg-transparent border-0 ps-0 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle-fill text-success"></i>
                            </div>
                            <div>
                                <strong>Post jobs for free</strong>
                                <p class="text-muted small mb-0">No hidden fees, pay only when you hire</p>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-0 ps-0 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle-fill text-success"></i>
                            </div>
                            <div>
                                <strong>Browse top-rated freelancers</strong>
                                <p class="text-muted small mb-0">Find the perfect match for your project</p>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-0 ps-0 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle-fill text-success"></i>
                            </div>
                            <div>
                                <strong>Secure payments</strong>
                                <p class="text-muted small mb-0">Your money is always protected</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">
                            <i class="bi bi-people-fill me-2"></i>
                            Join 50,000+ satisfied clients and freelancers
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="container py-5">
        <h2 class="section-title text-center mb-5">Why Choose FreelancerHub?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card p-4 text-center h-100">
                    <div class="feature-icon">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Verified Professionals</h4>
                    <p class="text-muted">All freelancers are thoroughly vetted and verified to ensure quality work and professionalism.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4 text-center h-100">
                    <div class="feature-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Safe & Secure</h4>
                    <p class="text-muted">Your payments are protected with our escrow system until you're completely satisfied with the work.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4 text-center h-100">
                    <div class="feature-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h4 class="fw-bold mb-3">24/7 Support</h4>
                    <p class="text-muted">Our dedicated support team is always here to help you with any issues or questions.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div id="how-it-works" class="container py-5">
        <h2 class="section-title text-center mb-5">How FreelancerHub Works</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center text-white">
                    <div class="step-circle">
                        1
                    </div>
                    <h5 class="fw-bold text-white">Post a Job</h5>
                    <p class="text-white-50">Describe your project, budget, and requirements</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center text-white">
                    <div class="step-circle">
                        2
                    </div>
                    <h5 class="fw-bold text-white">Get Proposals</h5>
                    <p class="text-white-50">Receive bids from qualified freelancers worldwide</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center text-white">
                    <div class="step-circle">
                        3
                    </div>
                    <h5 class="fw-bold text-white">Choose & Hire</h5>
                    <p class="text-white-50">Review proposals and select the best freelancer</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center text-white">
                    <div class="step-circle">
                        4
                    </div>
                    <h5 class="fw-bold text-white">Get Results</h5>
                    <p class="text-white-50">Collaborate, get work done, and release payment</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div id="testimonials" class="container py-5">
        <h2 class="section-title text-center mb-5">What Our Users Say</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="rating mb-3">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="mb-4">"Found an amazing developer for my startup. The platform made it easy to find and hire the right talent."</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <span class="fw-bold">JD</span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">John Doe</h6>
                            <small class="text-muted">Startup Founder</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="rating mb-3">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="mb-4">"As a freelancer, FreelancerHub has helped me find consistent work and build long-term relationships with clients."</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <span class="fw-bold">JS</span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Jane Smith</h6>
                            <small class="text-muted">Web Developer</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="rating mb-3">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <p class="mb-4">"The best freelance platform I've used. Great communication tools and secure payment system."</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <span class="fw-bold">MJ</span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Mike Johnson</h6>
                            <small class="text-muted">Marketing Agency</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="container py-5">
        <div class="bg-white bg-opacity-10 backdrop-blur rounded-5 p-5 text-center">
            <h2 class="display-5 fw-bold text-white mb-4">Ready to Get Started?</h2>
            <p class="lead text-white mb-4">Join thousands of satisfied clients and freelancers on FreelancerHub</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-gradient btn-lg px-5">
                    <i class="bi bi-person-plus me-2"></i>Create Your Account
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-gradient btn-lg px-5">
                    <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                </a>
            @endguest
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3"><i class="bi bi-briefcase-fill me-2"></i>FreelancerHub</h5>
                    <p class="text-white-50">Connecting talented freelancers with amazing clients worldwide.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">For Clients</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">How to Hire</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Post a Job</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Browse Freelancers</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">For Freelancers</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">How to Start</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Find Work</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Success Stories</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">Company</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Careers</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Terms</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Privacy</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Security</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-white bg-opacity-25">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-white-50">&copy; {{ date('Y') }} FreelancerHub. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3">Terms</a>
                    <a href="#" class="text-white-50 text-decoration-none me-3">Privacy</a>
                    <a href="#" class="text-white-50 text-decoration-none">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>