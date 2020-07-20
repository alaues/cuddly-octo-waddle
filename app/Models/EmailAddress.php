<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAddress extends Model
{
    protected $table = 'email_addresses';
    protected $fillable = ['email', 'code'];

    public function senderHistory()
    {
        return $this->hasMany('App\Models\SenderHistory', 'email_address_id', 'id');
    }

    /**
     * @param int $code
     * @return bool
     * @throws \Exception
     */
    public function checkCode(int $code):bool
    {
        if ($this->code == $code){
            $this->delete();
            $this->senderHistory()->delete();
            return true;

        } elseif ($this->check_counter >= 2){
            $this->delete();
            $this->senderHistory()->delete();
            return false;

        } else {
            $this->check_counter += 1;
            $this->save();
            return false;
        }
    }
}
