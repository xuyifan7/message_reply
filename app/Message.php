<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    const CREATED_AT = 'creation_date';
    protected $table = 'message';
    /**
     * @var array|string
     */
    private $title;
    /**
     * @var array|string
     */
    private $content;
    private $post_time;
    private $reply_content;
    private $reply_time;
    /**
     * @var \Illuminate\Session\SessionManager|\Illuminate\Session\Store
     */
    private $reply_name;
}
