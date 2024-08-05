<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
        ? response()->json(['message' => 'Unauthenticated'], 401)
        : redirect()->guest(route('login'));
    }
    
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    // Example for API specific error handling
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            if ($exception instanceof AuthenticationException) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
        }

        return parent::render($request, $exception);
    }
}
