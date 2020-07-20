<?php

namespace App\Http\Middleware\Api;

use App\Models\EmailAddress;
use Carbon\Carbon;

class SendCode
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

            $model = EmailAddress::where('email', $email)->first();
            if ($model && $model->senderHistory()){
                $now = Carbon::now()->subHour();
                $history = $model->senderHistory()
                    ->where('sent_at', '>=', $now->format('U'))
                    ->orderBy('sent_at')
                    ->get();

                //not more than 5 times for last hour
                if (count($history) >= 5){
                    $first = $history[0];
                    $dt = Carbon::createFromFormat('U', $first->sent_at)->addHour();

                    $minutes = $dt->diffInMinutes(Carbon::now());
                    throw new \InvalidArgumentException('too many requests, try in ' . $minutes . ' mins');
                }

                //once per 5 mins
                $last = $model->senderHistory()->orderBy('sent_at', 'desc')->first();
                $dtSent = Carbon::createFromFormat('U', $last->sent_at);
                $now    = Carbon::now();
                $minutes = $now->diffInMinutes($dtSent);

                if ($minutes < 5){
                    throw new \InvalidArgumentException('too often requests, try in ' . (5-$minutes) . ' mins');
                }
            }

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
