<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->status != '1') {
            return    redirect()->route('logout')->with('error', 'this account is not allowed to login');
//            abort(403, 'الحساب المستخدم غير مفعل. يرجى تفعيل الحساب');
        }
        return $next($request);
    }
}
