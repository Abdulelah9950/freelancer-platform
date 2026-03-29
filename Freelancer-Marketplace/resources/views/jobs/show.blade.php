@extends('layouts.app')

@section('title', $job->title)

@section('content')
<div class="container py-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('jobs.index') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Jobs
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Job Details Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <!-- Header with Title and Status -->
                    <div class="d-flex flex-wrap align-items-start justify-content-between mb-3">
                        <h2 class="h3 fw-bold mb-3 mb-sm-0">{{ $job->title }}</h2>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="bi bi-cash-stack me-1"></i>
                                ${{ number_format($job->budget, 0) }}
                            </span>
                            <span class="badge bg-{{ $job->status === 'open' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $job->status === 'open' ? 'success' : 'secondary' }} px-3 py-2 rounded-pill">
                                <span class="status-dot me-1"></span>
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Client Info -->
                    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                        <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <span class="fw-bold text-primary">{{ strtoupper(substr($job->client->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold">{{ $job->client->name }}</p>
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>Posted {{ $job->created_at->format('F j, Y') }}
                            </small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-semibold mb-3">
                            <i class="bi bi-file-text me-2 text-primary"></i>
                            Description
                        </h5>
                        <p class="text-muted lh-lg">{{ $job->description }}</p>
                    </div>

                    <!-- Action Buttons for Client -->
                    @can('update', $job)
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex gap-2">
                                <a href="{{ route('jobs.edit', $job) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil me-2"></i>Edit Job
                                </a>
                                <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this job?')">
                                        <i class="bi bi-trash me-2"></i>Delete Job
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>

            <!-- Proposals Section (For Client) -->
            @if(auth()->user()->isClient() && auth()->id() === $job->client_id)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-semibold mb-0">
                            <i class="bi bi-envelope me-2 text-success"></i>
                            Proposals ({{ $job->proposals->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        @forelse($job->proposals as $proposal)
                            <div class="border rounded-3 p-3 mb-3 proposal-item">
                                <div class="d-flex flex-wrap align-items-start justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2 mb-2 mb-sm-0">
                                        <div class="bg-success bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <span class="fw-bold text-success">{{ strtoupper(substr($proposal->freelancer->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $proposal->freelancer->name }}</h6>
                                            <small class="text-muted">{{ $proposal->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                        <i class="bi bi-cash me-1"></i>
                                        ${{ number_format($proposal->bid_amount, 0) }}
                                    </span>
                                </div>
                                
                                <p class="text-muted small mb-3">{{ Str::limit($proposal->cover_letter, 150) }}</p>
                                
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <span class="badge bg-{{ $proposal->status === 'pending' ? 'warning' : ($proposal->status === 'accepted' ? 'success' : 'danger') }} bg-opacity-10 text-{{ $proposal->status === 'pending' ? 'warning' : ($proposal->status === 'accepted' ? 'success' : 'danger') }} px-3 py-2 rounded-pill">
                                        {{ ucfirst($proposal->status) }}
                                    </span>
                                    
                                    @if($job->status === 'open' && $proposal->status === 'pending')
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('proposals.accept', [$job, $proposal]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Accept this proposal and create a contract?')">
                                                    <i class="bi bi-check-lg me-1"></i>Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('proposals.reject', [$job, $proposal]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Reject this proposal?')">
                                                    <i class="bi bi-x-lg me-1"></i>Reject
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-envelope-open display-4 text-muted"></i>
                                <p class="text-muted mt-3 mb-0">No proposals yet.</p>
                                <small class="text-muted">When freelancers submit proposals, they'll appear here</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- Freelancer's Proposal Status -->
            @if(auth()->user()->isFreelancer() && isset($userProposal))
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-semibold mb-0">
                            <i class="bi bi-send me-2 text-success"></i>
                            Your Proposal
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="border rounded-3 p-3">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                    <i class="bi bi-cash me-1"></i>
                                    Bid: ${{ number_format($userProposal->bid_amount, 0) }}
                                </span>
                                <span class="badge bg-{{ $userProposal->status === 'pending' ? 'warning' : ($userProposal->status === 'accepted' ? 'success' : 'danger') }} bg-opacity-10 text-{{ $userProposal->status === 'pending' ? 'warning' : ($userProposal->status === 'accepted' ? 'success' : 'danger') }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($userProposal->status) }}
                                </span>
                            </div>
                            
                            <p class="text-muted mb-3">{{ $userProposal->cover_letter }}</p>
                            
                            @if($userProposal->status === 'pending')
                                <div class="mt-3">
                                    <a href="{{ route('jobs.proposals.edit', [$job, $userProposal]) }}" 
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil me-2"></i>Edit Proposal
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Apply Card for Freelancers -->
            @if(auth()->user()->isFreelancer() && $job->status === 'open' && !isset($userProposal))
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-send display-4 text-success"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Interested in this job?</h5>
                        <p class="text-muted small mb-4">Submit your proposal and bid for this project</p>
                        <a href="{{ route('jobs.proposals.create', $job) }}" class="btn btn-success w-100 py-2">
                            <i class="bi bi-send me-2"></i>Apply Now
                        </a>
                    </div>
                </div>
            @endif

            <!-- Contract Card -->
            @if($job->contract)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info bg-opacity-10 text-info border-0 pt-4 px-4">
                        <h5 class="fw-semibold mb-0">
                            <i class="bi bi-file-text me-2"></i>
                            Contract Status
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-{{ $job->contract->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $job->contract->status === 'active' ? 'success' : 'secondary' }} px-3 py-2 rounded-pill">
                                {{ ucfirst($job->contract->status) }}
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Freelancer</small>
                            <span class="fw-semibold">{{ $job->contract->freelancer->name }}</span>
                        </div>
                        
                        <div class="mb-4">
                            <small class="text-muted d-block">Started</small>
                            <span>{{ $job->contract->start_date->format('M d, Y') }}</span>
                        </div>
                        
                        <a href="{{ route('contracts.show', $job->contract) }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-eye me-2"></i>View Contract
                        </a>
                    </div>
                </div>
            @endif

            <!-- Client Info Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-person-circle me-2 text-primary"></i>
                        About the Client
                    </h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="text-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-4 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                            <span class="fw-bold text-primary fs-2">{{ strtoupper(substr($job->client->name, 0, 1)) }}</span>
                        </div>
                        <h6 class="fw-semibold mb-1">{{ $job->client->name }}</h6>
                    </div>
                    
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <span class="text-muted">Rating: <strong>5.0</strong></span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-briefcase text-primary"></i>
                            <span class="text-muted">{{ $job->client->jobs_count ?? 0 }} jobs posted</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-check text-success"></i>
                            <span class="text-muted">Member since {{ $job->client->created_at->format('Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

.proposal-item {
    transition: transform 0.2s, box-shadow 0.2s;
}

.proposal-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05) !important;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.05) !important;
}

.btn-success {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    border: none;
    color: #2d3748;
}

.btn-success:hover {
    background: linear-gradient(135deg, #6be39c 0%, #7ec7e8 100%);
    color: #2d3748;
}

/* Responsive */
@media (max-width: 768px) {
    h2 {
        font-size: 1.5rem;
    }
}
</style>
@endsection