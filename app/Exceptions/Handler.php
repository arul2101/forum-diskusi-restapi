<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($this->isHttpException($e)) {
            if ($e->getStatusCode() == 404) {
                return response()->json([
                    "errors" => [
                        "message" => [
                            "not found"
                        ]
                    ]
                ], 404);
            }

            if ($e->getStatusCode() == 500) {
                return response()->json([
                    "errors" => [
                        "message" => [
                            "server errors"
                        ]
                    ]
                ], 500);
            }

            if ($e->getStatusCode() == 405) {
                return response()->json([
                    "errors" => [
                        "message" => [
                            "method not allowed"
                        ]
                    ]
                ], 405);
            }
        }
     
        return parent::render($request, $e);
    }
}
