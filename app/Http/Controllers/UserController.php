<?php

namespace App\Http\Controllers;

use App\Http\Resources;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;



class UserController extends Controller

{
    //////////showwww this all user galing database
    public function index()
    {
        return User::all();
    }
    //////////showwww this all students galing database
    public function getStudentUsers()
    {
        try {
            $students = User::where('usertype', 'student')->get();
            return response()->json($students, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch student users'], 500);
        }
    }
    public function getTeachersUsers()
    {
        try {
            $teacher = User::where('usertype', 'teacher')->get();
            return response()->json($teacher, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch student users'], 500);
        }
    }
    public function getUsersCounts()
    {
        // Count the number of male and female users as well as teacher and student
        $femaleCount = User::where('sex', 'female')->count();
        $maleCount = User::where('sex', 'male')->count();
        $teacherCount = User::where('usertype', 'teacher')->count();
        $studentCount = User::where('usertype', 'student')->count();

        // Return the counts as a JSON response
        return response()->json([
            'femaleUsers' => $femaleCount,
            'maleUsers' => $maleCount,
            'teacher' => $teacherCount,
            'student' => $studentCount
        ]);
    }
    public function register(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'idnumber' => ['required', 'string','min:8', 'max:12' ,'unique:users,idnumber'],
            'fname' => ['required', 'string'],
            'mname' => ['required', 'string'],
            'lname' => ['required', 'string'],
            'sex' => ['required', 'string'],
            'usertype' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'Mobile_no' => ['required','string', 'digits:11', 'unique:users,Mobile_no'],
            'password' => [
                'required', 
                'string', 
                'min:8', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ]);
    
        try {
            // Create the user
            $user = User::create([
                'idnumber' => $data['idnumber'],
                'fname' => $data['fname'],
                'mname' => $data['mname'],
                'lname' => $data['lname'],
                'sex' => $data['sex'],
                'usertype' => $data['usertype'],
                'email' => $data['email'],
                'Mobile_no' => $data['Mobile_no'],
                'password' => Hash::make($data['password']),
            ]);
    
            // Generate the token
            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Return the user and token
            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 201);
    
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Registration failed: ' . $e->getMessage());
    
            // Return a response with error details
            return response()->json([
                'message' => 'Registration failed',
                'error' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    $user = User::where('email', $request->email)->first();

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
            // 'idnumber' => ['required', 'integer', 'unique:users,idnumber'],
            'idnumber' => ['nullable', 'integer', 'unique:users,idnumber,' . $id],
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