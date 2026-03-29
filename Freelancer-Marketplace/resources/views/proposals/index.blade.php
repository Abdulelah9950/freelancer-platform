@extends('layouts.app')

@section('title', 'My Proposals')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="bi bi-send fs-1 text-success"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-2" style="color: #2d3748;">
                        My Proposals
                    </h1>
                    <p class="text-muted fs-5 mb-0">Track and manage all your submitted proposals.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-search me-2"></i>Browse More Jobs
            </a>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill flex-column flex-sm-row">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
                               href="{{ route('proposals.index') }}">
                                <i class="bi bi-grid me-2"></i>
                                All
                                <span class="badge bg-white text-dark ms-2">{{ $proposals->total() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
                               href="{{ route('proposals.index', ['status' => 'pending']) }}">
                                <i class="bi bi-clock me-2"></i>
                                Pending
                                <span class="badge bg-white text-dark ms-2">{{ $proposals->where('status', 'pending')->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'accepted' ? 'active' : '' }}" 
                               href="{{ route('proposals.index', ['status' => 'accepted']) }}">
                                <i class="bi bi-check-circle me-2"></i>
                                Accepted
                                <span class="badge bg-white text-dark ms-2">{{ $proposals->where('status', 'accepted')->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'rejected' ? 'active' : '' }}" 
                               href="{{ route('proposals.index', ['status' => 'rejected']) }}">
                                <i class="bi bi-x-circle me-2"></i>
                                Rejected
                                <span class="badge bg-white text-dark ms-2">{{ $proposals->where('status', 'rejected')->count() }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Proposals List -->
    <div class="row">
        <div class="col-12">
            @forelse($proposals as $proposal)
                <div class="card mb-3 border-0 shadow-sm proposal-card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-8">
                                @if($proposal->job)
                                    {{-- Job exists --}}
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="badge bg-{{ $proposal->status === 'pending' ? 'warning' : ($proposal->status === 'accepted' ? 'success' : 'danger') }} bg-opacity-10 text-{{ $proposal->status === 'pending' ? 'warning' : ($proposal->status === 'accepted' ? 'success' : 'danger') }} px-3 py-2 rounded-pill">
                                            <span class="status-dot me-1"></span>
                                            {{ ucfirst($proposal->status) }}
                                        </span>
                                        <span class="badge bg-light text-muted px-3 py-2 rounded-pill">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $proposal->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <h4 class="h5 fw-bold mb-3">
                                        <a href="{{ route('jobs.show', $proposal->job) }}" class="text-decoration-none text-dark hover-primary">
                                            {{ $proposal->job->title }}
                                        </a>
                                    </h4>

                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <span class="small fw-bold text-primary">{{ strtoupper(substr($proposal->job->client->name, 0, 1)) }}</span>
                                            </div>
                                            <span class="text-muted">{{ $proposal->job->client->name }}</span>
                                        </div>
                                        <span class="text-muted">•</span>
                                        <span class="text-muted">
                                            <i class="bi bi-cash-stack me-1"></i>
                                            Budget: ${{ number_format($proposal->job->budget, 0) }}
                                        </span>
                                    </div>

                                    <div class="bg-light p-3 rounded-3 mb-3">
                                        <p class="mb-0 text-muted fst-italic">"{{ Str::limit($proposal->cover_letter, 200) }}"</p>
                                    </div>

                                    @if($proposal->status === 'accepted' && $proposal->job->contract)
                                        <div class="alert alert-success bg-opacity-10 border-0 py-2 mb-0">
                                            <i class="bi bi-patch-check-fill me-2"></i>
                                            Contract started - 
                                            <a href="{{ route('contracts.show', $proposal->job->contract) }}" class="alert-link">
                                                View Contract Details <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    {{-- Job was deleted --}}
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="badge bg-{{ $proposal->status === 'pending' ? 'warning' : ($proposal->status === 'accepted' ? 'success' : 'danger') }} bg-opacity-10 text-{{ $proposal->status === 'pending' ? 'warning' : ($proposal->status === 'accepted' ? 'success' : 'danger') }} px-3 py-2 rounded-pill">
                                            <span class="status-dot me-1"></span>
                                            {{ ucfirst($proposal->status) }}
                                        </span>
                                        <span class="badge bg-light text-muted px-3 py-2 rounded-pill">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $proposal->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <div class="alert alert-warning bg-opacity-10 border-0 d-flex align-items-center gap-2 py-2 mb-3">
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                        <span class="text-muted">The job this proposal was for is no longer available.</span>
                                    </div>

                                    <div class="bg-light p-3 rounded-3 mb-3">
                                        <p class="mb-0 text-muted fst-italic">"{{ Str::limit($proposal->cover_letter, 200) }}"</p>
                                    </div>
                                @endif
                            </div>

                            <div class="col-lg-4 mt-4 mt-lg-0">
                                <div class="bg-light rounded-3 p-4 h-100 d-flex flex-column">
                                    <div class="text-center mb-4">
                                        <span class="text-muted small text-uppercase">Your Bid</span>
                                        <div class="display-5 fw-bold text-primary">
                                            ${{ number_format($proposal->bid_amount, 0) }}
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-2 mt-auto">
                                        @if($proposal->job && $proposal->status === 'pending')
                                            <a href="{{ route('jobs.proposals.edit', [$proposal->job, $proposal]) }}" 
                                               class="btn btn-outline-warning w-100">
                                                <i class="bi bi-pencil me-2"></i>Edit Proposal
                                            </a>
                                        @endif

                                        @if($proposal->job && $proposal->status === 'accepted' && $proposal->job->contract)
                                            <a href="{{ route('contracts.show', $proposal->job->contract) }}" 
                                               class="btn btn-success w-100">
                                                <i class="bi bi-file-text me-2"></i>View Contract
                                            </a>
                                        @endif

                                        @if(!$proposal->job)
                                            <span class="badge bg-secondary w-100 py-2">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Job Unavailable
                                            </span>
                                        @endif

                                        @if($proposal->status === 'rejected')
                                            <span class="badge bg-danger bg-opacity-10 text-danger w-100 py-2">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Proposal Rejected
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="bg-light d-inline-flex rounded-circle p-4 mb-4">
                            <i class="bi bi-send fs-1 text-muted"></i>
                        </div>
                        <h4 class="fw-bold mb-3">No proposals found</h4>
                        <p class="text-muted mb-4">You haven't submitted any proposals yet. Start exploring jobs and submit your first proposal!</p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-search me-2"></i>Browse Available Jobs
                        </a>
                    </div>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($proposals->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $proposals->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Status dot animation */
    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: currentColor;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    /* Proposal card hover effect */
    .proposal-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .proposal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }

    /* Custom link hover */
    .hover-primary:hover {
        color: #667eea !important;
    }

    /* Nav pills custom styling */
    .nav-pills .nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 50px;
        transition: all 0.2s;
    }

    .nav-pills .nav-link:hover {
        background-color: #e9ecef;
        color: #495057;
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .nav-pills .nav-link.active .badge {
        background: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .nav-pills .nav-link .badge {
        font-weight: 500;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .nav-pills .nav-link {
            margin-bottom: 0.5rem;
        }
        
        .display-5 {
            font-size: 2rem;
        }
    }
</style>
@endpush
@endsection