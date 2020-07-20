<?php

namespace App\Http\Middleware\Api;

class CheckCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            $email = filter_var($request->get('email'), FILTER_VALIDATE_EMAIL);
            throw_if(!$email, new \InvalidArgumentException('Invalid email'));

            $code = filter_var($request->get('code'), FILTER_VALIDATE_INT);
            throw_if(!$code, new \InvalidArgumentException('Invalid code'));

            return $next($request);
        } catch (\InvalidArgumentException $exception){
            $data = [
                'result' => 'error',
                'message' => $exception->getMessage()
            ];
            return response()->json($data);
        }
    }
}
