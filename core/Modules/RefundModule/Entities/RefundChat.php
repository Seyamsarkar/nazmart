<?php

namespace Modules\RefundModule\Entities;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RefundChat extends Model
{
    protected $table = 'refund_chat';
    protected $fillable = ['title','via','operating_system','user_agent','description','subject','status','priority','user_id','admin_id','department_id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id','id');
    }
}
