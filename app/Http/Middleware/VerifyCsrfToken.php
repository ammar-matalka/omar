<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // يمكنك إضافة routes هنا إذا كنت تريد استثناءها من CSRF
        // مثلاً: 'webhook/*'
    ];

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        // إضافة logging للتشخيص
        if ($request->isMethod('post') && $request->ajax()) {
            \Log::info('CSRF Check for AJAX POST', [
                'url' => $request->url(),
                'has_token_header' => $request->hasHeader('X-CSRF-TOKEN'),
                'has_token_input' => $request->has('_token'),
                'token_header' => $request->header('X-CSRF-TOKEN'),
                'session_token' => $request->session()->token(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return parent::handle($request, $next);
    }
}