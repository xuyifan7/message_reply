<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //


    protected $table = 'message';

    public $primaryKey = 'id';

    public $fillable = [
        'user_id','title','content','reply_name','reply_content','reply_time'
    ];
    //外键
    public function user(){
        return $this->belongsTo('App/User','user_id','uid');
    }

    /**
     * @var array|string
     */


}
