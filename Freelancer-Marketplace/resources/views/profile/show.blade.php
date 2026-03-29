@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Profile Header Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="bg-gradient-primary text-white p-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center">
                        <div class="position-relative">
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg" 
                                 style="width: 120px; height: 120px; font-size: 48px; font-weight: 600; border: 4px solid rgba(255,255,255,0.3);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @if($user->email_verified_at)
                                <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2 border border-2 border-white" 
                                     style="width: 25px; height: 25px;" 
                                     data-bs-toggle="tooltip" 
                                     title="Verified Email">
                                    <i class="bi bi-check text-white" style="font-size: 16px; margin-left: -4px; margin-top: -4px;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ms-4">
                            <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                            <p class="mb-2 opacity-75"><i class="bi bi-envelope me-2"></i>{{ $user->email }}</p>
                            <span class="badge bg-white text-primary px-4 py-2 rounded-pill fw-semibold">
                                <i class="bi bi-{{ $user->role === 'freelancer' ? 'person-workspace' : ($user->role === 'client' ? 'briefcase' : 'shield') }} me-2"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Skills Section (Only for Freelancers) -->
                    @if($user->isFreelancer())
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-star-fill text-warning me-2"></i>Skills & Expertise
                                </h5>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#skillsModal">
                                    <i class="bi bi-pencil-square me-1"></i>Manage Skills
                                </button>
                            </div>
                            <div class="skills-container">
                                @forelse($user->skills as $skill)
                                    <span class="badge bg-light text-dark border rounded-pill px-4 py-2 me-2 mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-1"></i>{{ $skill->name }}
                                    </span>
                                @empty
                                    <p class="text-muted bg-light p-4 rounded-3 text-center">
                                        <i class="bi bi-info-circle me-2"></i>No skills added yet
                                    </p>
                                @endforelse
                            </div>
                        </div>
                        <hr class="my-4">
                    @endif

                    <!-- Statistics Grid -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded-3">
                                <small class="text-muted text-uppercase fw-semibold">Member Since</small>
                                <h4 class="fw-bold mb-0">{{ $user->created_at->format('F j, Y') }}</h4>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded-3">
                                <small class="text-muted text-uppercase fw-semibold">Last Updated</small>
                                <h4 class="fw-bold mb-0">{{ $user->updated_at->format('F j, Y') }}</h4>
                                <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Role-based Statistics -->
                    @if($user->isFreelancer())
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-primary mb-1">{{ $user->proposals()->count() }}</h3>
                                    <small class="text-muted">Total Proposals</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-success mb-1">{{ $user->proposals()->where('status', 'accepted')->count() }}</h3>
                                    <small class="text-muted">Accepted</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-info mb-1">{{ $user->freelancerContracts()->where('status', 'active')->count() }}</h3>
                                    <small class="text-muted">Active Contracts</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($user->isClient())
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-primary mb-1">{{ $user->jobs()->count() }}</h3>
                                    <small class="text-muted">Jobs Posted</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-success mb-1">{{ $user->jobs()->where('status', 'open')->count() }}</h3>
                                    <small class="text-muted">Open Jobs</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded-3 p-3 text-center">
                                    <h3 class="fw-bold text-info mb-1">{{ $user->clientContracts()->where('status', 'active')->count() }}</h3>
                                    <small class="text-muted">Active Contracts</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr class="my-4">

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill px-4 py-2">
                            <i class="bi bi-pencil-square me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skills Modal (for freelancers) -->
@if($user->isFreelancer())
<div class="modal fade" id="skillsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-star-fill text-warning me-2"></i>Manage Your Skills
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.skills.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-muted mb-3">Select the skills you want to showcase:</p>
                    <div class="row g-2">
                        @foreach($skills ?? [] as $skill)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="skills[]" value="{{ $skill->id }}" 
                                           id="skill{{ $skill->id }}"
                                           {{ $user->skills->contains($skill->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="skill{{ $skill->id }}">
                                        {{ $skill->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Skills</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
@endsection