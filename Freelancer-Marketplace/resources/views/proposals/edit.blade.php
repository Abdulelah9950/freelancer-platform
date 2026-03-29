@extends('layouts.app')

@section('title', 'Edit Proposal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Edit Proposal for: {{ $proposal->job->title }}</h5>
                </div>

                <div class="card-body">
                    <!-- Job Summary -->
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>Job Budget:</strong> ${{ number_format($proposal->job->budget, 2) }}
                            </div>
                            <div>
                                <strong>Your Current Bid:</strong> 
                                <span class="text-primary">${{ number_format($proposal->bid_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('jobs.proposals.update', [$proposal->job, $proposal]) }}">
                        @csrf
                        @method('PUT')

                        <!-- Bid Amount -->
                        <div class="mb-3">
                            <label for="bid_amount" class="form-label">Your Bid Amount ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control @error('bid_amount') is-invalid @enderror" 
                                       id="bid_amount" 
                                       name="bid_amount" 
                                       value="{{ old('bid_amount', $proposal->bid_amount) }}" 
                                       min="1" 
                                       max="{{ $proposal->job->budget }}"
                                       required>
                            </div>
                            @error('bid_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Enter your bid amount (maximum: ${{ number_format($proposal->job->budget, 2) }})
                            </small>
                        </div>

                        <!-- Cover Letter -->
                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">Cover Letter</label>
                            <textarea class="form-control @error('cover_letter') is-invalid @enderror" 
                                      id="cover_letter" 
                                      name="cover_letter" 
                                      rows="6" 
                                      required>{{ old('cover_letter', $proposal->cover_letter) }}</textarea>
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Explain why you're the best candidate for this job. Minimum 50 characters.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('jobs.proposals.show', [$proposal->job, $proposal]) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Update Proposal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection