<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected function allowedAdminActions()
    {
        if (Gate::denies('admin-actions')) {
            throw new AuthorizationException('Accion no autorizada');
        }
    }
}
