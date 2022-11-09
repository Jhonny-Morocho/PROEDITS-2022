<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
class Controller extends BaseController
{
    /**
     * @OA\Info(title="PROEDITSCLUB-API-2022", version="1")
     */

    /**
     * @OA\Get(
     *     path="/api/me",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    
    //************** SEGURIDAD TOKEN **********//
    /**
     * @OA\OpenApi(
     *   security={{"bearerAuth": {}}}
     * )
     *
     * @OA\SecurityScheme(
     *   securityScheme="bearerAuth",
     * description="Authentication Bearer Token",
     *   type="http",
     * bearerFormat="JWT",
     * securityScheme="Bearer dsfdgfd",
     *   scheme="bearer",
     * )
     */

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
