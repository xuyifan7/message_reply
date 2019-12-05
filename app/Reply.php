<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_update';

    protected $table = 'reply';

    public $primaryKey = 'rid';

    public function user(){
        return $this->belongsTo('App/User','user_id','uid');
    }

    public function message(){
        return $this->belongsTo('App/Message','mes_id','id');
    }

    private $user_id;
    private $mes_id;
    private $reply_name;
    private $reply_content;
}
