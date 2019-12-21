<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplyModel extends Model
{
    protected $table = 'reply';

    public $primaryKey = 'rid';

    public $fillable = [
        'user_id', 'message_id', 'reply_content', 'reply_id', 'r_reply_count'
    ];

}
