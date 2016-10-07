<?php

namespace App\Http\Middleware;

use App\Helpers\ViewHelper;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class Boot
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Request::ajax() && Auth::check() && Auth::user()->is(Config::get('app.role.user'))){
            $user = Auth::user();
            if(ViewHelper::isRoute('reader.read') || ViewHelper::isRoute('reader.verse')){
                $user->last_reader_url = str_replace(Request::root(),'',Request::fullUrl());
            }
            elseif(!ViewHelper::isRoute('auth.logout')){
                $user->last_reader_url = null;
            }
            $user->save();
        }
        return $next($request);
    }
}
