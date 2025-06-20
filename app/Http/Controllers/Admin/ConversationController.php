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
    /**
     * عرض قائمة المحادثات
     */
    public function index()
    {
        $conversations = Conversation::with(['user', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        return view('admin.conversations.index', compact('conversations'));
    }
    
    /**
     * عرض تفاصيل المحادثة
     */
    public function show(Conversation $conversation)
    {
        // تحديد كمقروءة
        $conversation->update(['is_read_by_admin' => true]);
        
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        
        return view('admin.conversations.show', compact('conversation', 'messages'));
    }
    
    /**
     * الرد على المحادثة
     */
    public function reply(Request $request, Conversation $conversation)
    {
        // إضافة logging للتشخيص
        Log::info('💬 محاولة رد من المدير', [
            'admin_id' => Auth::id(),
            'conversation_id' => $conversation->id,
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson(),
            'content_type' => $request->header('Content-Type'),
            'user_agent' => $request->header('User-Agent')
        ]);

        try {
            $validated = $request->validate([
                'message' => 'required|string|max:2000',
            ], [
                'message.required' => 'الرسالة مطلوبة.',
                'message.string' => 'الرسالة يجب أن تكون نص.',
                'message.max' => 'الرسالة لا يجب أن تتجاوز 2000 حرف.',
            ]);
            
            Log::info('✅ تم التحقق من البيانات بنجاح', ['validated_data' => $validated]);
            
            // إنشاء الرسالة
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'message' => $validated['message'],
                'is_from_admin' => true,
            ]);
            
            Log::info('📝 تم إنشاء رسالة المدير', ['message_id' => $message->id]);
            
            // تحديث المحادثة
            $conversation->update([
                'is_read_by_user' => false,
                'is_read_by_admin' => true,
                'updated_at' => now(),
            ]);
            
            Log::info('🔄 تم تحديث محادثة المدير');

            // تحميل بيانات المستخدم
            $message->load('user');

            // إذا كان الطلب AJAX، أرجع JSON
            if ($request->expectsJson() || $request->ajax()) {
                $response_data = [
                    'success' => true,
                    'message' => [
                        'id' => $message->id,
                        'message' => $message->message,
                        'is_from_admin' => true,
                        'user_name' => $message->user->name ?? 'المدير',
                        'avatar' => 'A', // حرف A للأدمن
                        'created_at' => $message->created_at->format('M d, Y h:i A'),
                        'created_at_human' => $message->created_at->diffForHumans(),
                    ]
                ];
                
                Log::info('🚀 إرجاع استجابة JSON للمدير', $response_data);
                
                return response()->json($response_data);
            }
            
            return redirect()->route('admin.conversations.show', $conversation)
                ->with('success', 'تم إرسال الرد بنجاح.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ خطأ في التحقق من صحة بيانات المدير', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'فشل في التحقق من صحة البيانات',
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('💥 خطأ في طريقة رد المدير', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'خطأ في الخادم الداخلي: ' . $e->getMessage()
                ], 500);
            }
            
            throw $e;
        }
    }

    /**
     * 🚀 تحقق من وجود رسائل جديدة (للأدمن) - API الجديد
     */
    public function checkNewMessages(Request $request, Conversation $conversation)
    {
        try {
            $lastMessageId = $request->get('last_message_id', 0);
            
            Log::info('🔍 تحقق من رسائل جديدة للأدمن', [
                'conversation_id' => $conversation->id,
                'last_message_id' => $lastMessageId,
                'admin_id' => Auth::id()
            ]);
            
            // جلب الرسائل الجديدة
            $newMessages = $conversation->messages()
                ->where('id', '>', $lastMessageId)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();

            $messagesData = [];
            foreach ($newMessages as $message) {
                $messagesData[] = [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_from_admin' => $message->is_from_admin,
                    'user_name' => $message->user->name,
                    'avatar' => strtoupper(substr($message->user->name, 0, 1)),
                    'created_at' => $message->created_at->format('M d, Y h:i A'),
                    'created_at_human' => $message->created_at->diffForHumans(),
                ];
            }

            Log::info('✅ تم العثور على رسائل جديدة', [
                'count' => count($messagesData),
                'customer_messages_only' => array_filter($messagesData, function($msg) {
                    return !$msg['is_from_admin'];
                })
            ]);

            return response()->json([
                'success' => true,
                'has_new_messages' => count($messagesData) > 0,
                'messages' => $messagesData,
                'count' => count($messagesData)
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ خطأ في التحقق من الرسائل الجديدة للأدمن', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversation->id ?? 'unknown',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ في التحقق من الرسائل الجديدة'
            ], 500);
        }
    }

    /**
     * 🔄 تحقق من تحديثات عامة للمحادثات
     */
    public function checkUpdates(Request $request)
    {
        try {
            Log::info('🔄 تحقق من التحديثات العامة للمحادثات');

            // جلب عدد الرسائل غير المقروءة
            $unreadCount = Conversation::where('is_read_by_admin', false)->count();
            
            // جلب آخر المحادثات المحدثة
            $recentConversations = Conversation::with(['user', 'lastMessage'])
                ->where('updated_at', '>', now()->subMinutes(5))
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            Log::info('📊 نتائج التحديثات', [
                'unread_count' => $unreadCount,
                'recent_conversations' => $recentConversations->count()
            ]);

            return response()->json([
                'success' => true,
                'hasNewMessages' => $unreadCount > 0,
                'unreadCount' => $unreadCount,
                'recentConversations' => $recentConversations->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ خطأ في التحقق من التحديثات للأدمن', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ في التحقق من التحديثات'
            ], 500);
        }
    }

    /**
     * تحديد المحادثة كمقروءة
     */
    public function markAsRead(Conversation $conversation)
    {
        try {
            $conversation->update(['is_read_by_admin' => true]);

            Log::info('✅ تم تحديد المحادثة كمقروءة للأدمن', [
                'conversation_id' => $conversation->id,
                'admin_id' => Auth::id()
            ]);

            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'تم تحديد المحادثة كمقروءة.');
            
        } catch (\Exception $e) {
            Log::error('❌ خطأ في تحديد المحادثة كمقروءة للمدير', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversation->id
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'خطأ في تحديد كمقروءة'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'خطأ في تحديد المحادثة كمقروءة.');
        }
    }

    /**
     * تحديد جميع المحادثات كمقروءة
     */
    public function markAllAsRead()
    {
        try {
            $updated = Conversation::where('is_read_by_admin', false)
                ->update(['is_read_by_admin' => true]);

            Log::info('✅ تم تحديد جميع المحادثات كمقروءة للأدمن', [
                'updated_count' => $updated,
                'admin_id' => Auth::id()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'updated_count' => $updated
                ]);
            }

            return redirect()->back()->with('success', "تم تحديد {$updated} محادثة كمقروءة.");
            
        } catch (\Exception $e) {
            Log::error('❌ خطأ في تحديد جميع المحادثات كمقروءة للمدير', [
                'error' => $e->getMessage()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'خطأ في تحديد الكل كمقروء'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'خطأ في تحديد جميع المحادثات كمقروءة.');
        }
    }

    /**
     * الحصول على عدد المحادثات غير المقروءة
     */
    public function getCounts()
    {
        try {
            $unreadCount = Conversation::where('is_read_by_admin', false)->count();
            $totalCount = Conversation::count();

            Log::info('📊 تم الحصول على العدادات للأدمن', [
                'unread_count' => $unreadCount,
                'total_count' => $totalCount
            ]);

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
                'total_count' => $totalCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('❌ خطأ في الحصول على العدادات للمدير', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'خطأ في الحصول على العدادات'
            ], 500);
        }
    }
}