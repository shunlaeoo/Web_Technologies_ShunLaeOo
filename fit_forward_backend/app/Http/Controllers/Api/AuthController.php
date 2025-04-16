<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255|unique:users',
            'password'       => 'required|string|min:6',
            'age'            => 'required|integer|min:1',
            'gender'         => 'required|integer|in:0,1',
            'height'         => 'required|numeric|min:20',
            'weight'         => 'required|numeric|min:20',
            'activity_level' => 'required|integer|min:1|max:5',
          ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Calculate BMI
        $heightInMeters = $request->height / 100;
        $bmi = $request->weight / ($heightInMeters * $heightInMeters);
        $bmi = round($bmi, 2);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'age'           => $request->age,
            'gender'         => $request->gender,
            'height'         => $request->height,
            'weight'         => $request->weight,
            'bmi'            => $bmi,
            'activity_level' => $request->activity_level,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data'          => $user,
            'message'       => 'Register successful.',
            'access_token'  => $token,
            'token_type'    => 'Bearer' 
        ]);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }
        $token  = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);     
    }

    public function logout() 
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout successfull'
        ]);
    }
}
