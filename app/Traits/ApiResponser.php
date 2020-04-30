<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
    /**
     * Create a custom success json response
     *
     * @param Mixed $data can be a Collection or Model
     * @param Integer $code default value 200
     * @return \Illuminate\Http\JsonResponse with $data and $code
     **/
    protected function successJsonResponse($data, $code = 200)
    {
        return response()->json(['data' => $data], $code);
    }

    /**
     * Create a custom error json response
     *
     * @param Mixed $message String[] or String
     * @param Integer $code doesn't have default value
     * @return JsonResponse with $message error and $code
     **/
    protected function errorJsonResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return response()->json(['data' => $message], $code);
    }

}
