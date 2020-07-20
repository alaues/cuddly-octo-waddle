<?php

namespace App\Models;

use Illuminate\Support\Facades\Mail;

trait CodeSenderEmail
{

    private $_senderType = 'email';

    /**
     * @param int $code
     * @param string $to
     * @return bool
     */
    public function send(int $code, string $to):bool
    {
        $text = 'Secret code: ' . $code;
        $subject = 'Secret Code';
        Mail::raw($text, function ($m) use ($subject, $to) {
            $m->from(config('mail.from.address'), config('mail.from.name'))
                ->to($to)
                ->subject($subject);
        });
        return empty(Mail::failures());
    }

    public function getSenderType()
    {
        return $this->_senderType;
    }

}
