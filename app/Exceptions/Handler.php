<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Krucas\Notification\Facades\Notification;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof HttpException && !Request::ajax() && $e->getStatusCode() == 404){
//            Notification::error("Requested content does not exist");
//            return redirect('/');
        }
        if ($e instanceof HttpException && $e->getStatusCode() == 401){
            Notification::error($e->getMessage());
//            Notification::info($e->getMessage());
            return redirect()->guest('auth/login');
        }
        if ($e instanceof \ErrorException || $e instanceof HttpException && $e->getStatusCode() == 500){
            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $e);
    }
}
