<?php

namespace App\Http\Middleware\Api;

use Illuminate\Http\Request;

class ApiKey
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!$request->hasHeader('x-api-key')){
            $data = [
                'result' => 'error',
                'message' => 'header `x-api-key` is not provided'
            ];
            return response()->json($data, 401);
        }
        if ($request->header('x-api-key') != env('APP_API_KEY')){
            $data = [
                'result' => 'error',
                'message' => 'header `x-api-key` is wrong'
            ];
            return response()->json($data, 401);
        }
        return $next($request);
    }
}
