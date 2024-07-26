<?php

namespace App\Http\Controllers;

use App\Models\tblsubject;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\TeacherController;

class SubjectController extends Controller
{
    public function store3(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'teacher_id' => 'required|exists:tblteacher,id|unique:tblsubject,teacher_id',
            'subjectname' => 'required|string|max:255',
            'yearlevel' => 'required|string',
            'strand' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'gen_code' => 'required|string|max:255',
            'up_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

            // Handle the file upload
    $filePath = null;
    if ($request->hasFile('up_img')) {
        $file = $request->file('up_img');
        $filePath = $file->store('uploads', 'public'); // Store the file in the 'public/uploads' directory
    }

    // Create a new subject record
    $subject = tblsubject::create([
        'teacher_id' => $request->input('teacher_id'),
        'subjectname' => $request->input('subjectname'),
        'yearlevel' => $request->input('yearlevel'),
        'strand' => $request->input('strand'),
        'semester' => $request->input('semester'),
        'gen_code' => $request->input('gen_code'),
        'up_img' => $filePath,
    ]);

        // Return a JSON response indicating success
        return response()->json([
            'message' => 'Subject created successfully',
            'data' => $subject,
        ], 201);
    }
}
