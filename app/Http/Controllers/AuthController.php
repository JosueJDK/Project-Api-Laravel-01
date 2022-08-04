<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credentials error!'], 401);
        }

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = ModelsUser::where('email', $request->email)->firstOrFail();
        $token = JWTAuth::attempt($credentials);

        if ($token) {
            return response()->json([
                'status' => 'success',
                'type_token' => 'Bearer',
                'token' => $token,
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'Wrong credentials',
                'errors' => $validator->errors()], 401);
        }

    }

    public function register(Request $request)
    {
        $credentials = request(['email', 'password']);

        $validator = Validator::make($credentials, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = ModelsUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash::make($request->password),
        ]);

        $token = JWTAuth::attempt($credentials);

        if ($token) {

            return response()->json([
                'status' => 'success',
                'type_token' => 'Bearer',
                'token' => $token,
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'Wrong credentials',
                'errors' => $validator->errors()], 401);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ]);
    }

    public function user_info()
    {
        $user = auth()->user();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'UNAUTHORIZED'
            ], 401);
        }
    }

    public function destroy()
    {
        $user_id = auth()->user()->id;
        auth()->logout();
        $user = ModelsUser::where('id', $user_id)->delete();
        if ($user) {
            return response()->json(['status' => 'success', 'message' => 'User deleted successfully'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }
}
