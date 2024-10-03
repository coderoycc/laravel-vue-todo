<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Ruta protegida, para obtener los datos del usuario loggeado
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    public function login(LoginRequest $req){
        $token = auth()->attempt($req->validated());
        if($token)
            return $this->responseWithToken(auth()->user(), $token);
        
        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    /**
     * registro de usuario con request Custom para validar
     */
    public function register(RegistrationRequest $req){
        $user = User::create($req->validated());
        if($user){
            $token = auth()->login($user);
            return $this->responseWithToken($user, $token);
        }
        return response()->json([
            'success' => false,
            'message' => 'Ocurrio un error, usuario no creado'
        ], 403);
    }

    /**
     * Respuesta con los datos de usuario y token 
     */
    public function responseWithToken($user, $token){
        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
