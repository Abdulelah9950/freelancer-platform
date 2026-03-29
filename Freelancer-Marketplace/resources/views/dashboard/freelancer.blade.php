@extends('layouts.app')

@section('title', 'Freelancer Dashboard')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-2" style="color: #2d3748;">
                Welcome back, <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ Auth::user()->name }}</span>!
            </h1>
            <p class="text-muted fs-5">Find your next project and manage your proposals.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('jobs.index') }}" class="btn btn-primary-modern btn-lg">
                <i class="bi bi-search me-2"></i>Browse Jobs
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
                <div class="stat-number">{{ $availableJobs->total() }}</div>
                <div class="stat-label">Available Jobs</div>
                <div class="mt-3">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                        <i class="bi bi-cash me-1"></i> Up to ${{ number_format($availableJobs->max('budget') ?? 0, 0) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                    <i class="bi bi-send"></i>
                </div>
                <div class="stat-number">{{ $submittedProposals->total() }}</div>
                <div class="stat-label">My Proposals</div>
                <div class="mt-3">
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                        <i class="bi bi-clock me-1"></i> {{ $submittedProposals->where('status', 'pending')->count() }} Pending
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="stat-number">{{ $assignedContracts->count() }}</div>
                <div class="stat-label">Active Contracts</div>
                <div class="mt-3">
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i> {{ $assignedContracts->count() }} In Progress
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Available Jobs Section -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-briefcase me-2" style="color: #667eea;"></i>
                        Available Jobs
                    </h5>
                    <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-modern">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($availableJobs as $job)
                        <div class="job-item px-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <a href="{{ route('jobs.show', $job) }}" class="job-title fs-5">
                                    {{ $job->title }}
                                </a>
                                <span class="badge-modern badge-open">
                                    ${{ number_format($job->budget, 2) }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2">{{ Str::limit($job->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 25px; height: 25px;">
                                        <small class="fw-bold">{{ strtoupper(substr($job->client->name, 0, 1)) }}</small>
                                    </div>
                                    <small class="text-muted">{{ $job->client->name }}</small>
                                    <small class="text-muted">• {{ $job->created_at->diffForHumans() }}</small>
                                </div>
                                <a href="{{ route('jobs.proposals.create', $job) }}" class="btn btn-sm btn-success" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); border: none;">
                                    Apply Now <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-briefcase"></i>
                            <h6 class="mt-3">No jobs available</h6>
                            <p class="text-muted small">Check back later for new opportunities</p>
                        </div>
                    @endforelse

                    @if($availableJobs->hasPages())
                        <div class="p-4 border-top">
                            {{ $availableJobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

      <!-- My Proposals Section -->
<div class="col-lg-6">
    <div class="dashboard-card">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-send me-2" style="color: #84fab0;"></i>
                My Proposals
            </h5>
            <a href="{{ route('proposals.index') }}" class="btn btn-sm btn-outline-modern">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            @forelse($submittedProposals as $proposal)
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
                        <p class="text-muted small mb-2">"{{ Str::limit($proposal->cover_letter, 80) }}"</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge-modern badge-{{ $proposal->status }} me-2">
                                    {{ ucfirst($proposal->status) }}
                                </span>
                                <small class="text-muted">{{ $proposal->created_at->diffForHumans() }}</small>
                            </div>
                            @if($proposal->status === 'pending')
                                <a href="{{ route('jobs.proposals.edit', [$proposal->job, $proposal]) }}" 
                                   class="btn btn-sm btn-link text-warning p-0">
                                    Edit <i class="bi bi-pencil ms-1"></i>
                                </a>
                            @endif
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
                        <p class="text-muted small mb-2">"{{ Str::limit($proposal->cover_letter, 80) }}"</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge-modern badge-{{ $proposal->status }} me-2">
                                    {{ ucfirst($proposal->status) }}
                                </span>
                                <small class="text-muted">{{ $proposal->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>Job unavailable
                            </span>
                        </div>
                    </div>
                @endif
            @empty
                <div class="empty-state">
                    <i class="bi bi-send"></i>
                    <h6 class="mt-3">No proposals yet</h6>
                    <p class="text-muted small">Start applying to jobs to see your proposals here</p>
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary-modern btn-sm mt-2">
                        <i class="bi bi-search me-2"></i>Browse Jobs
                    </a>
                </div>
            @endforelse

            @if($submittedProposals->hasPages())
                <div class="p-4 border-top">
                    {{ $submittedProposals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
    </div>

    <!-- Active Contracts Section -->
    @if($assignedContracts->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-text me-2" style="color: #f6d365;"></i>
                            My Active Contracts
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-modern table mb-0">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Client</th>
                                        <th>Started</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedContracts as $contract)
                                        <tr>
                                            <td>
                                                <a href="{{ route('jobs.show', $contract->job) }}" class="job-title">
                                                    {{ Str::limit($contract->job->title, 40) }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                        <span class="small fw-bold">{{ strtoupper(substr($contract->client->name, 0, 1)) }}</span>
                                                    </div>
                                                    {{ $contract->client->name }}
                                                </div>
                                            </td>
                                            <td>{{ $contract->start_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge-modern badge-{{ $contract->status === 'active' ? 'accepted' : 'pending' }}">
                                                    {{ ucfirst($contract->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-outline-modern">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
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