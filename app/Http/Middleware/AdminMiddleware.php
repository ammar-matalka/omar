<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تحقق من أن المستخدم مسجل دخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this area.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // تحقق من أن المستخدم أدمن
        if (!$user->isAdmin()) {
            abort(403, 'Access Denied. Admin privileges required.');
        }

        return $next($request);
    }
}