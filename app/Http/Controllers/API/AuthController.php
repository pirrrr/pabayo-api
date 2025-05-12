<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        Log::info('Request payload:', $request->all());

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'emailAddress' => 'required|string|email|max:255|unique:users_tbl',
            'contactNumber' => 'required|string|max:11',
            'homeAddress' => 'required|string|max:255',
            'IDCard' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'emailAddress' => $request->emailAddress,
            'contactNumber' => $request->contactNumber,
            'homeAddress' => $request->homeAddress,
            'IDCard' => $request->IDCard,
            'roleID' => 3, // Example for a default role (you can adjust)
            'password' => Hash::make($request->password),
        ]);

        $user->save();
        $token = $user->createToken('PabayoApp')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    // Login a user
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailAddress' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'error' => $validator->errors()], 422);
        }

        $user = User::where('emailAddress', $request->emailAddress)->first();

        Log::info('Password from request: ' . $request->password);
        Log::info('Hashed password from DB: ' . $user->password);
        Log::info('User retrieved: ', $user ? $user->toArray() : []);

        

        try {
    if (!$user || !Hash::check($request->password, $user->password)) {
        Log::warning('Login failed for email: ' . $request->emailAddress);
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }
} catch (\Throwable $e) {
    Log::error('Hash check failed: ' . $e->getMessage());
    return response()->json([
        'success' => false,
        'message' => 'Server error during password validation',
        'error' => $e->getMessage()
    ], 500);
}

        $token = $user->createToken('PabayoApp')->plainTextToken;
        Log::info('Token created for user ID: ' . $user->id);

        return response()->json(
            
            ['user' => $user, 'token' => $token], 200);
    }

    // Get the logged-in user's profile
    public function profile(Request $request)
{
    $user = $request->user();
    Log::info('Accessing /profile - Auth user:', [$request->user()]);

    return response()->json($user);
}


    // Logout a user (revoke token)
    public function logout(Request $request)
    {
        $request->user()->tokens->delete();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
