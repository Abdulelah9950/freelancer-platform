<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'contract'])
            ->latest()
            ->paginate(20);

        return view('messages.index', compact('messages'));
    }

    public function create(User $user, Contract $contract = null)
    {
        return view('messages.create', compact('user', 'contract'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'message' => 'required|string|max:1000'
        ]);

        $validated['sender_id'] = Auth::id();

        Message::create($validated);

        return redirect()->route('messages.index')
            ->with('success', 'Message sent successfully!');
    }

    public function show(Message $message)
    {
        $user = Auth::user();
        
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            abort(403);
        }

        if ($message->receiver_id === $user->id && !$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('messages.show', compact('message'));
    }

    public function conversation(User $user)
    {
        $currentUser = Auth::user();
        
        $messages = Message::where(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $user->id);
            })->orWhere(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $currentUser->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.conversation', compact('messages', 'user'));
    }
}