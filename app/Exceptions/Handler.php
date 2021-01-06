<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler {

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception) {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception) {
        if (!$request->expectsJson()) {
            if ($this->isHttpException($exception)) {
                if ($exception->getStatusCode() == 404) {
                    return response()->view('admin.errors.' . '404', [], 404);
                }
            } else {
                return response()->view('admin.errors.default', ['exception' => $exception]);
            }
        }

        return parent::render($request, $exception);
    }

    protected function renderHttpException(Throwable $e) {
        if (!view()->exists("admin.errors.{$e->getStatusCode()}")) {
            return response()->view('admin.errors.' . '406', [], 406);
        }

        return parent::renderHttpException($e);
    }

}
