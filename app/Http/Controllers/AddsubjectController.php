<?php

namespace App\Http\Controllers;

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
            'gen_code' => 'required|string',  // Assuming gen_code is a string
        ]);
    
        // Retrieve the subject's gen_code from the database
        $subject = DB::table('tblsubject')->where('id', $request->input('subject_id'))->first();
    
        // Check if the provided gen_code matches the one in the database
        if ($subject && $subject->gen_code === $request->input('gen_code')) {
            // Insert the student into the addstudent table if gen_code matches
            DB::table('addstudent')->insert([
                'subject_id' => $request->input('subject_id'),
                'student_id' => $request->input('student_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            return redirect()->back()->with('success', 'Student added successfully.');
        } else {
            // Return an error message if gen_code does not match
            return redirect()->back()->withErrors(['gen_code' => 'The provided gen_code is incorrect.']);
        }
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
