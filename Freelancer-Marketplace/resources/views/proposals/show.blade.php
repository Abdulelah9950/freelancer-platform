@extends('layouts.app')

@section('title', 'Proposal Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <!-- Proposal Details -->
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Proposal Details</h5>
                    <span class="badge bg-{{ $proposal->status === 'pending' ? 'warning' : ($proposal->status === 'accepted' ? 'success' : 'danger') }} p-2">
                        {{ ucfirst($proposal->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Job Title</h6>
                        <h5>
                            <a href="{{ route('jobs.show', $proposal->job) }}" class="text-decoration-none">
                                {{ $proposal->job->title }}
                            </a>
                        </h5>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Client</h6>
                            <p>{{ $proposal->job->client->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Job Budget</h6>
                            <p class="h5 text-primary">${{ number_format($proposal->job->budget, 2) }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">My Bid Amount</h6>
                        <p class="h4 text-success">${{ number_format($proposal->bid_amount, 2) }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Cover Letter</h6>
                        <div class="p-3 bg-light rounded">
                            {{ $proposal->cover_letter }}
                        </div>
                    </div>

                    <div class="text-muted small">
                        Submitted on {{ $proposal->created_at->format('F j, Y \a\t g:i A') }}
                        @if($proposal->updated_at != $proposal->created_at)
                            <br>Last updated on {{ $proposal->updated_at->format('F j, Y \a\t g:i A') }}
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('proposals.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Proposals
                        </a>
                        @if($proposal->status === 'pending')
                            <a href="{{ route('jobs.proposals.edit', [$proposal->job, $proposal]) }}" 
                               class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit Proposal
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Job Summary Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Job Summary</h6>
                </div>
                <div class="card-body">
                    <h6>{{ $proposal->job->title }}</h6>
                    <p class="small text-muted mb-2">{{ Str::limit($proposal->job->description, 100) }}</p>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Budget:</span>
                        <span class="fw-bold">${{ number_format($proposal->job->budget, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-{{ $proposal->job->status === 'open' ? 'success' : 'secondary' }}">
                            {{ ucfirst($proposal->job->status) }}
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Posted:</span>
                        <span>{{ $proposal->job->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <hr>
                    
                    <a href="{{ route('jobs.show', $proposal->job) }}" class="btn btn-outline-primary w-100">
                        View Full Job Details
                    </a>
                </div>
            </div>

            <!-- Client Info Card -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Client Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px; font-size: 20px;">
                            {{ strtoupper(substr($proposal->job->client->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $proposal->job->client->name }}</h6>
                            <small class="text-muted">Member since {{ $proposal->job->client->created_at->format('M Y') }}</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Jobs Posted:</span>
                        <span>{{ $proposal->job->client->jobs()->count() }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Hired Freelancers:</span>
                        <span>{{ $proposal->job->client->clientContracts()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection