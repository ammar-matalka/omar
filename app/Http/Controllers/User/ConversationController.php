<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        return view('user.conversations.index', compact('conversations'));
    }
    
    public function create()
    {
        return view('user.conversations.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        $conversation = Conversation::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'is_read_by_user' => true,
            'is_read_by_admin' => false,
        ]);
        
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_from_admin' => false,
        ]);
        
        return redirect()->route('user.conversations.show', $conversation)
            ->with('success', 'Conversation started successfully.');
    }
    
    public function show(Conversation $conversation)
    {
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Mark as read
        $conversation->update(['is_read_by_user' => true]);
        
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        
        return view('user.conversations.show', compact('conversation', 'messages'));
    }
    
    public function reply(Request $request, Conversation $conversation)
    {
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_from_admin' => false,
        ]);
        
        $conversation->update([
            'is_read_by_user' => true,
            'is_read_by_admin' => false,
            'updated_at' => now(),
        ]);
        
        return redirect()->route('user.conversations.show', $conversation)
            ->with('success', 'Reply sent successfully.');
    }
}