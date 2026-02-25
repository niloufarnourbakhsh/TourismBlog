<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user=User::where('name',$request->name)->first();
        if (! $user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message'=>'Login information is not valid'
            ],401);
        }
        $token=$user->createToken('api_token')->plainTextToken;
        return \response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer',
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $validated['password']=Hash::make($request->password);
        $user=User::create($request->validated());
        return response()->json([
            'data'=>$user,
            'access_token'=>$user->createToken('api_token')->plainTextToken,
            'token_type'=>'Bearer',
        ] , 201);
    }
}
