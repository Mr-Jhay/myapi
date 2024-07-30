<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddsubjectController extends Controller
{
    public function store4(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:tblsubject,id',
            'student_id' => 'required|exists:tblstudent,id',
        ]);

        DB::table('addstudent')->insert([
            'subject_id' => $request->input('subject_id'),
            'student_id' => $request->input('student_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Student added successfully.');
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
