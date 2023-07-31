<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorNotification;

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


    public function report(Throwable $exception){
        if ($this->shouldReport($exception)) {
            $errorMessage = $exception->getMessage();
            $errorTrace = $exception->getTraceAsString();
            Mail::to('solotobz5@gmail.com')->send(new ErrorNotification($errorMessage, $errorTrace));
        }

        parent::report($exception);
    }
}
