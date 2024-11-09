<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::query()->where('email', $request->email)->first();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(new AuthResource(['token' => $token]), 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
        };

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type_id' => 0
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(new AuthResource(['token' => $token]), 201);
    }

    public function adminRegister(UserRegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type_id' => 1,
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(new AuthResource(['token' => $token]), 201);
    }
}