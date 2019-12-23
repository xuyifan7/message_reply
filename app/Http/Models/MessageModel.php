<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageModel extends Model
{
    use SoftDeletes;

    protected $table = 'message';

    protected $dates = ['deleted_at'];

    public $primaryKey = 'id';

    public $fillable = [
        'user_id', 'title', 'content', 'reply_count', 'click_count'
    ];

}
