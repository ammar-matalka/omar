<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Make sure this import exists

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        return redirect('/')->with('error', 'You do not have admin access.');
    }
}
