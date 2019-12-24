<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReplyModel extends Model
{
    use SoftDeletes;

    protected $table = 'reply';

    protected $dates = ['deleted_at'];

    public $primaryKey = 'rid';

    public $fillable = [
        'user_id', 'message_id', 'reply_content', 'parent_id', 'r_reply_count'
    ];

}
