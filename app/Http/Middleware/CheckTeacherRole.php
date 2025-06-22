<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTeacherRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // ログインしていない場合、ログイン画面にリダイレクト
        if (!Auth::check()) {
            return redirect()->route('loginView');
        }

        // ログインしているが、生徒（role: 4）の場合はアクセス禁止
        if (!in_array(Auth::user()->role, [1, 2, 3])) {
            abort(403, 'このページへアクセスする権限がありません。');
        }

        return $next($request);
    }
}
