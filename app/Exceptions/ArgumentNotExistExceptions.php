<?php


namespace App\Exceptions;

use Exception;
class ArgumentNotExistExceptions extends Exception
{
    protected $code = 500;
    protected $message = "This argument not exist! ";
}