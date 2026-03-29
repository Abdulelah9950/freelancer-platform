<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - FreelancerHub</title>

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

    

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }

        /* Modern Navigation */
        .navbar-modern {
            background: white;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            padding: 15px 0;
        }

        .navbar-modern .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-modern .nav-link {
            color: #4a5568;
            font-weight: 500;
            padding: 8px 16px !important;
            border-radius: 50px;
            transition: all 0.3s ease;
            margin: 0 2px;
        }

        .navbar-modern .nav-link:hover {
            background: #f7fafc;
            color: #667eea;
            transform: translateY(-1px);
        }

        .navbar-modern .nav-link.active {
            background: var(--primary-gradient);
            color: white !important;
        }

        .navbar-modern .nav-link i {
            margin-right: 6px;
        }

        .navbar-modern .user-dropdown .dropdown-toggle {
            background: #f7fafc;
            border-radius: 50px;
            padding: 6px 16px 6px 6px !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-modern .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .navbar-modern .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 10px;
            min-width: 220px;
            margin-top: 10px;
        }

        .navbar-modern .dropdown-item {
            border-radius: 10px;
            padding: 10px 15px;
            color: #4a5568;
            transition: all 0.3s ease;
        }

        .navbar-modern .dropdown-item:hover {
            background: #f7fafc;
            color: #667eea;
            transform: translateX(5px);
        }

        .navbar-modern .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            color: #667eea;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-radius: 50%;
            z-index: 0;
        }

        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .stat-card .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #2d3748;
            line-height: 1.2;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }

        .stat-card .stat-label {
            color: #718096;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .job-item {
            border-bottom: 1px solid #edf2f7;
            padding: 20px 0;
            transition: all 0.3s ease;
        }

        .job-item:last-child {
            border-bottom: none;
        }

        .job-item:hover {
            transform: translateX(5px);
        }

        .job-title {
            font-weight: 600;
            color: #2d3748;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .job-title:hover {
            color: #667eea;
        }

        .badge-modern {
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge-open {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: #2d3748;
        }

        .badge-closed {
            background: #e2e8f0;
            color: #718096;
        }

        .badge-pending {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: white;
        }

        .badge-accepted {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: #2d3748;
        }

        .badge-rejected {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-modern {
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-modern {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }

        .btn-outline-modern:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
        }

        .section-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #edf2f7;
        }

        .section-header h5 {
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .table-modern {
            border-radius: 15px;
            overflow: hidden;
        }

        .table-modern thead th {
            background: #f8f9fa;
            color: #4a5568;
            font-weight: 600;
            border-bottom: none;
            padding: 15px;
        }

        .table-modern tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .stat-card {
                padding: 20px;
            }
            
            .stat-card .stat-number {
                font-size: 1.8rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: start !important;
                gap: 10px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Modern Navigation -->
    <nav class="navbar navbar-expand-lg navbar-modern sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-briefcase-fill me-2" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                FreelancerHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">
                            <i class="bi bi-briefcase"></i> Jobs
                        </a>
                    </li>
                    
                    @auth
                        @if(auth()->user()->isFreelancer())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('proposals.*') ? 'active' : '' }}" href="{{ route('proposals.index') }}">
                                    <i class="bi bi-send"></i> My Proposals
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}" href="{{ route('contracts.index') }}">
                                <i class="bi bi-file-text"></i> Contracts
                            </a>
                        </li>

                        @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-shield-lock"></i> Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                            <i class="bi bi-people"></i> Manage Users
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary-modern" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Sign Up
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown user-dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        <div class="user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <span>{{ Auth::user()->name }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('profile.show') }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">
                    <i class="bi bi-box-arrow-right"></i> Logout
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

    <main class="py-4">
        <div class="container-fluid px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>