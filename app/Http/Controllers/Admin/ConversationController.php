<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with(['user', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        return view('admin.conversations.index', compact('conversations'));
    }
    
    public function show(Conversation $conversation)
    {
        // Mark as read
        $conversation->update(['is_read_by_admin' => true]);
        
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        
        return view('admin.conversations.show', compact('conversation', 'messages'));
    }
    
    public function reply(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_from_admin' => true,
        ]);
        
        $conversation->update([
            'is_read_by_user' => false,
            'is_read_by_admin' => true,
            'updated_at' => now(),
        ]);
        
        return redirect()->route('admin.conversations.show', $conversation)
            ->with('success', 'Reply sent successfully.');
    }
}