<?php

namespace App\Http\Controllers;

use App\Services\User\Auth\AuthnService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthnService $authnService
    ) {}

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->authnService->authenticate($request->email, $request->password);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refreshtoken(Request $request)
    {
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
