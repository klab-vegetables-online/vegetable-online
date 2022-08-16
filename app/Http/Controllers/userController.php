<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    //
    public function register(Request $request)
    {
        //
        $this->validate($request, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string|max:6',
            'age' => 'required',
            'address' => 'required',
            'contact' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
        ]);
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'age' => $request->age,
            'address' => $request->address,
            'contact' => $request->contact,
            'email' => $request->email,
            'role' => 'customer',
            'password' => bcrypt($request->password),
        ]);
        $token = $user->createToken('authToken')->plainTextToken;
        $res = [
            'token' => $token,
            'user' => $user
        ];
        return Response()->json($res, 200);
    }
    public function login(Request $request)
    {
        //
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return Response()->json(['message' => 'User not found'], 404);
        }
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('authToken')->plainTextToken;
            $res = [
                'token' => $token,
                'user' => $user
            ];
            return Response()->json($res, 200);
        } else {
            return Response()->json(['message' => 'Password is incorrect'], 404);
        }
    }

    public function update(Request $request)
    {
        //
        $this->validate($request, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string|max:6',
            'age' => 'required',
            'address' => 'required',
            'contact' => 'required',
        ]);
        $user = User::findOrFail(auth()->user()->id);
        $user->update($request->all());
        return Response()->json($user, 200);
    }

    public function changePassword(Request $request)
    {
        //
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|string|min:6',
            'password_confirmation'  => 'required|same:new_password',
        ]);
        $user = User::findOrFail(auth()->user()->id);
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return Response()->json(['message' => 'Password changed successfully'], 200);
        } else {
            return Response()->json(['message' => 'Old password is incorrect'], 404);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return Response()->json(['message' => 'Successfully logged out'], 200);
    }
}
