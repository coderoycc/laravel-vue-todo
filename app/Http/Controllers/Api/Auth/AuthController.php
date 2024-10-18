<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Prompts\Note;

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
        consolog($req->validated());
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

    public function changepass(Request $request){
        // Validar los datos de entrada
        $request->validate([
            'password' => 'required|string',
            'newPassword' => 'required|string|min:8',
        ]);
        try {
            // Obtener el usuario autenticado
            $user = auth()->user();
    
            // Verificar si la contraseña actual es correcta
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'La contraseña actual es incorrecta.'], 400);
            }
    
            $userObj = User::find($user->id);
            $userObj->password = Hash::make($request->newPassword);
            Log::info('usuario '.json_encode($user));
            $userObj->save();
    
            return response()->json(['message' => 'Contraseña actualizada exitosamente.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
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
