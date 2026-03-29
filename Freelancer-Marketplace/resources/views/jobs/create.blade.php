@extends('layouts.app')

@section('title', 'Post a New Job')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>
                        Post a New Job
                    </h4>
                    <p class="text-muted mt-2">Fill in the details below to find the perfect freelancer</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('jobs.store') }}">
                        @csrf

                        <!-- Job Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                <i class="bi bi-briefcase me-2"></i>Job Title
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="e.g., Laravel Developer Needed"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-file-text me-2"></i>Job Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="6" 
                                      placeholder="Describe your project requirements, scope, and expectations..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Be detailed to attract the right freelancers</small>
                        </div>

                        <!-- Budget -->
                        <div class="mb-4">
                            <label for="budget" class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar me-2"></i>Budget
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">$</span>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control form-control-lg @error('budget') is-invalid @enderror" 
                                       id="budget" 
                                       name="budget" 
                                       value="{{ old('budget') }}" 
                                       placeholder="1000"
                                       required>
                            </div>
                            @error('budget')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Set a realistic budget for your project</small>
                        </div>

                        <!-- Required Skills -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-star-fill text-warning me-2"></i>Required Skills
                            </label>
                            <div class="alert alert-info bg-info bg-opacity-10 border-0 rounded-3 mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Select the skills required for this job. Freelancers with matching skills will be notified immediately!
                            </div>
                            
                            <div class="row g-2">
                                @foreach($skills as $skill)
                                    <div class="col-md-4">
                                        <div class="form-check skill-checkbox">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="skills[]" value="{{ $skill->id }}" 
                                                   id="skill{{ $skill->id }}"
                                                   {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill{{ $skill->id }}">
                                                {{ $skill->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('skills')
                                <div class="text-danger small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            @error('skills.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notification Preview -->
                        <div class="card bg-light border-0 rounded-3 mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-envelope-paper text-primary me-2"></i>
                                    Freelancer Notification Preview
                                </h6>
                                <p class="small text-muted mb-2">
                                    When you post this job, email notifications will be sent to freelancers who have these skills:
                                </p>
                                <div class="skills-preview" id="skillsPreview">
                                    <span class="badge bg-secondary me-1 mb-1">Select skills to see preview</span>
                                </div>
                                <p class="small text-muted mt-2 mb-0">
                                    <i class="bi bi-clock me-1"></i>Notifications are sent immediately after posting
                                </p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill py-3">
                                <i class="bi bi-send me-2"></i>Post Job & Notify Freelancers
                            </button>
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .skill-checkbox {
        padding: 10px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .skill-checkbox:hover {
        background: #f8f9fa;
    }
    .skill-checkbox input[type="checkbox"]:checked + label {
        color: #667eea;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
    // Live preview of selected skills
    document.querySelectorAll('input[name="skills[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const preview = document.getElementById('skillsPreview');
            const selectedSkills = [];
            
            document.querySelectorAll('input[name="skills[]"]:checked').forEach(cb => {
                selectedSkills.push(cb.nextElementSibling.textContent);
            });
            
            if (selectedSkills.length > 0) {
                preview.innerHTML = selectedSkills.map(skill => 
                    `<span class="badge bg-primary me-1 mb-1">${skill}</span>`
                ).join('');
            } else {
                preview.innerHTML = '<span class="badge bg-secondary me-1 mb-1">No skills selected</span>';
            }
        });
    });
</script>
@endpush