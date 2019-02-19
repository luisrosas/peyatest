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
     * Lista de los tipos de excepciones que no deberían ser reportados.
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
     * Reporte o log en la excepción.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Renderiza una excepción dentro de la respuesta HTTP.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $out = $this->setExceptionResponse($exception);

        return response()->json($out['data'], $out['code']);
    }

    /**
     * Genera la respuesta según la excepción.
     *
     * @param \Exception $exception
     * @return array
     */
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

    /**
     * Genera la respuesta para la excepcion unprocessable entity.
     *
     * @param \Illuminate\Validation\ValidationException $exception
     * @return array
     */
    private function convertValidationExceptionToResponse(ValidationException $exception)
    {
        $code = Response::HTTP_UNPROCESSABLE_ENTITY;
        $data = [];
        $errors = $exception->validator->errors()->getMessages();
        foreach ($errors as $error => $value) {
            $data[] = $this->formatterErrorResponse(
                $error,
                $value,
                $code
            );
        }

        return ['data' => $data, 'code' => $code];
    }

    /**
     * Formatea la respuesta de error.
     *
     * @param string $title   Titulo del error
     * @param string $details Detalles del error
     * @param string $code    Código del error
     * @return array
     */
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
