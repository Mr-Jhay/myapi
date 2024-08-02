<?php

namespace App\Http\Controllers;
use App\Models\tblsubject;
use App\Models\tblstudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddsubjectController extends Controller
{
    public function store4(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'subject_id' => 'required|exists:tblsubject,id',
        'student_id' => 'required|exists:tblstudent,id',
        'gen_code' => 'required|string',
    ]);

    // Retrieve the subject's gen_code from the database
    $subject = DB::table('tblsubject')->where('id', $request->input('subject_id'))->first();

    // Check if the subject exists and the provided gen_code matches the one in the database
    if (!$subject) {
        return response()->json(['error' => 'The selected subject does not exist.'], 404);
    }

    if ($subject->gen_code !== $request->input('gen_code')) {
        return response()->json(['error' => 'The provided gen_code is incorrect.'], 400);
    }

    // Check if the student is already enrolled in the subject
    $existingEnrollment = DB::table('addstudent')
        ->where('subject_id', $request->input('subject_id'))
        ->where('student_id', $request->input('student_id'))
        ->first();

    if ($existingEnrollment) {
        return response()->json(['error' => 'The student is already enrolled in this subject.'], 409);
    }

    // Insert the student into the addstudent table if gen_code matches and the student is not already enrolled
    DB::table('addstudent')->insert([
        'subject_id' => $request->input('subject_id'),
        'student_id' => $request->input('student_id'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => 'Student added successfully.'], 200);
}
    

    public function index4()
    {
        $addstudents = DB::table('addstudent')
        ->join('tblsubject', 'addstudent.subject_id', '=', 'tblsubject.id')
        ->join('tblstudent', 'addstudent.student_id', '=', 'tblstudent.id')
        ->join('users', 'tblstudent.user_id', '=', 'users.id') // Assuming `tblstudent` has a `user_id` foreign key for joining with `users`
        ->select('addstudent.*', 'tblsubject.subjectname as subject_name', 'tblstudent.fname as student_name', 'users.email as student_email') // Select relevant columns
        ->get();

        return view('addsubject.index', compact('addstudents'));
    }
}
