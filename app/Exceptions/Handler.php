<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use  ApiResponseTrait;

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

    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {

        if ($e instanceof AmountToLowException) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],

            );
        }
        if ($e instanceof SameAccountException) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
            );
        }
        if ($e instanceof InvalidPinSuppliedException) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],

            );
        }
        if ($e instanceof InvalidAccountNumber) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],

            );
        }
        if ($e instanceof ANotFoundException) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],

            );
        }
        if ($e instanceof AccountBlockedException) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],

            );
        }
        if ($e instanceof InsufficientBalanceException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],

            );
        }
        if ($e instanceof AuthenticationException) {
            Log::error($e);
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'Unauthenticated or Token Expired, Please Login'
                ],
                401
            );
        }
        if ($e instanceof NotFoundHttpException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'Route not found',
                    'exception' => $e
                ],
                500
            );
        }
        if ($e instanceof ValidationException) {

            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ],
                422
            );
        }
        if ($e instanceof QueryException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => 'There was Issue with the Query',
                    'exception' => $e

                ],
                500
            );
        }
        if ($e instanceof ModelNotFoundException) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => class_basename($e->getModel()) . ' not found'
                ],
                404
            );
        }
        if ($e instanceof \Error) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => "There was some internal error",
                    'exception' => $e
                ],
                500
            );
        }
        if ($e instanceof \Exception) {
            return $this->apiResponse(
                [
                    'success' => false,
                    'message' => "There was some internal error",
                    'exception' => $e
                ],
                500
            );
        }
        return parent::render($request, $e);
    }
}
