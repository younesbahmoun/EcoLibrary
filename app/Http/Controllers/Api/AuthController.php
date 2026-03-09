<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use App\Http\Requests\Api\LoginUserRequest;
// use App\Traits\ApiResponses;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    // ─── Register ───────────────────────────────────────────
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = false;
        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'User registered successfully',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new UserResource($user),
        ], 201);
    }

    // ─── Login ──────────────────────────────────────────────
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect.',
            ], 401);
        }
        // if (!Auth::attempt($request->only('email', 'password'))) {
        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect.'],
        //     ]);
        // }

        /** @var \App\Models\User $user */ //error here in vscode but it works fine in postman
        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken; 

        return response()->json([
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new UserResource($user),
        ]);
    }

    // ─── Logout ─────────────────────────────────────────────
    public function logout(Request $request)
    {
        // Revoke only current token
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

    // ─── Logout from all devices ────────────────────────────
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices',
        ]);
    }

    // ─── Me ─────────────────────────────────────────────────
    // public function me(Request $request)
    // {
    //     return response()->json([
    //         'user' => new UserResource($request->user()),
    //     ]);
    // }
}