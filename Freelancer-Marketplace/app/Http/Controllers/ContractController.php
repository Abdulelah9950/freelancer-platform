<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of contracts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Base query based on user role
        if ($user->isAdmin()) {
            $query = Contract::with(['job', 'client', 'freelancer']);
        } elseif ($user->isClient()) {
            $query = Contract::where('client_id', $user->id)
                ->with(['job', 'freelancer']);
        } else {
            $query = Contract::where('freelancer_id', $user->id)
                ->with(['job', 'client']);
        }

        // Apply status filter if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Apply search if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('job', function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }

        // Get paginated results
        $contracts = $query->latest()->paginate(10);

        return view('contracts.index', compact('contracts'));
    }

    /**
     * Display the specified contract.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\View\View
     */
    public function show(Contract $contract)
    {
        $this->authorize('view', $contract);
        
        $contract->load(['job', 'client', 'freelancer']);
        return view('contracts.show', compact('contract'));
    }

    /**
     * Update the specified contract.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contract $contract)
    {
        $this->authorize('update', $contract);

        $validated = $request->validate([
            'status' => 'required|in:active,completed,terminated',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        $contract->update($validated);

        if ($validated['status'] === 'completed') {
            $contract->job->update(['status' => 'closed']);
        }

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contract updated successfully!');
    }
}