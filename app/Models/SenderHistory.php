<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SenderHistory extends Model
{

    public $timestamps = false;

    protected $table = 'sender_history';

    protected $fillable = [
        'email_address_id',
        'sender_type',
        'sent_at'
    ];
}
