<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Proposal;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'client') {
            return $this->clientDashboard();
        } else {
            return $this->freelancerDashboard();
        }
    }

    /**
     * Admin dashboard with statistics.
     *
     * @return \Illuminate\View\View
     */
    private function adminDashboard(): View
    {
        $statistics = [
            'total_users' => User::count(),
            'total_freelancers' => User::where('role', 'freelancer')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_jobs' => Job::count(),
            'total_proposals' => Proposal::count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentJobs = Job::with('client')->latest()->take(5)->get();

        return view('dashboard.admin', compact('statistics', 'recentUsers', 'recentJobs'));
    }

    /**
     * Client dashboard with their jobs and proposals.
     *
     * @return \Illuminate\View\View
     */
    private function clientDashboard(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get jobs directly from the jobs table instead of relationship
        $jobs = Job::where('client_id', $user->id)
            ->withCount('proposals')
            ->latest()
            ->paginate(10);

        $recentProposals = Proposal::whereIn('job_id', function($query) use ($user) {
                $query->select('id')
                    ->from('jobs')
                    ->where('client_id', $user->id);
            })
            ->with(['job', 'freelancer'])
            ->latest()
            ->take(5)
            ->get();

        $activeContracts = Contract::where('client_id', $user->id)
            ->where('status', 'active')
            ->with(['job', 'freelancer'])
            ->get();

        return view('dashboard.client', compact('jobs', 'recentProposals', 'activeContracts'));
    }

    /**
     * Freelancer dashboard with available jobs and their proposals.
     *
     * @return \Illuminate\View\View
     */
/**
 * Freelancer dashboard with available jobs and their proposals.
 *
 * @return \Illuminate\View\View
 */
private function freelancerDashboard(): View
{
    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    $availableJobs = Job::where('status', 'open')
        ->whereDoesntHave('contract')
        ->latest()
        ->paginate(10);

    $submittedProposals = Proposal::where('freelancer_id', $user->id)
        ->with(['job' => function($query) {
            $query->withTrashed(); // Include soft deleted jobs
        }])
        ->latest()
        ->paginate(10);

    $assignedContracts = Contract::where('freelancer_id', $user->id)
        ->with(['job' => function($query) {
            $query->withTrashed(); // Include soft deleted jobs
        }, 'client'])
        ->latest()
        ->get();

    return view('dashboard.freelancer', compact('availableJobs', 'submittedProposals', 'assignedContracts'));
}
}