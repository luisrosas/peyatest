<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
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
        $out = $this->setExceptionResponse($exception);

        return response()->json($out['data'], $out['code']);
    }

    private function setExceptionResponse(Exception $exception)
    {
        $out = [];
        if ($exception instanceof ValidationException) {
            $out = $this->convertValidationExceptionToResponse($exception);
        } else if ($exception instanceof QueryException) {
            $codeException = 0;
            if (isset($exception->errorInfo[1])) {
                $codeException = $exception->errorInfo[1];
            }
            if ($codeException == 1062 || $codeException == 19) {
                $out['code'] = Response::HTTP_CONFLICT;
                $out['data'] = $this->formatterErrorResponse(
                    'Query exception',
                    'Already exist a comment for this purchase',
                    $out['code']
                );
            }
        } else if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            $out['code'] = Response::HTTP_NOT_FOUND;
            $out['data'] = $this->formatterErrorResponse(
                'Instance not found',
                "Don't exist instance of {$model} with the specified id",
                $out['code']
            );
        } else if ($exception instanceof NotFoundHttpException) {
            $out['code'] = Response::HTTP_NOT_FOUND;
            $out['data'] = $this->formatterErrorResponse(
                'Not found http',
                'The specified URL could not be found',
                $out['code']
            );
        } else {
            $out['code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
            $out['data'] = $this->formatterErrorResponse(
                'Internal server error',
                'Internal error has occurred, try again',
                $out['code']
            );
        }

        return $out;
    }

    private function convertValidationExceptionToResponse(ValidationException $e)
    {
        $code = Response::HTTP_UNPROCESSABLE_ENTITY;
        $data = [];
        $errors = $e->validator->errors()->getMessages();
        foreach ($errors as $error => $value) {
            $data[] = $this->formatterErrorResponse(
                $error,
                $value,
                $code
            );
        }

        return ['data' => $data, 'code' => $code];
    }

    private function formatterErrorResponse($title, $details, $code)
    {
        return [
            'error' => [
                'status' => $code,
                'title' => $title,
                'details' => $details,
            ]
        ];
    }
}
