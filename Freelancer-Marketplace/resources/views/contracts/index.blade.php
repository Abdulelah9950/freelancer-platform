@extends('layouts.app')

@section('title', 'Contracts')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-2" style="color: #2d3748;">
                <i class="bi bi-file-text me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                Contracts
            </h1>
            <p class="text-muted fs-5">Manage and track all your work contracts</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="btn-group" role="group">
                <a href="{{ route('contracts.index') }}" class="btn btn-primary-modern {{ !request('status') ? 'active' : '' }}">
                    All
                </a>
                <a href="{{ route('contracts.index', ['status' => 'active']) }}" class="btn btn-outline-modern {{ request('status') == 'active' ? 'active' : '' }}">
                    Active
                </a>
                <a href="{{ route('contracts.index', ['status' => 'completed']) }}" class="btn btn-outline-modern {{ request('status') == 'completed' ? 'active' : '' }}">
                    Completed
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="stat-number">{{ $contracts->total() }}</div>
                <div class="stat-label">Total Contracts</div>
                <div class="mt-3">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i> {{ $contracts->where('status', 'active')->count() }} Active
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-number">{{ $contracts->where('status', 'active')->count() }}</div>
                <div class="stat-label">In Progress</div>
                <div class="mt-3">
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                        <i class="bi bi-hourglass me-1"></i> {{ $contracts->where('status', 'active')->count() }} Active
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div class="stat-number">{{ $contracts->where('status', 'completed')->count() }}</div>
                <div class="stat-label">Completed</div>
                <div class="mt-3">
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                        <i class="bi bi-star me-1"></i> Success Rate
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contracts List -->
    <div class="dashboard-card">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2" style="color: #667eea;"></i>
                All Contracts
            </h5>
            <div class="d-flex gap-2">
                <form action="{{ route('contracts.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search contracts..." value="{{ request('search') }}" style="border-radius: 50px 0 0 50px; border-right: none;">
                    <button type="submit" class="btn btn-sm btn-primary-modern" style="border-radius: 0 50px 50px 0;">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            @forelse($contracts as $contract)
                <div class="job-item px-4">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <a href="{{ route('contracts.show', $contract) }}" class="job-title fs-5">
                                    {{ $contract->job->title }}
                                </a>
                                <span class="badge-modern badge-{{ $contract->status === 'active' ? 'accepted' : ($contract->status === 'completed' ? 'open' : 'pending') }}">
                                    {{ ucfirst($contract->status) }}
                                </span>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-4 mb-2">
                                @if(auth()->user()->isClient() || auth()->user()->isAdmin())
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                            <span class="small fw-bold">{{ strtoupper(substr($contract->freelancer->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Freelancer</small>
                                            <span class="fw-medium">{{ $contract->freelancer->name }}</span>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(auth()->user()->isFreelancer() || auth()->user()->isAdmin())
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                            <span class="small fw-bold">{{ strtoupper(substr($contract->client->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Client</small>
                                            <span class="fw-medium">{{ $contract->client->name }}</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar3 text-muted me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Started</small>
                                        <span>{{ $contract->start_date->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                
                                @if($contract->end_date)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-check text-muted me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Ended</small>
                                            <span>{{ $contract->end_date->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-primary fw-bold">
                                    Budget: ${{ number_format($contract->job->budget, 2) }}
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-clock me-1"></i> 
                                    {{ $contract->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center justify-content-lg-end mt-3 mt-lg-0">
                            <div class="d-flex gap-2">
                                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-primary-modern btn-sm">
                                    <i class="bi bi-eye me-1"></i> View Details
                                </a>
                                @if(auth()->user()->isClient() && $contract->status === 'active')
                                    <button type="button" class="btn btn-outline-modern btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $contract->id }}">
                                        <i class="bi bi-pencil me-1"></i> Update
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Status Modal -->
                @if(auth()->user()->isClient() && $contract->status === 'active')
                <div class="modal fade" id="updateStatusModal{{ $contract->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Contract Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('contracts.update', $contract) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="active" {{ $contract->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="completed" {{ $contract->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="terminated" {{ $contract->status === 'terminated' ? 'selected' : '' }}>Terminated</option>
                                        </select>
                                    </div>
                                    @if($contract->status === 'active')
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date (if completed)</label>
                                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{ $contract->end_date ? $contract->end_date->format('Y-m-d') : '' }}">
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary-modern">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @empty
                <div class="empty-state">
                    <i class="bi bi-file-text"></i>
                    <h6 class="mt-3">No contracts found</h6>
                    <p class="text-muted small">
                        @if(auth()->user()->isFreelancer())
                            When your proposals are accepted, contracts will appear here.
                        @elseif(auth()->user()->isClient())
                            When you accept proposals, contracts will be created automatically.
                        @else
                            No contracts have been created yet.
                        @endif
                    </p>
                    @if(auth()->user()->isClient())
                        <a href="{{ route('jobs.create') }}" class="btn btn-primary-modern btn-sm mt-2">
                            <i class="bi bi-plus-circle me-2"></i>Post a Job
                        </a>
                    @elseif(auth()->user()->isFreelancer())
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary-modern btn-sm mt-2">
                            <i class="bi bi-search me-2"></i>Browse Jobs
                        </a>
                    @endif
                </div>
            @endforelse

            <!-- Pagination -->
            @if($contracts->hasPages())
                <div class="p-4 border-top">
                    {{ $contracts->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-group .btn {
        padding: 8px 20px;
        font-weight: 500;
    }
    
    .btn-group .btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }
    
    .modal-content {
        border: none;
        border-radius: 20px;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 20px;
    }
    
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .modal-footer {
        padding: 20px;
        border-top: 1px solid #edf2f7;
    }
    
    .form-select, .form-control {
        border-radius: 10px;
        border: 2px solid #edf2f7;
        padding: 10px 15px;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #667eea;
        box-shadow: none;
    }
</style>
@endpush