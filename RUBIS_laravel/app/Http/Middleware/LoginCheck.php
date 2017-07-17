<?php

namespace App\Http\Middleware;

use App\user;
use Closure;
use Request;
use \Response;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $filename = basename($request->getUri());
        if (Request::exists('nickname') and Request::exists('password')) {
            $user = Request::get('nickname');
            $password = Request::get('password');
            $count = user::where('nickname', $user)->where('password', $password)->count();
            if ($count != 1) {
                $title = 'RUBiS ERROR: "' . basename($request->getUri()) . '"';
                $message = 'invalid user or password';
                return response()->view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
            } else
                return $next($request);
        } else {
            $title = 'RUBiS ERROR: "' . basename($request->getUri()) . '"';
            $filename = basename($request->getUri());
            $message = 'please load this page from form or via API with datas';
            return response()->view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
    }
}
