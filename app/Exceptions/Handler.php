<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    use ApiResponser;

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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        //respuesta para errorers de validacion
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        //respuesta modelo o registro no encontrado
        if ($exception instanceof ModelNotFoundException) {
            $name = $exception->getModel()::$modelName;
            return $this->errorJsonResponse("No existe un registo de {$name} con el identificador especificado", 404);
        }
        //respuesta error en url ingresada
        if ($exception instanceof NotFoundHttpException){
            return $this->errorJsonResponse('La URL ingresada no existe',404);
        }
        //respuesta error de autenticacion de usuario
        if ($exception instanceof AuthenticationException){
            return $this->unauthenticated($request,$exception);
        }
        //respuesta error permisos insuficientes 
        if ($exception instanceof AuthorizationException){
            return $this->errorJsonResponse($exception->getMessage(),403);
        }
        //respuesta error metodo no valido en peticion http
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorJsonResponse('El mertodo especificado para la peticion es invalido', 405);
        }
        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return $this->errorJsonResponse($errors, 422);
    }

    /**
     * Create a response object for unauthenticated users
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse
     **/
    public function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorJsonResponse('Unauthenticated',401);
    }
}
