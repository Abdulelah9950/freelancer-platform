@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-2" style="color: #2d3748;">
                Welcome back, <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; color: transparent;">{{ Auth::user()->name }}</span>!
            </h1>
            <p class="text-muted fs-5">Manage your jobs and find the perfect freelancer for your projects.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('jobs.create') }}" class="btn btn-primary-modern btn-lg">
                <i class="bi bi-plus-circle me-2"></i>Post a New Job
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div class="stat-number">{{ $jobs->total() }}</div>
                <div class="stat-label">Total Jobs Posted</div>
                <div class="mt-3">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                        <i class="bi bi-arrow-up me-1"></i> {{ $jobs->where('status', 'open')->count() }} Open
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="stat-number">{{ $recentProposals->count() }}</div>
                <div class="stat-label">Proposals Received</div>
                <div class="mt-3">
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                        <i class="bi bi-clock me-1"></i> {{ $recentProposals->where('status', 'pending')->count() }} Pending
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="stat-number">{{ $activeContracts->count() }}</div>
                <div class="stat-label">Active Contracts</div>
                <div class="mt-3">
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i> {{ $activeContracts->count() }} In Progress
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- My Jobs Section -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-briefcase me-2" style="color: #667eea;"></i>
                        My Jobs
                    </h5>
                    <a href="{{ route('jobs.index') }}?client={{ Auth::id() }}" class="btn btn-sm btn-outline-modern">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($jobs as $job)
                        <div class="job-item px-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <a href="{{ route('jobs.show', $job) }}" class="job-title fs-5">
                                    {{ $job->title }}
                                </a>
                                <span class="badge-modern {{ $job->status === 'open' ? 'badge-open' : 'badge-closed' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2">{{ Str::limit($job->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-3">
                                    <span class="text-primary fw-bold">
                                        ${{ number_format($job->budget, 2) }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="bi bi-people me-1"></i> {{ $job->proposals_count ?? 0 }} proposals
                                    </span>
                                </div>
                                <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-link text-primary p-0">
                                    View Details <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-briefcase"></i>
                            <h6 class="mt-3">No jobs posted yet</h6>
                            <p class="text-muted small">Start by posting your first job</p>
                            <a href="{{ route('jobs.create') }}" class="btn btn-primary-modern btn-sm mt-2">
                                <i class="bi bi-plus-circle me-2"></i>Post a Job
                            </a>
                        </div>
                    @endforelse

                    @if($jobs->hasPages())
                        <div class="p-4 border-top">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Proposals Section -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope me-2" style="color: #84fab0;"></i>
                        Recent Proposals
                    </h5>
                    <a href="{{ route('proposals.index') }}" class="btn btn-sm btn-outline-modern">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($recentProposals as $proposal)
                        @if($proposal->job) {{-- Check if job exists --}}
                            <div class="job-item px-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <a href="{{ route('jobs.show', $proposal->job) }}" class="job-title">
                                        {{ $proposal->job->title }}
                                    </a>
                                    <span class="badge-modern badge-{{ $proposal->status }}">
                                        ${{ number_format($proposal->bid_amount, 2) }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        <span class="small fw-bold">{{ strtoupper(substr($proposal->freelancer->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="fw-medium">{{ $proposal->freelancer->name }}</span>
                                    <span class="text-muted ms-2 small">• {{ $proposal->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-muted small mb-2">"{{ Str::limit($proposal->cover_letter, 80) }}"</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge-modern badge-{{ $proposal->status }}">
                                        {{ ucfirst($proposal->status) }}
                                    </span>
                                    <a href="{{ route('jobs.show', $proposal->job) }}" class="btn btn-sm btn-link text-primary p-0">
                                        Review <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @else
                            {{-- Handle proposal with deleted job --}}
                            <div class="job-item px-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="job-title text-muted">
                                        <i class="bi bi-exclamation-triangle text-warning me-1"></i>
                                        [Job Deleted]
                                    </span>
                                    <span class="badge-modern badge-{{ $proposal->status }}">
                                        ${{ number_format($proposal->bid_amount, 2) }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        <span class="small fw-bold">{{ strtoupper(substr($proposal->freelancer->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="fw-medium">{{ $proposal->freelancer->name }}</span>
                                    <span class="text-muted ms-2 small">• {{ $proposal->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-muted small mb-2">"{{ Str::limit($proposal->cover_letter, 80) }}"</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge-modern badge-{{ $proposal->status }}">
                                        {{ ucfirst($proposal->status) }}
                                    </span>
                                    <span class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>Job no longer available
                                    </span>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-envelope"></i>
                            <h6 class="mt-3">No proposals yet</h6>
                            <p class="text-muted small">When freelancers submit proposals, they'll appear here</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Active Contracts Section -->
    @if($activeContracts->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-text me-2" style="color: #f6d365;"></i>
                            Active Contracts
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-modern table mb-0">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Freelancer</th>
                                        <th>Started</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeContracts as $contract)
                                        @if($contract->job) {{-- Check if job exists --}}
                                            <tr>
                                                <td>
                                                    <a href="{{ route('jobs.show', $contract->job) }}" class="job-title">
                                                        {{ Str::limit($contract->job->title, 40) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                            <span class="small fw-bold">{{ strtoupper(substr($contract->freelancer->name, 0, 1)) }}</span>
                                                        </div>
                                                        {{ $contract->freelancer->name }}
                                                    </div>
                                                </td>
                                                <td>{{ $contract->start_date->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge-modern badge-accepted">
                                                        {{ ucfirst($contract->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-outline-modern">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection