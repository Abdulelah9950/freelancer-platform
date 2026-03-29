@extends('layouts.app')

@section('title', 'Submit Proposal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Submit Proposal for: {{ $job->title }}</h5>
                </div>

                <div class="card-body">
                    <!-- Job Summary -->
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>Job Budget:</strong> ${{ number_format($job->budget, 2) }}
                            </div>
                            <div>
                                <strong>Posted by:</strong> {{ $job->client->name }}
                            </div>
                        </div>
                        <p class="mt-2 mb-0 small">{{ Str::limit($job->description, 200) }}</p>
                    </div>

                    <form method="POST" action="{{ route('jobs.proposals.store', $job) }}">
                        @csrf

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
                                       value="{{ old('bid_amount') }}" 
                                       min="1" 
                                       max="{{ $job->budget }}"
                                       required>
                            </div>
                            @error('bid_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Enter your bid amount (maximum: ${{ number_format($job->budget, 2) }})
                            </small>
                        </div>

                        <!-- Cover Letter -->
                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">Cover Letter</label>
                            <textarea class="form-control @error('cover_letter') is-invalid @enderror" 
                                      id="cover_letter" 
                                      name="cover_letter" 
                                      rows="6" 
                                      required>{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Explain why you're the best candidate for this job. Minimum 50 characters.
                            </small>
                        </div>

                        <!-- Tips -->
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">Tips for a successful proposal:</h6>
                            <ul class="mb-0 small">
                                <li>Be specific about your relevant experience</li>
                                <li>Explain how you'll approach the project</li>
                                <li>Highlight similar work you've done</li>
                                <li>Be professional and courteous</li>
                                <li>Proofread your proposal before submitting</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Submit Proposal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection