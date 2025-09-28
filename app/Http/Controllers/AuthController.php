<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message'=>'Credenciales inválidas'], 401);
        }

        // crear token personal
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'user'=>$user
        ]);
    }

    public function logout(Request $request)
    {
        // revocar tokens del usuario autenticado
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Tokens eliminados']);
    }
}
