<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class APIAuthController extends Controller
{
    private const VERSION = "v1";

    /**
     * Login to app.
     * When success login user will retrive Sanctum Token.
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Unauthenticated! Your email or password is incorrect!',
                'version' => self::VERSION
            ], 401);
        }

        $token = $user->createToken('api_access_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'version' => self::VERSION
        ], Response::HTTP_OK);
    }

    /**
     * Logout from app.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'API :: Logged out',
            'version' => self::VERSION
        ], Response::HTTP_OK);
    }
}
