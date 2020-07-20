<?php

namespace App\Models;

class Code
{
    use CodeSenderEmail {
        send as protected senderSend;
    }

    /**
     * @var int
     */
    private int $_code;

    public function __construct()
    {
        $this->_code = mt_rand(0000, 9999);
    }

    /**
     * @return int
     */
    public function getCode():int
    {
        return $this->_code;
    }

    /**
     * @param string $to
     * @return bool
     */
    public function send(string $to):bool
    {
        return $this->senderSend($this->_code, $to);
    }
}
