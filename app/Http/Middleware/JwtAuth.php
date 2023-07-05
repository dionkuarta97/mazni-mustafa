<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */

  public function handle(Request $request, Closure $next)
  {
    $token = $request->header('access_token');
    $key = config('app.jwt_key');
    if (!$token) return response()->json(['message' => 'Anda Tidak Dapat Akses'], 401);

    try {
      $checkToken = JWT::decode($token, new Key($key, "HS256"));
    } catch (Exception $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }

    $user = [
      'id' => $checkToken->id,
      'username' => $checkToken->username,
    ];


    $request->request->add(['user' => $user]);
    return $next($request);
  }
}
