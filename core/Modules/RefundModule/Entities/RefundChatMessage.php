<?php

namespace Modules\RefundModule\Entities;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RefundChatMessage extends Model
{
    protected $table = 'refund_messages';
    protected $fillable = ['message','notify','attachment','refund_chat_id','type','user_id'];

    public function user_info(){
        if ($this->attributes['type'] === 'admin' && !is_null($this->attributes['user_id'])){
            return Admin::find($this->attributes['user_id']);
        }
        return User::find($this->attributes['user_id']);
    }
}
