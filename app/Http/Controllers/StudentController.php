<?php

namespace App\Http\Controllers;

use App\Models\tblstudent; // Assuming your model is named Student
use App\Models\User;
use Illuminate\Http\Request;

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
}
