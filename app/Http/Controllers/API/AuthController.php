<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $req)
    {
      $u = User::where('email', $req->email)->first();

      if ($u && Hash::check($req->password, $u->password)) {
            $token = $u->createToken('auth-user')->plainTextToken;

            return response()->json([
                'message' => 'berhasil',
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => 'Username atau Password salah',
            'data' => $req->all()
        ], 401);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'berhasil'
        ], 200);
    }
}
