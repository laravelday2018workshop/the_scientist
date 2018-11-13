<?php

declare(strict_types=1);

namespace App\Exceptions;

use Acme\Common\Exception\EntityNotFound;
use Acme\Common\Exception\InvalidInput;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = ['password', 'password_confirmation'];

    public function report(Exception $exception)
    {
        parent::report($exception);
    }

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
