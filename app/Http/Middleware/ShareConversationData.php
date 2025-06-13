<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Conversation;

class ShareConversationData
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            try {
                $unreadConversationsCount = Conversation::where('user_id', Auth::id())
                    ->where('is_read_by_user', false)
                    ->count();
                    
                View::share('unreadConversationsCount', $unreadConversationsCount);
            } catch (\Exception $e) {
                View::share('unreadConversationsCount', 0);
            }
        } else {
            View::share('unreadConversationsCount', 0);
        }

        return $next($request);
    }
}