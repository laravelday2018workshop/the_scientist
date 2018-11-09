<?php

declare(strict_types=1);

namespace App\Exceptions;

use Acme\Common\Exception\EntityNotFound;
use Acme\Common\Exception\InvalidInput;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
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
     * @param \Exception $exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof EntityNotFound) {
            return $this->renderEntityNotFound($exception);
        }

        if ($exception instanceof InvalidInput) {
            return $this->renderInvalidInput($exception);
        }

        return $this->renderException($exception);
    }

    private function renderEntityNotFound(EntityNotFound $exception)
    {
        return response()->json(
            [
                'message' => \sprintf(
                    '%s with ID "%s" was not found',
                    class_basename($exception->getEntityName()),
                    $exception->getEntityId()
                ),
            ],
            404
        );
    }

    private function renderInvalidInput(InvalidInput $exception)
    {
        return response()->json(['message' => $exception->getMessage()], 400);
    }

    private function renderException(Exception $exception)
    {
        return response()->json(['message' => $exception->getMessage()], 500);
    }
}
