<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'min:5',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:8|confirmed',
            'admin' => 'in: 0,1',
        ];
        $this->validate($request, $rules);

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorJsonResponse('Solo los usuarios verificados pueden modificar el campo administrador', 409);
            }
            $user->admin = $request->admin;
        }
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->email_verified_at = null;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }
        if ($user->isClean()) {
            return $this->errorJsonResponse('No hay datos que actualizar', 422);
        }
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showMessage(['id' => $user->id]);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;
        $user->email_verified_at = Carbon::now();
        $user->save();
        return $this->showMessage(['message' => 'Su correo ha sido verificado correctamente']);
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorJsonResponse('El usuario ya se encuentra verificado', 409);
        }
        retry(5, function () use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 2000);
        return $this->showMessage(['message' => 'El correo de verificacion ha sido reenviado']);
    }
}
