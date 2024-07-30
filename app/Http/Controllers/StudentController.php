<?php

namespace App\Http\Controllers;

use App\Models\tblstudent; // Assuming your model is named Student
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UserController;

class StudentController extends Controller
{
    public function store2(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:tblstudent,user_id',
            'strand' => 'required|string|max:255',
            'gradelevel' => 'required|string|max:255',
        ]);

        // Check if the user has a usertype of 'student'
        $user = User::find($request->user_id);

        if ($user->usertype !== 'student') {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        $student = tblstudent::create([ // Use the correct model name
            'user_id' => $request->user_id,
            'strand' => $request->strand,
            'gradelevel' => $request->gradelevel,
        ]);

        return response()->json($student, 201);
    }

    public function index2()
    {
        // Fetch data
        $results = DB::table('users')
            ->join('tblstudent', 'users.id', '=', 'tblstudent.user_id')
            ->select('users.*', 'tblstudent.user_id', 'tblstudent.strand', 'tblstudent.gradelevel')
            ->get();
    
        // Define the timezone offset (e.g., +8 hours for Asia/Manila)
        $timezoneOffset = 8 * 60 * 60; // Offset in seconds
    
        // Convert timestamps
        foreach ($results as $result) {
            // Convert created_at and updated_at to the desired timezone
            $result->created_at = date('Y-m-d H:i:s', strtotime($result->created_at) + $timezoneOffset);
            $result->updated_at = date('Y-m-d H:i:s', strtotime($result->updated_at) + $timezoneOffset);
        }
    
        // Return JSON response
        return response()->json($results, 200);
    }
    
    
    // UPDATE PROFILE
    public function update(Request $request, $id)
    {
        $request->validate([
            'idnumber' => [
                'sometimes', 'string', 'min:8', 'max:12',
                Rule::unique('users', 'idnumber')->ignore($id),
            ],
            'fname' => 'sometimes|string',
            'mname' => 'sometimes|string',
            'lname' => 'sometimes|string',
            'sex' => 'sometimes|string',
            'usertype' => 'sometimes|string',
            'email' => [
                'sometimes', 'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'Mobile_no' => [
                'sometimes', 'string', 'digits:11',
                Rule::unique('users', 'Mobile_no')->ignore($id),
            ],
            'password' => [
                'sometimes', 'string', 'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'strand' => 'sometimes|string|max:255',
            'gradelevel' => 'sometimes|string|max:255',
        ]);

        $data = $request->only([
            'idnumber', 'fname', 'mname', 'lname', 'sex', 'usertype', 'email', 'Mobile_no', 'password'
        ]);

        // Update the user record
        DB::table('users')
            ->where('id', $id)
            ->update([
                'idnumber' => $data['idnumber'],
                'fname' => $data['fname'],
                'mname' => $data['mname'],
                'lname' => $data['lname'],
                'sex' => $data['sex'],
                'usertype' => $data['usertype'],
                'email' => $data['email'],
                'Mobile_no' => $data['Mobile_no'],
                'password' => Hash::make($data['password']),
                'updated_at' => now(),
            ]);

        // Update the student record
        DB::table('tblstudent')
            ->where('user_id', $id)
            ->update([
                'strand' => $request->input('strand'),
                'gradelevel' => $request->input('gradelevel'),
                'updated_at' => now(),
            ]);

        // Assuming you want to create a new token for the updated user
        $user = User::find($id);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Record updated successfully.',
            'token' => $token
        ]);
    }

}
