<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function signup(Request $request)
    {
        # code...
        $this->validate($request, [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required',
        ]);

        $admin = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'admin',
            'password' => bcrypt($request->password),
        ]);
        $token = $admin->createToken('authToken')->plainTextToken;
        $res = [
            'token' => $token,
            'admin' => $admin
        ];
        return Response()->json($res, 200);
    }
    public function login(Request $request)
    {
        # code...
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
        ]);
        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            return Response()->json(['message' => 'User not found'], 404);
        }
        if (Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('authToken')->plainTextToken;
            $res = [
                'token' => $token,
                'admin' => $admin
            ];
            return Response()->json($res, 200);
        }
        return Response()->json(['message' => 'Password is invalid'], 404);
    }

    public function logout(Request $request)
    {
        # code...
        $request->user()->token()->revoke();
        return Response()->json(['message' => 'Successfully logged out'], 200);
    }
}
