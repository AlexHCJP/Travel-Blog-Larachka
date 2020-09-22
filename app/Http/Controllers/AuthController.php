<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'uploadImage']]);
    }

    public function login(){
        $credentials = request(['email', 'password']);

        if(!$token = auth()->attempt($credentials)){
            return response()->json(['error' => 'Invalid email or password'], 401);
        }
        return  $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }



    public function respondWithToken($token){
        return response()->json([
            'token' => $token,
            'access_type' => 'bearer',
            'expires_in' => auth()->factory(User::class)->getTTL() * 60
        ]);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }
    public function me(){
        $user = auth()->user();
        $data['user'] = $user;
        $data['blogs'] = $user->blog()->get();
        return $data;
    }

}
