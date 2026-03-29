<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Skill;
use App\Services\JobNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{
    protected $notificationService;

    public function __construct(JobNotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,client')->except(['index', 'show']);
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
      $query = Job::query();
    
    // Search filter
    if ($request->has('search') && $request->search != '') {
        $query->where('title', 'like', '%' . $request->search . '%');
    }
    
    // Budget range filter
    if ($request->has('budget_range') && $request->budget_range != '') {
        $range = explode('-', $request->budget_range);
        if (count($range) == 2) {
            $query->whereBetween('budget', [$range[0], $range[1]]);
        } elseif ($request->budget_range == '10000+') {
            $query->where('budget', '>=', 10000);
        }
    }
    
    // Sort options
    switch ($request->sort) {
        case 'budget_high':
            $query->orderBy('budget', 'desc');
            break;
        case 'budget_low':
            $query->orderBy('budget', 'asc');
            break;
        case 'most_proposals':
            $query->withCount('proposals')->orderBy('proposals_count', 'desc');
            break;
        default:
            $query->latest();
    }
    
    $jobs = $query->paginate(12);
    
    return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Job::class);
        $skills = Skill::all();
        return view('jobs.create', compact('skills'));
    }

/**
 * Store a newly created job.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function store(Request $request)
{
    $this->authorize('create', Job::class);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'budget' => 'required|numeric|min:0',
        'skills' => 'required|array|min:1',
        'skills.*' => 'exists:skills,id'
    ]);

    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    $job = $user->jobs()->create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'budget' => $validated['budget'],
        'status' => 'open'
    ]);

    // Attach skills to the job
    $job->skills()->attach($validated['skills']);
    
    // Reload the job with skills
    $job->load('skills');

    // Send webhook to n8n (optional - can be commented out if n8n is not running)
    // $this->sendWebhook($job);

    // Send email notifications to matching freelancers
    try {
        $this->notificationService->notifyMatchingFreelancers($job);
        
        // Get count of matching freelancers for success message
        $matchingCount = $this->notificationService->getMatchingFreelancers($job)->count();
        
        if ($matchingCount > 0) {
            return redirect()->route('jobs.show', $job)
                ->with('success', "Job posted successfully! Notifications sent to {$matchingCount} freelancers with matching skills.");
        }
    } catch (\Exception $e) {
        Log::error('Failed to send job notifications', [
            'job_id' => $job->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    return redirect()->route('jobs.show', $job)
        ->with('success', 'Job posted successfully!');
}

    /**
     * Display the specified job.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function show(Job $job)
    {
        $job->load(['client', 'proposals.freelancer', 'skills']);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->isFreelancer()) {
            $userProposal = $job->proposals()
                ->where('freelancer_id', Auth::id())
                ->first();
            return view('jobs.show', compact('job', 'userProposal'));
        }

        // For clients, show matching freelancers count
        $matchingFreelancersCount = 0;
        if ($user->isClient() && $user->id === $job->client_id) {
            $matchingFreelancersCount = app(JobNotificationService::class)
                ->getMatchingFreelancers($job)
                ->count();
        }

        return view('jobs.show', compact('job', 'matchingFreelancersCount'));
    }

    /**
     * Show the form for editing the specified job.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function edit(Job $job)
    {
        $this->authorize('update', $job);
        $job->load('skills');
        $skills = Skill::all();
        return view('jobs.edit', compact('job', 'skills'));
    }

    /**
     * Update the specified job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'status' => 'sometimes|in:open,closed',
            'skills' => 'sometimes|array|exists:skills,id'
        ]);

        $job->update($validated);

        // Sync skills if provided
        if ($request->has('skills')) {
            $job->skills()->sync($request->skills);
        }

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified job.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);
        
        $job->delete();

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted successfully!');
    }

    /**
     * Send webhook notification to n8n.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    private function sendWebhook($job)
    {
        try {
            Http::post('http://localhost:5678/webhook/new-job', [
                'job_id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'budget' => $job->budget,
                'client_name' => $job->client->name,
                'client_email' => $job->client->email,
                'required_skills' => $job->skills->pluck('name')->toArray(),
                'created_at' => $job->created_at->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            Log::error('Webhook failed: ' . $e->getMessage());
        }
    }
    
}