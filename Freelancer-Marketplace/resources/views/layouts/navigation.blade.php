<nav class="navbar navbar-expand-lg navbar-dark bg-gradient sticky-top" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="{{ url('/') }}">
            <i class="bi bi-briefcase-fill me-2"></i>FreelancerHub
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('jobs.*') ? 'active fw-semibold' : '' }}" href="{{ route('jobs.index') }}">
                        <i class="bi bi-briefcase me-1"></i>Jobs
                    </a>
                </li>
                
                @auth
                    @if(auth()->user()->isFreelancer())
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('proposals.*') ? 'active fw-semibold' : '' }}" href="{{ route('proposals.index') }}">
                                <i class="bi bi-send me-1"></i>My Proposals
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('contracts.*') ? 'active fw-semibold' : '' }}" href="{{ route('contracts.index') }}">
                            <i class="bi bi-file-text me-1"></i>Contracts
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-shield-lock me-1"></i>Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
                                <li>
                                    <a class="dropdown-item text-white" href="{{ route('admin.users.index') }}">
                                        <i class="bi bi-people me-2"></i>Manage Users
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-white" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-light rounded-pill px-4" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i>Sign Up
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <span class="fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-0 shadow-lg" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
                            <li>
                                <a class="dropdown-item text-white" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-white" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-circle me-2"></i>Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider bg-white bg-opacity-25"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-white">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Add this CSS to your existing styles or create a new style block -->
<style>
    .navbar {
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        padding: 15px 0;
    }
    
    .navbar .nav-link {
        position: relative;
        padding: 8px 15px !important;
        transition: all 0.3s ease;
        border-radius: 50px;
        margin: 0 2px;
    }
    
    .navbar .nav-link:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    
    .navbar .nav-link.active {
        background: rgba(255, 255, 255, 0.25);
        font-weight: 600;
    }
    
    .navbar .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 3px;
        background: white;
        border-radius: 2px;
    }
    
    .navbar .dropdown-menu {
        border-radius: 15px;
        margin-top: 10px;
        padding: 10px;
        min-width: 220px;
        animation: slideDown 0.3s ease;
    }
    
    .navbar .dropdown-item {
        border-radius: 10px;
        padding: 10px 15px;
        transition: all 0.3s ease;
        margin-bottom: 2px;
    }
    
    .navbar .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        transform: translateX(5px);
    }
    
    .btn-light {
        background: white;
        color: #0d6efd;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,255,255,0.3);
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Mobile responsive adjustments */
    @media (max-width: 991.98px) {
        .navbar .nav-link.active::after {
            display: none;
        }
        
        .navbar .nav-link {
            padding: 12px 15px !important;
        }
        
        .navbar .dropdown-menu {
            background: rgba(255, 255, 255, 0.1) !important;
            box-shadow: none;
            margin-left: 15px;
            width: calc(100% - 30px);
        }
        
        .btn-light {
            margin: 10px 15px;
            text-align: center;
        }
    }
</style>