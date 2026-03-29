@extends('layouts.app')

@section('title', 'Contract Details')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header with Breadcrumb and Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('contracts.index') }}" class="text-decoration-none">
                                <i class="bi bi-file-text me-1"></i>Contracts
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Contract #{{ $contract->id }}
                        </li>
                    </ol>
                </nav>
                
                <!-- Status Badge -->
                <span class="badge bg-{{ $contract->status === 'active' ? 'success' : ($contract->status === 'completed' ? 'primary' : 'danger') }} bg-opacity-10 text-{{ $contract->status === 'active' ? 'success' : ($contract->status === 'completed' ? 'primary' : 'danger') }} px-3 py-2 rounded-pill d-inline-flex align-items-center">
                    <span class="status-dot me-2"></span>
                    {{ ucfirst($contract->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - Main Contract Details -->
        <div class="col-lg-8">
            <!-- Contract Overview Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light bg-opacity-50 border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text me-2 text-primary"></i>
                        Contract Overview
                    </h5>
                    <span class="text-muted">#{{ $contract->id }}</span>
                </div>
                
                <div class="card-body p-4">
                    <!-- Job Title with Link -->
                    <div class="mb-4">
                        <h2 class="h4 mb-2">
                            <a href="{{ route('jobs.show', $contract->job) }}" class="text-decoration-none text-dark">
                                {{ $contract->job->title }}
                            </a>
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar3 me-2"></i>
                            Started {{ $contract->start_date->format('F j, Y') }}
                        </p>
                    </div>

                    <!-- Timeline Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded-3 d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-2 p-3 me-3">
                                    <i class="bi bi-calendar-check text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted text-uppercase">Start Date</small>
                                    <div class="fw-semibold">{{ $contract->start_date->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded-3 d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-2 p-3 me-3">
                                    <i class="bi bi-calendar-x text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted text-uppercase">End Date</small>
                                    <div class="fw-semibold">
                                        {{ $contract->end_date ? $contract->end_date->format('M d, Y') : 'Not set' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded-3 d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-2 p-3 me-3">
                                    <i class="bi bi-clock-history text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted text-uppercase">Duration</small>
                                    <div class="fw-semibold">
                                        @if($contract->end_date)
                                            {{ $contract->start_date->diffInDays($contract->end_date) }} days
                                        @else
                                            {{ $contract->start_date->diffInDays(now()) }} days (ongoing)
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Overview -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-cash-stack me-2 text-success"></i>
                            Financial Details
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded-3">
                                    <small class="text-muted">Contract Value</small>
                                    <div class="fs-4 fw-bold text-primary">${{ number_format($contract->job->budget, 0) }}</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded-3">
                                    <small class="text-muted">Payment Status</small>
                                    <div class="mt-1">
                                        <span class="badge bg-{{ $contract->status === 'active' ? 'warning' : ($contract->status === 'completed' ? 'success' : 'secondary') }} bg-opacity-10 text-{{ $contract->status === 'active' ? 'warning' : ($contract->status === 'completed' ? 'success' : 'secondary') }} px-3 py-2">
                                            <i class="bi bi-credit-card me-1"></i>
                                            @if($contract->status === 'active')
                                                Pending
                                            @elseif($contract->status === 'completed')
                                                Completed
                                            @elseif($contract->status === 'terminated')
                                                Cancelled
                                            @else
                                                {{ ucfirst($contract->status) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded-3">
                                    <small class="text-muted">Payment Method</small>
                                    <div class="fw-semibold">Milestone-based</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Description Preview -->
                    <div>
                        <h6 class="fw-semibold mb-3">
                            <i class="bi bi-file-text me-2 text-primary"></i>
                            Job Description
                        </h6>
                        
                        <div class="bg-light p-3 rounded-3">
                            <p class="text-muted mb-3">{{ Str::limit($contract->job->description, 300) }}</p>
                            <a href="{{ route('jobs.show', $contract->job) }}" class="text-decoration-none">
                                View Full Description <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Metadata -->
                <div class="card-footer bg-light bg-opacity-50 border-0 py-3 px-4">
                    <div class="d-flex flex-wrap gap-4">
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            Created {{ $contract->created_at->format('M d, Y \a\t g:i A') }}
                        </small>
                        @if($contract->updated_at != $contract->created_at)
                            <small class="text-muted">
                                <i class="bi bi-pencil me-1"></i>
                                Updated {{ $contract->updated_at->diffForHumans() }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Milestones/Progress Section -->
            @if($contract->status === 'active')
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light bg-opacity-50 border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart me-2 text-success"></i>
                            Project Progress
                        </h5>
                        <span class="badge bg-success bg-opacity-10 text-success">45% Complete</span>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="bg-light p-4 rounded-3 mb-4">
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 45%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-check-circle-fill text-success me-1"></i> Milestone 1: Design Review</span>
                                <span><i class="bi bi-hourglass-split text-warning me-1"></i> Milestone 2: Development</span>
                            </div>
                        </div>

                        <!-- Placeholder for upcoming features -->
                        <div class="text-center py-4 border border-2 border-dashed rounded-3">
                            <i class="bi bi-megaphone fs-1 text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0">Milestone tracking coming soon</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Users & Actions -->
        <div class="col-lg-4">
            <!-- Client Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light bg-opacity-50 border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2 text-primary"></i>
                        Client
                    </h5>
                    <span class="badge bg-success bg-opacity-10 text-success">
                        <i class="bi bi-patch-check-fill me-1"></i>
                        Verified
                    </span>
                </div>
                
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 90px; height: 90px;">
                        <span class="fw-bold text-primary fs-2">{{ strtoupper(substr($contract->client->name, 0, 2)) }}</span>
                    </div>
                    <h5 class="mb-1">{{ $contract->client->name }}</h5>
                    <p class="text-muted small mb-3">{{ $contract->client->email }}</p>
                    
                    <div class="row g-2 pt-3 border-top">
                        <div class="col-4">
                            <div class="fw-bold">{{ $contract->client->created_at->format('M Y') }}</div>
                            <small class="text-muted">Member Since</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">{{ $contract->client->jobs()->count() }}</div>
                            <small class="text-muted">Jobs Posted</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">{{ $contract->client->clientContracts()->count() }}</div>
                            <small class="text-muted">Contracts</small>
                        </div>
                    </div>

                    @if(auth()->user()->isFreelancer())
                        <div class="mt-3 pt-3 border-top">
                            <a href="mailto:{{ $contract->client->email }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-envelope me-2"></i>Contact Client
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Freelancer Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light bg-opacity-50 border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="bi bi-person-workspace me-2 text-success"></i>
                        Freelancer
                    </h5>
                    <span class="badge bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-star-fill me-1"></i>
                        4.9
                    </span>
                </div>
                
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 90px; height: 90px;">
                        <span class="fw-bold text-success fs-2">{{ strtoupper(substr($contract->freelancer->name, 0, 2)) }}</span>
                    </div>
                    <h5 class="mb-1">{{ $contract->freelancer->name }}</h5>
                    <p class="text-muted small mb-3">{{ $contract->freelancer->email }}</p>
                    
                    <div class="row g-2 pt-3 border-top">
                        <div class="col-4">
                            <div class="fw-bold">{{ $contract->freelancer->created_at->format('M Y') }}</div>
                            <small class="text-muted">Member Since</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">{{ $contract->freelancer->proposals()->count() }}</div>
                            <small class="text-muted">Proposals</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">{{ $contract->freelancer->freelancerContracts()->count() }}</div>
                            <small class="text-muted">Contracts</small>
                        </div>
                    </div>

                    @if(auth()->user()->isClient())
                        <div class="mt-3 pt-3 border-top">
                            <a href="mailto:{{ $contract->freelancer->email }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-envelope me-2"></i>Contact Freelancer
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Card -->
            @if((auth()->user()->isClient() && $contract->status === 'active') || auth()->user()->isFreelancer())
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light bg-opacity-50 border-0 py-3 px-4">
                        <h5 class="mb-0">
                            <i class="bi bi-gear me-2 text-primary"></i>
                            Actions
                        </h5>
                    </div>
                    
                    <div class="card-body p-4">
                        @if(auth()->user()->isClient() && $contract->status === 'active')
                            <button type="button" class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="bi bi-pencil me-2"></i>Update Contract Status
                            </button>
                            
                            <button class="btn btn-outline-success w-100 mb-3">
                                <i class="bi bi-cash me-2"></i>Process Payment
                            </button>
                            
                            <button class="btn btn-outline-warning w-100">
                                <i class="bi bi-exclamation-triangle me-2"></i>Report Issue
                            </button>
                        @endif

                        @if(auth()->user()->isFreelancer() && $contract->status === 'active')
                            <div class="text-center p-4 bg-light rounded-3">
                                <i class="bi bi-envelope display-4 text-muted mb-2 d-block"></i>
                                <p class="text-muted small mb-2">
                                    Need to communicate with the client?
                                </p>
                                <a href="mailto:{{ $contract->client->email }}" class="btn btn-outline-primary">
                                    <i class="bi bi-envelope me-2"></i>Send Message
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Contracts
            </a>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
@if(auth()->user()->isClient() && $contract->status === 'active')
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>
                    Update Contract Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('contracts.update', $contract) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Contract Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $contract->status === 'active' ? 'selected' : '' }}>🟢 Active</option>
                            <option value="completed" {{ $contract->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="terminated" {{ $contract->status === 'terminated' ? 'selected' : '' }}>🔴 Terminated</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">End Date (optional)</label>
                        <input type="date" class="form-control" name="end_date" 
                               value="{{ $contract->end_date ? $contract->end_date->format('Y-m-d') : '' }}">
                        <div class="form-text">Leave empty if the contract is ongoing</div>
                    </div>

                    <div class="alert alert-info bg-opacity-10 border-0 small">
                        <i class="bi bi-info-circle me-2"></i>
                        Changing the status will notify the freelancer via email.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Update Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
/* Minimal custom CSS - only what Bootstrap doesn't provide */
.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: currentColor;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.border-dashed {
    border-style: dashed !important;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .bg-light.p-3.d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .bg-light.p-3.d-flex .me-3 {
        margin-right: 0 !important;
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection