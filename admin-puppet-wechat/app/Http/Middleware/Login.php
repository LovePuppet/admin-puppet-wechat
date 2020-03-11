<?php

namespace App\Http\Middleware;

use Closure;
/**
 * 验证登录中间件
 * 郭钊林
 */
class Login{
    
    public function handle($request, Closure $next){
        if(!$request->session()->has('user')){
            return redirect('admin/login');
        }
        return $next($request);
    }
}

