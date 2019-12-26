<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        /*var_dump($exception->getTraceAsString(),$exception->getMessage());
        die;*/
        if ($exception instanceof ArgumentNotExistExceptions) {
            \Log::error('Exception,code=' . $exception->getCode() . ',message=' . $exception->getMessage() . ',file=' . $exception->getFile() . ',line=' . $exception->getLine());
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ArgumentNotExistExceptions) {
            return response()->json(['code' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
        return parent::render($request, $exception);
    }
}
