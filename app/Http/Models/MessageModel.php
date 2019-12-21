<?php

namespace App\Models;

use App\Http\Controllers\MessageController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MessageModel extends Model
{

    protected $table = 'message';

    public $primaryKey = 'id';

    public $fillable = [
        'user_id', 'title', 'content', 'reply_count', 'click_count'
    ];

}
