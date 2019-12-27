<?php


namespace App\Exceptions;

use Exception;
class BaseExceptions extends Exception
{
    protected $code = 500;
    protected $message = "This argument not exist! ";
}