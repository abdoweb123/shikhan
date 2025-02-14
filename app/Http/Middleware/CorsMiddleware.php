<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*header("Access-Control-Allow-Origin: *");

            $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, Origin'
            ];
            if($request->getMethod() == "OPTIONS") {

            return response()->make('OK', 200, $headers);
            }

            $response = $next($request);
            foreach($headers as $key => $value)
            $response->header($key, $value);
            return $response;*/

           /* return $next($request)
            ->header('Access-Control-Allow-Origi','*')
            ->header('Access-Control-Allow-Methods','POST, GET, OPTIONS, PUT, DELETE');*/
          /*  if ($request->isMethod('OPTIONS')){
                $response = Response::make();
            } else {
                $response = $next($request);
            }*/
            return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type');
    }
}
