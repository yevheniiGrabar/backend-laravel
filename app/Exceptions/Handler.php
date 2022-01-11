<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

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
        });
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        return response()->json($e->validator->errors()->getMessages(), 422);
    }

    public function render($request, Throwable $e): \Symfony\Component\HttpFoundation\Response
    {
        if ($e instanceof AuthenticationException) {
            return response_json_error(
                'AuthenticationException',
                $e->getMessage(),
                'Guard: ' . implode(', ', $e->guards()),
                401
            );
        }

        if ($e instanceof AuthorizationException) {
            return response_json_error(
                'AuthorizationException',
                $e->getMessage(),
                '',
                403
            );
        }

        if ($e instanceof ValidationException) {
            return response_json_error('ValidationException', $e->getMessage(), $e->errors(), 422);
        }

        if ($e instanceof ModelNotFoundException) {
            return response_json_error('ModelNotFoundException', $e->getMessage(), '', 404);
        }

        if ($e instanceof \PDOException) {
            return response_json_error(class_basename($e), 'Bad Request', 'Error executing SQL query', 400);
        }

        if ($e instanceof BadRequestHttpException) {
            return response_json_error(class_basename($e), $e->getMessage(), '', 400);
        }

        if ($e->getCode() === 500) {
            return response_json_error(
                'InternalServerError',
                $e->getMessage(),
                'Unknown error occurred. Try to refresh the page and repeat actions.',
                500
            );
        }

        if ($e->getCode() === 400) {
            return response_json_error(class_basename($e), $e->getMessage(), '', $e->getCode());
        }

        if ($e instanceof \Exception) {
            return response_json_error(
                class_basename($e),
                $e->getMessage()
            );
        }
        return parent::render($request, $e);
    }
}
