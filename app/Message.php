<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //


    protected $table = 'message';

    public $primaryKey = 'id';

    public $fillable = [
        'user_id','title','content','reply_id'
    ];
    //外键
    public function user(){
        return $this->belongsTo('App/Users','user_id','uid');
    }



}
