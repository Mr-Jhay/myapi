<?php

namespace App\Http\Controllers;

use App\Http\Resources;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
//use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;
//namespace App\Http\Resources;
//namespace App\Models;
//use Illuminate\Http\Request;
//use Illuminate\Http\Resources\Json\JsonResource;


class UserController extends Controller

{

    public function register(Request $request)
    {
        $data = $request->validate([

            
            'idnumber' => ['required', 'integer', 'unique:users,idnumber'],
            'fname'=> ['required', 'string'],
            'mname'=> ['required', 'string'],
            'lname'=> ['required', 'string'],
            'sex'=> ['required', 'string'],
            'usertype'=> ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],

        ]);
    
        $user = User::create([
            'idnumber' => $data['idnumber'],
            'fname' => $data['fname'],
            'mname' => $data['mname'],
            'lname' => $data['lname'],
            'sex' => $data['sex'],
            'usertype' => $data['usertype'],
            'password' => bcrypt($data['password']),
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'idnumber' => ['required', 'exists:users,idnumber'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
        ]);
    
        $user = User::where('idnumber', $request->idnumber)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'user' => $user,
            'token' => $token,
            'usertype' => $user->usertype,
        ]);
    }
    public function userprofile()
    {
        // Get the authenticated user
        $user = auth()->user();
    
        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
    
        // Return the user profile data
        return response()->json([
            'status' => true,
            'message' => 'User Login Profile',
            'data' => $user,
        ], 200);
    }

    public function logout()
    {
        $user = auth()->user();

        if ($user) {
            // Revoke all tokens
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout successful',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Logout failed: User not authenticated',
            'data' => []
        ], 401);
    }

    public function edit(Request $request, $id)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'idnumber' => ['required', 'integer', 'unique:users,idnumber'],
            'fname' => ['string'],
            'mname' => ['string'],
            'lname' => ['string'],
            'sex'=> ['string'],
            'usertype' => ['string'],
            'password' => ['string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
        ]);

        // Find the user by id
        $user = User::findOrFail($id);

        // Update user fields if provided
        if (isset($data['idnumber'])) {
            $user->idnumber = $data['idnumber'];
        }
        if (isset($data['fname'])) {
            $user->fname = $data['fname'];
        }
        if (isset($data['mname'])) {
            $user->mname = $data['mname'];
        }
        if (isset($data['lname'])) {
            $user->lname = $data['lname'];
        }
        if (isset($data['sex'])) {
            $user->sex = $data['sex'];
        }
        if (isset($data['usertype'])) {
            $user->usertype = $data['usertype'];
        }
        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        // Save the updated user
        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully',
        ]);
    }

    public function userresource()
    {
        try {
            // Retrieve the authenticated user's ID
            $userId = auth()->user()->id;

            // Find the user or fail
            $user = User::findOrFail($userId);

            // Create a new UserResource instance
            $userData = new UserResource($user);

            return response()->json([
                'status' => true,
                'message' => 'User Login profile using Resource Collection',
                'data' => $userData,
                'id' => $userId
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve user profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userResourceCollection()
    {
        // Assuming UserResource is defined to transform User model data
        $userData = UserResource::collection(User::all());
        
        return response()->json([
            'status' => true,
            'message' => 'User Login profile using Resource Collection',
            'data' => $userData,
            //'id'=>''
        ], 200);
    }
}