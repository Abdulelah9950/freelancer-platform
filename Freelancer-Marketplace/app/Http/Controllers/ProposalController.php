<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Proposal;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProposalController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of proposals.
     *
     * @return \Illuminate\View\View
     */
   public function index()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    if ($user->isFreelancer()) {
        $proposals = $user->proposals()
            ->with(['job' => function($query) {
                $query->withTrashed()->with('client');
            }])
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);
    } else {
        $proposals = Proposal::whereHas('job', function($query) use ($user) {
                $query->where('client_id', $user->id);
            })
            ->with(['job' => function($query) {
                $query->withTrashed();
            }, 'freelancer'])
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);
    }

    return view('proposals.index', compact('proposals'));
}

    /**
     * Show the form for creating a new proposal.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function create(Job $job)
    {
        // Manual authorization
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isFreelancer()) {
            abort(403, 'Only freelancers can submit proposals.');
        }

        if ($job->status !== 'open') {
            abort(403, 'This job is no longer accepting proposals.');
        }

        $existingProposal = Proposal::where('job_id', $job->id)
            ->where('freelancer_id', $user->id)
            ->exists();

        if ($existingProposal) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'You have already submitted a proposal for this job.');
        }
        
        return view('proposals.create', compact('job'));
    }

    /**
     * Store a newly created proposal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Job $job)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Manual validation
        if (!$user->isFreelancer()) {
            abort(403, 'Only freelancers can submit proposals.');
        }

        if ($job->status !== 'open') {
            abort(403, 'This job is no longer accepting proposals.');
        }

        $existingProposal = Proposal::where('job_id', $job->id)
            ->where('freelancer_id', $user->id)
            ->exists();

        if ($existingProposal) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'You have already submitted a proposal for this job.');
        }

        $validated = $request->validate([
            'bid_amount' => 'required|numeric|min:1|max:' . $job->budget,
            'cover_letter' => 'required|string|min:50'
        ]);

        $proposal = Proposal::create([
            'job_id' => $job->id,
            'freelancer_id' => $user->id,
            'bid_amount' => $validated['bid_amount'],
            'cover_letter' => $validated['cover_letter'],
            'status' => 'pending'
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Proposal submitted successfully!');
    }

    /**
     * Display the specified proposal.
     *
     * @param  \App\Models\Job  $job
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\View\View
     */
    public function show(Job $job, Proposal $proposal)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify the proposal belongs to the job
        if ($proposal->job_id !== $job->id) {
            abort(404, 'Proposal not found for this job.');
        }

        // Manual authorization
        if (!$user->isAdmin() && 
            !($user->isFreelancer() && $user->id === $proposal->freelancer_id) &&
            !($user->isClient() && $user->id === $job->client_id)) {
            abort(403, 'Unauthorized access.');
        }
        
        $proposal->load(['job.client', 'freelancer']);
        return view('proposals.show', compact('proposal', 'job'));
    }

    /**
     * Show the form for editing the specified proposal.
     *
     * @param  \App\Models\Job  $job
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Job $job, Proposal $proposal)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify the proposal belongs to the job
        if ($proposal->job_id !== $job->id) {
            abort(404, 'Proposal not found for this job.');
        }

        // Manual authorization
        if (!$user->isFreelancer() || $user->id !== $proposal->freelancer_id) {
            abort(403, 'You can only edit your own proposals.');
        }

        if ($proposal->status !== 'pending') {
            return redirect()->route('jobs.proposals.show', [$job, $proposal])
                ->with('error', 'You can only edit pending proposals.');
        }

        if ($job->status !== 'open') {
            return redirect()->route('jobs.proposals.show', [$job, $proposal])
                ->with('error', 'This job is no longer open for proposals.');
        }
        
        return view('proposals.edit', compact('proposal', 'job'));
    }

    /**
     * Update the specified proposal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Job $job, Proposal $proposal)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify the proposal belongs to the job
        if ($proposal->job_id !== $job->id) {
            abort(404, 'Proposal not found for this job.');
        }

        // Manual authorization
        if (!$user->isFreelancer() || $user->id !== $proposal->freelancer_id) {
            abort(403, 'You can only edit your own proposals.');
        }

        if ($proposal->status !== 'pending') {
            return redirect()->route('jobs.proposals.show', [$job, $proposal])
                ->with('error', 'You can only edit pending proposals.');
        }

        if ($job->status !== 'open') {
            return redirect()->route('jobs.proposals.show', [$job, $proposal])
                ->with('error', 'This job is no longer open for proposals.');
        }

        $validated = $request->validate([
            'bid_amount' => 'required|numeric|min:1|max:' . $job->budget,
            'cover_letter' => 'required|string|min:50'
        ]);

        $proposal->update($validated);

        return redirect()->route('jobs.proposals.show', [$job, $proposal])
            ->with('success', 'Proposal updated successfully!');
    }

    /**
     * Accept a proposal and create a contract.
     *
     * @param  \App\Models\Job  $job
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Job $job, Proposal $proposal)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify the proposal belongs to the job
        if ($proposal->job_id !== $job->id) {
            abort(404, 'Proposal not found for this job.');
        }

        // Manual authorization
        if (!$user->isClient() || $user->id !== $job->client_id) {
            abort(403, 'Only the job owner can accept proposals.');
        }

        if ($proposal->status !== 'pending') {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'This proposal cannot be accepted.');
        }

        if ($job->status !== 'open') {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'This job is no longer open.');
        }

        // Update proposal status
        $proposal->update(['status' => 'accepted']);
        
        // Create contract
        $contract = Contract::create([
            'job_id' => $job->id,
            'client_id' => $job->client_id,
            'freelancer_id' => $proposal->freelancer_id,
            'start_date' => now(),
            'status' => 'active'
        ]);

        // Update job status
        $job->update(['status' => 'closed']);

        // Reject other proposals
        $job->proposals()
            ->where('id', '!=', $proposal->id)
            ->update(['status' => 'rejected']);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Proposal accepted and contract created!');
    }

    /**
     * Reject a proposal.
     *
     * @param  \App\Models\Job  $job
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Job $job, Proposal $proposal)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify the proposal belongs to the job
        if ($proposal->job_id !== $job->id) {
            abort(404, 'Proposal not found for this job.');
        }

        // Manual authorization
        if (!$user->isClient() || $user->id !== $job->client_id) {
            abort(403, 'Only the job owner can reject proposals.');
        }

        if ($proposal->status !== 'pending') {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'This proposal cannot be rejected.');
        }

        $proposal->update(['status' => 'rejected']);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Proposal rejected.');
    }

    /**
     * Delete the specified proposal.
     *
     * @param  \App\Models\Job  $job
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Job $job, Proposal $proposal)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify the proposal belongs to the job
        if ($proposal->job_id !== $job->id) {
            abort(404, 'Proposal not found for this job.');
        }

        // Manual authorization
        if (!$user->isFreelancer() || $user->id !== $proposal->freelancer_id) {
            abort(403, 'You can only delete your own proposals.');
        }

        if ($proposal->status !== 'pending') {
            return redirect()->route('jobs.proposals.show', [$job, $proposal])
                ->with('error', 'You can only delete pending proposals.');
        }

        $proposal->delete();

        return redirect()->route('proposals.index')
            ->with('success', 'Proposal deleted successfully!');
    }
}