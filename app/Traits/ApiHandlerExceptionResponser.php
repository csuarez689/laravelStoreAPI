<?php

namespace App\Traits;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ApiHandlerExceptionResponser
{
    use ApiResponser;

    protected function hasJsonResponse($request, Exception $exception){
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
            return $this->errorJsonResponse('La URL ingresada no es valida',404);
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
        //respuestas generales http exceptions
        if($exception instanceof HttpException){
            return $this->errorJsonResponse($exception->getMessage(),$exception->getStatusCode());
        }
        //manejo de errores en consultas por contraint foreign key
        if($exception instanceof QueryException){
            $errorCode= $exception->errorInfo[1];
            if($errorCode==1451){
                return $this->errorJsonResponse('No se puede eliminar este recurso. Existe informacion relacionada con el mismo',409);
            }
        }
    }
}