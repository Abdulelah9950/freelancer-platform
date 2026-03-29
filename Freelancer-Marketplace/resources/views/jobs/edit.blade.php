@extends('layouts.app')

@section('title', 'Edit Job')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>
                            Edit Job
                        </h4>
                        <span class="badge bg-{{ $job->status === 'open' ? 'success' : 'secondary' }} px-3 py-2">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                    <p class="text-muted mt-2">Update your job details below</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('jobs.update', $job) }}">
                        @csrf
                        @method('PUT')

                        <!-- Job Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                <i class="bi bi-briefcase me-2"></i>Job Title
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $job->title) }}" 
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
                                      required>{{ old('description', $job->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                       value="{{ old('budget', $job->budget) }}" 
                                       required>
                            </div>
                            @error('budget')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Status -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-toggle-on me-2"></i>Job Status
                            </label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" 
                                           id="statusOpen" value="open" 
                                           {{ old('status', $job->status) == 'open' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusOpen">
                                        <span class="badge bg-success">Open</span> - Job is accepting proposals
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" 
                                           id="statusClosed" value="closed" 
                                           {{ old('status', $job->status) == 'closed' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusClosed">
                                        <span class="badge bg-secondary">Closed</span> - Job is no longer accepting proposals
                                    </label>
                                </div>
                            </div>
                            @error('status')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Required Skills -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-star-fill text-warning me-2"></i>Required Skills
                            </label>
                            <div class="alert alert-info bg-info bg-opacity-10 border-0 rounded-3 mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Select the skills required for this job. Freelancers with matching skills will be notified.
                            </div>
                            
                            <div class="row g-2">
                                @foreach($skills as $skill)
                                    <div class="col-md-4">
                                        <div class="form-check skill-checkbox">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="skills[]" value="{{ $skill->id }}" 
                                                   id="skill{{ $skill->id }}"
                                                   {{ in_array($skill->id, old('skills', $job->skills->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill{{ $skill->id }}">
                                                {{ $skill->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('skills')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Preview Section -->
                        <div class="card bg-light border-0 rounded-3 mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-eye text-primary me-2"></i>
                                    Preview
                                </h6>
                                <div class="job-preview p-3 bg-white rounded-3">
                                    <h5 id="previewTitle">{{ $job->title }}</h5>
                                    <p id="previewDescription" class="text-muted small">{{ Str::limit($job->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold" id="previewBudget">${{ number_format($job->budget, 2) }}</span>
                                        <span class="badge bg-{{ $job->status === 'open' ? 'success' : 'secondary' }}" id="previewStatus">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-2" id="previewSkills">
                                        @foreach($job->skills as $skill)
                                            <span class="badge bg-light text-dark me-1">{{ $skill->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill py-3">
                                <i class="bi bi-check-circle me-2"></i>Update Job
                            </button>
                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Job Card (for job owners only) -->
            @can('delete', $job)
            <div class="card border-0 shadow-sm rounded-4 mt-4 border-danger border-opacity-25">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-danger mb-1">Delete Job</h5>
                            <p class="text-muted small mb-0">This action cannot be undone</p>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning-emphasis rounded-3">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Deleting this job will also remove all associated proposals.
                    </div>

                    <form method="POST" action="{{ route('jobs.destroy', $job) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this job? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-5 py-2">
                            <i class="bi bi-trash3 me-2"></i>Delete Job
                        </button>
                    </form>
                </div>
            </div>
            @endcan
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
    .job-preview {
        border-left: 4px solid #667eea;
    }
</style>
@endpush

@push('scripts')
<script>
    // Live preview
    document.getElementById('title').addEventListener('input', function() {
        document.getElementById('previewTitle').textContent = this.value || 'Job Title';
    });

    document.getElementById('description').addEventListener('input', function() {
        const desc = this.value;
        document.getElementById('previewDescription').textContent = 
            desc.length > 100 ? desc.substring(0, 100) + '...' : desc || 'No description provided';
    });

    document.getElementById('budget').addEventListener('input', function() {
        const budget = parseFloat(this.value) || 0;
        document.getElementById('previewBudget').textContent = '$' + budget.toFixed(2);
    });

    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const status = this.value;
            const badge = document.getElementById('previewStatus');
            badge.textContent = status === 'open' ? 'Open' : 'Closed';
            badge.className = 'badge ' + (status === 'open' ? 'bg-success' : 'bg-secondary');
        });
    });

    document.querySelectorAll('input[name="skills[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const preview = document.getElementById('previewSkills');
            const selectedSkills = [];
            
            document.querySelectorAll('input[name="skills[]"]:checked').forEach(cb => {
                selectedSkills.push(cb.nextElementSibling.textContent);
            });
            
            preview.innerHTML = selectedSkills.map(skill => 
                `<span class="badge bg-light text-dark me-1">${skill}</span>`
            ).join('');
            
            if (selectedSkills.length === 0) {
                preview.innerHTML = '<span class="text-muted small">No skills selected</span>';
            }
        });
    });
</script>
@endpush