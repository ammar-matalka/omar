<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    public function index()
    {
        try {
            $conversations = Conversation::where('user_id', Auth::id())
                ->with(['lastMessage', 'messages'])
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
                
            return view('user.conversations.index', compact('conversations'));
        } catch (\Exception $e) {
            Log::error('خطأ في فهرس المحادثات', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'خطأ في تحميل المحادثات: ' . $e->getMessage());
        }
    }
    
    public function create()
    {
        return view('user.conversations.create');
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
            ], [
                'title.required' => 'عنوان المحادثة مطلوب.',
                'title.string' => 'عنوان المحادثة يجب أن يكون نص.',
                'title.max' => 'عنوان المحادثة لا يجب أن يتجاوز 255 حرف.',
                'message.required' => 'الرسالة مطلوبة.',
                'message.string' => 'الرسالة يجب أن تكون نص.',
            ]);
            
            DB::beginTransaction();
            
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
            
            DB::commit();
            
            return redirect()->route('user.conversations.show', $conversation)
                ->with('success', 'تم بدء المحادثة بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('خطأ في إنشاء المحادثة', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'خطأ في إنشاء المحادثة: ' . $e->getMessage());
        }
    }
    
    public function show(Conversation $conversation)
    {
        try {
            if ($conversation->user_id !== Auth::id()) {
                abort(403, 'وصول غير مخول للمحادثة');
            }
            
            // تحديد كمقروءة
            $conversation->update(['is_read_by_user' => true]);
            
            $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
            
            return view('user.conversations.show', compact('conversation', 'messages'));
        } catch (\Exception $e) {
            Log::error('خطأ في عرض المحادثة', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversation->id ?? 'unknown',
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('user.conversations.index')
                ->with('error', 'خطأ في تحميل المحادثة: ' . $e->getMessage());
        }
    }
    
    public function reply(Request $request, Conversation $conversation)
    {
        // تسجيل تفصيلي للطلب
        Log::info('=== بداية محاولة رد المستخدم ===', [
            'timestamp' => now(),
            'user_id' => Auth::id(),
            'conversation_id' => $conversation->id,
            'method' => $request->method(),
            'url' => $request->url(),
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'request_data' => $request->all(),
            'message_content' => $request->input('message'),
            'message_length' => strlen($request->input('message', '')),
        ]);

        // تحقق من الصلاحية
        if ($conversation->user_id !== Auth::id()) {
            Log::warning('محاولة رد غير مخولة', [
                'user_id' => Auth::id(),
                'conversation_user_id' => $conversation->user_id,
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'وصول غير مخول'
                ], 403);
            }
            abort(403);
        }
        
        try {
            // التحقق من صحة البيانات
            Log::info('بدء التحقق من الصحة');
            
            $rules = [
                'message' => 'required|string|max:2000',
            ];
            
            $validator = Validator::make($request->all(), $rules, [
                'message.required' => 'الرسالة مطلوبة.',
                'message.string' => 'الرسالة يجب أن تكون نص.',
                'message.max' => 'الرسالة لا يجب أن تتجاوز 2000 حرف.',
            ]);
            
            if ($validator->fails()) {
                Log::error('فشل التحقق من الصحة', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all(),
                ]);
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'فشل التحقق من الصحة',
                        'errors' => $validator->errors()->toArray(),
                        'debug' => [
                            'received_data' => $request->all(),
                            'validation_rules' => $rules,
                        ]
                    ], 422);
                }
                
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $validated = $validator->validated();
            Log::info('نجح التحقق من الصحة', ['validated_data' => $validated]);
            
            // بدء المعاملة
            DB::beginTransaction();
            
            // إنشاء الرسالة
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'message' => $validated['message'],
                'is_from_admin' => false,
            ]);
            
            Log::info('تم إنشاء الرسالة بنجاح', [
                'message_id' => $message->id,
                'message_content' => $message->message,
            ]);
            
            // تحديث المحادثة
            $conversation->update([
                'is_read_by_user' => true,
                'is_read_by_admin' => false,
                'updated_at' => now(),
            ]);
            
            Log::info('تم تحديث المحادثة بنجاح');
            
            // تأكيد المعاملة
            DB::commit();
            
            // إعداد الاستجابة
            $response_data = [
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_from_admin' => false,
                    'user_name' => Auth::user()->name,
                    'created_at' => $message->created_at->format('M d, h:i A'),
                    'avatar' => substr(Auth::user()->name, 0, 1)
                ],
                'debug' => [
                    'timestamp' => now()->toISOString(),
                    'conversation_id' => $conversation->id,
                    'message_id' => $message->id,
                ]
            ];
            
            Log::info('تم إعداد الاستجابة بنجاح', $response_data);

            // إرجاع الاستجابة
            if ($request->expectsJson() || $request->ajax()) {
                Log::info('=== نجح رد المستخدم (JSON) ===');
                
                return response()->json($response_data, 200, [
                    'Content-Type' => 'application/json',
                ]);
            }
            
            Log::info('=== نجح رد المستخدم (إعادة توجيه) ===');
            return redirect()->route('user.conversations.show', $conversation)
                ->with('success', 'تم إرسال الرد بنجاح.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            Log::error('استثناء التحقق من الصحة', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'فشل التحقق من الصحة',
                    'errors' => $e->errors(),
                ], 422);
            }
            
            throw $e;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('خطأ غير متوقع في طريقة الرد', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'خطأ في الخادر الداخلي: ' . $e->getMessage(),
                    'debug' => config('app.debug') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => array_slice(explode("\n", $e->getTraceAsString()), 0, 10)
                    ] : null
                ], 500);
            }
            
            throw $e;
        }
    }

    public function checkNewMessages(Request $request, Conversation $conversation)
    {
        try {
            if ($conversation->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'غير مخول'
                ], 403);
            }

            $lastMessageId = $request->input('last_message_id', 0);
            
            $newMessages = $conversation->messages()
                ->where('id', '>', $lastMessageId)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();

            $hasNewMessages = $newMessages->count() > 0;
            
            // تحديث حالة القراءة إذا كانت هناك رسائل جديدة من الأدمن
            if ($hasNewMessages && $newMessages->where('is_from_admin', true)->count() > 0) {
                $conversation->update(['is_read_by_user' => false]);
            }

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
                }),
                'unread_count' => $conversation->hasUnreadForUser() ? 1 : 0
            ]);
            
        } catch (\Exception $e) {
            Log::error('خطأ في فحص الرسائل الجديدة', [
                'error' => $e->getMessage(),
                'conversation_id' => $conversation->id
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'خطأ في فحص الرسائل'
            ], 500);
        }
    }

    public function getUnreadCount()
    {
        try {
            $unreadCount = Conversation::where('user_id', Auth::id())
                ->where('is_read_by_user', false)
                ->count();

            return response()->json([
                'unread_count' => $unreadCount,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('خطأ في الحصول على عدد المحادثات غير المقروءة', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'unread_count' => 0,
                'success' => false,
                'error' => 'خطأ في الحصول على العدد غير المقروء'
            ], 500);
        }
    }

    public function markAsRead(Conversation $conversation)
    {
        try {
            if ($conversation->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'غير مخول'
                ], 403);
            }

            $conversation->update(['is_read_by_user' => true]);

            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'تم تحديد المحادثة كمقروءة.');
            
        } catch (\Exception $e) {
            Log::error('خطأ في تحديد كمقروءة', [
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
}