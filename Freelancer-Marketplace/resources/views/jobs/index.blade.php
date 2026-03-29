@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                    <i class="bi bi-briefcase fs-1 text-primary"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-2">Browse Jobs</h1>
                    <p class="text-muted fs-5 mb-0">Find the perfect project that matches your skills</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            @can('create', App\Models\Job::class)
                <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Post New Job
                </a>
            @endcan
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="{{ route('jobs.index') }}" method="GET" id="filter-form">
                <div class="row g-3">
                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                   placeholder="Search by job title or keywords..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                Search
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="d-flex gap-2 justify-content-lg-end">
                            <select name="sort" class="form-select w-auto" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="budget_high" {{ request('sort') == 'budget_high' ? 'selected' : '' }}>Budget: High to Low</option>
                                <option value="budget_low" {{ request('sort') == 'budget_low' ? 'selected' : '' }}>Budget: Low to High</option>
                                <option value="most_proposals" {{ request('sort') == 'most_proposals' ? 'selected' : '' }}>Most Proposals</option>
                            </select>
                            <select name="budget_range" class="form-select w-auto" onchange="this.form.submit()">
                                <option value="">All Budgets</option>
                                <option value="0-1000" {{ request('budget_range') == '0-1000' ? 'selected' : '' }}>Under $1,000</option>
                                <option value="1000-5000" {{ request('budget_range') == '1000-5000' ? 'selected' : '' }}>$1,000 - $5,000</option>
                                <option value="5000-10000" {{ request('budget_range') == '5000-10000' ? 'selected' : '' }}>$5,000 - $10,000</option>
                                <option value="10000+" {{ request('budget_range') == '10000+' ? 'selected' : '' }}>$10,000+</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Filters -->
    @if(request()->anyFilled(['search', 'budget_range', 'sort']) && request('sort') != 'latest')
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <span class="text-muted me-2">Active filters:</span>
            
            @if(request('search'))
                <span class="badge bg-primary d-inline-flex align-items-center p-2">
                    Search: "{{ request('search') }}"
                    <a href="{{ route('jobs.index', array_merge(request()->except(['search', 'page']), ['search' => null])) }}" class="text-white ms-2">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
            @endif
            
            @if(request('budget_range'))
                <span class="badge bg-primary d-inline-flex align-items-center p-2">
                    Budget: 
                    @php
                        $budgetLabels = [
                            '0-1000' => 'Under $1,000',
                            '1000-5000' => '$1,000 - $5,000',
                            '5000-10000' => '$5,000 - $10,000',
                            '10000+' => '$10,000+'
                        ];
                    @endphp
                    {{ $budgetLabels[request('budget_range')] ?? request('budget_range') }}
                    <a href="{{ route('jobs.index', array_merge(request()->except(['budget_range', 'page']), ['budget_range' => null])) }}" class="text-white ms-2">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
            @endif
            
            @if(request('sort') && request('sort') != 'latest')
                <span class="badge bg-primary d-inline-flex align-items-center p-2">
                    Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}
                    <a href="{{ route('jobs.index', array_merge(request()->except(['sort', 'page']), ['sort' => 'latest'])) }}" class="text-white ms-2">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
            @endif
            
            <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Clear All
            </a>
        </div>
    @endif

    <!-- Jobs Grid -->
    <div class="row g-4">
        @forelse($jobs as $job)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm job-card">
                    <div class="card-body p-4">
                        <!-- Header -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <span class="fw-bold text-primary">{{ strtoupper(substr($job->client->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $job->client->name }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $job->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-{{ $job->status === 'open' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $job->status === 'open' ? 'success' : 'secondary' }} rounded-pill">
                                <span class="status-dot me-1"></span>
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>

                        <!-- Title -->
                        <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none text-dark">
                            <h5 class="fw-bold mb-2 job-title">{{ $job->title }}</h5>
                        </a>
                        
                        <!-- Description -->
                        <p class="text-muted small mb-3">{{ Str::limit($job->description, 120) }}</p>

                        <!-- Skills -->
                        @php
                            $skills = [];
                            if ($job->skills) {
                                $decoded = json_decode($job->skills, true);
                                if (is_array($decoded)) {
                                    $skills = isset($decoded[0]['name']) ? array_column($decoded, 'name') : $decoded;
                                } elseif (is_string($job->skills)) {
                                    $skills = explode(',', $job->skills);
                                }
                            }
                            $skills = array_slice($skills, 0, 3);
                        @endphp
                        
                        @if(!empty($skills))
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @foreach($skills as $skill)
                                    <span class="badge bg-light text-dark rounded-pill">{{ trim($skill) }}</span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="d-flex gap-3 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cash-stack text-success me-1"></i>
                                <span class="fw-bold text-success">${{ number_format($job->budget, 0) }}</span>
                                <small class="text-muted ms-1">budget</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-people text-primary me-1"></i>
                                <span>{{ $job->proposals_count ?? $job->proposals()->count() }}</span>
                                <small class="text-muted ms-1">proposals</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-hourglass-split text-warning me-1"></i>
                                <span>{{ $job->duration ?? '30' }}</span>
                                <small class="text-muted ms-1">days</small>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= ($job->client->rating ?? 5) ? '-fill' : '' }} text-warning small"></i>
                                @endfor
                                <small class="text-muted ms-1">({{ $job->client->reviews_count ?? 0 }})</small>
                            </div>
                            
                            @if(auth()->user() && auth()->user()->isFreelancer() && $job->status === 'open')
                                <a href="{{ route('jobs.proposals.create', $job) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-send me-1"></i>Apply Now
                                </a>
                            @else
                                <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-primary btn-sm">
                                    View Details <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="bg-light d-inline-flex rounded-circle p-4 mb-4">
                            <i class="bi bi-briefcase fs-1 text-muted"></i>
                        </div>
                        <h4 class="fw-bold mb-3">No jobs found</h4>
                        <p class="text-muted mb-4">Try adjusting your search or filter to find what you're looking for.</p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat me-2"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jobs->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $jobs->withQueryString()->links() }}
        </div>
    @endif
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

.job-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.job-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important;
}

.job-title:hover {
    color: #667eea !important;
}

/* Pagination styling */
.pagination .page-link {
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    color: #6c757d;
    margin: 0 0.25rem;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.pagination .page-link:hover {
    background: #f8f9fa;
    color: #667eea;
}

/* Responsive */
@media (max-width: 768px) {
    .job-card .d-flex.gap-3 {
        flex-wrap: wrap;
        gap: 0.5rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit forms when selects change
    document.querySelectorAll('select[onchange="this.form.submit()"]').forEach(select => {
        select.removeAttribute('onchange');
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endsection