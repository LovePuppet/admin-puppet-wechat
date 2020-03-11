<?php

namespace App\Http\Middleware;

use Closure;
/**
 * 验证访问权限中间件
 * 郭钊林
 */
class Permission{
    
    public function handle($request, Closure $next){
        $url = $_SERVER['REQUEST_URI'];
        $result = \App\Components\Tools::urlLimit($url,$request);
        if(!$result){
            abort(403);
        }
        return $next($request);
    }
}

