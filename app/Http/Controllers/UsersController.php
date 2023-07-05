<?php

namespace App\Http\Controllers;


use App\Models\Users;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UsersController extends Controller
{

  public function login(Request $request)
  {
    try {
      $validation = Validator::make($request->all(), [
        'username' => "required",
        'password' => "required"
      ], [
        'username.required' => 'username tidak boleh kosong',
        'password' => 'password tidak boleh kosong'
      ]);
      if ($validation->fails()) return response()->json($validation->errors(), 400);
      $checkLogin = Users::where('username', $request->username)->first();
      if (!$checkLogin) return response()->json(['message' => 'username/password salah'], 401);
      if ($checkLogin['password'] !== $request->password) return response()->json(['message' => 'username/password salah'], 401);

      $payload = [
        'id' => $checkLogin['id'],
        'username' => $checkLogin['username'],
      ];
      $key = config('app.jwt_key');
      $access_token = JWT::encode($payload, $key, 'HS256');

      return response()->json(['access_token' => $access_token], 200);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
}
