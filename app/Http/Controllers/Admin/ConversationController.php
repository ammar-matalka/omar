<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        // إضافة logging للتشخيص
        Log::info('Admin reply attempt', [
            'admin_id' => Auth::id(),
            'conversation_id' => $conversation->id,
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson(),
            'request_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            $validated = $request->validate([
                'message' => 'required|string|max:2000',
            ]);
            
            Log::info('Admin creating message', ['validated_data' => $validated]);
            
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'message' => $validated['message'],
                'is_from_admin' => true,
            ]);
            
            Log::info('Admin message created', ['message_id' => $message->id]);
            
            $conversation->update([
                'is_read_by_user' => false,
                'is_read_by_admin' => true,
                'updated_at' => now(),
            ]);
            
            Log::info('Admin conversation updated');

            // إذا كان الطلب AJAX، أرجع JSON
            if ($request->expectsJson() || $request->ajax()) {
                $response_data = [
                    'success' => true,
                    'message' => [
                        'id' => $message->id,
                        'message' => $message->message,
                        'is_from_admin' => true,
                        'user_name' => 'Admin',
                        'created_at' => $message->created_at->format('M d, h:i A'),
                        'avatar' => 'admin'
                    ]
                ];
                
                Log::info('Admin returning JSON response', $response_data);
                
                return response()->json($response_data);
            }
            
            return redirect()->route('admin.conversations.show', $conversation)
                ->with('success', 'Reply sent successfully.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Admin validation error in reply', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Admin error in reply method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Internal server error: ' . $e->getMessage()
                ], 500);
            }
            
            throw $e;
        }
    }

    /**
     * تحقق من وجود رسائل جديدة (للأدمن)
     */
    public function checkNewMessages(Request $request, Conversation $conversation)
    {
        try {
            $lastMessageId = $request->input('last_message_id', 0);
            
            $newMessages = $conversation->messages()
                ->where('id', '>', $lastMessageId)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();

            $hasNewMessages = $newMessages->count() > 0;

            return response()->json([
                'has_new_messages' => $hasNewMessages,
                'messages' => $newMessages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'is_from_admin' => $message->is_from_admin,
                        'user_name' => $message->user->name,
                        'created_at' => $message->created_at->format('M d, h:i A'),
                        'avatar' => $message->is_from_admin ? 'admin' : substr($message->user->name, 0, 1)
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            Log::error('Admin error in checkNewMessages', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversation->id
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error checking messages'
            ], 500);
        }
    }

    /**
     * تحديد المحادثة كمقروءة
     */
    public function markAsRead(Conversation $conversation)
    {
        try {
            $conversation->markAsReadByAdmin();

            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Conversation marked as read.');
            
        } catch (\Exception $e) {
            Log::error('Admin error in markAsRead', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversation->id
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error marking as read'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error marking conversation as read.');
        }
    }

    /**
     * تحديد جميع المحادثات كمقروءة
     */
    public function markAllAsRead()
    {
        try {
            Conversation::where('is_read_by_admin', false)
                ->update(['is_read_by_admin' => true]);

            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'All conversations marked as read.');
            
        } catch (\Exception $e) {
            Log::error('Admin error in markAllAsRead', [
                'error' => $e->getMessage()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error marking all as read'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error marking all conversations as read.');
        }
    }

    /**
     * الحصول على عدد المحادثات غير المقروءة
     */
    public function getCounts()
    {
        try {
            $unreadCount = Conversation::unreadByAdmin()->count();
            $totalCount = Conversation::count();

            return response()->json([
                'unread_count' => $unreadCount,
                'total_count' => $totalCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Admin error in getCounts', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error getting counts'
            ], 500);
        }
    }
}   