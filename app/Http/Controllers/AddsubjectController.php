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
            'gen_code' => 'required|string',
        ]);
    
        // Retrieve the authenticated user
        $user = auth()->user();
    
        // Retrieve the student's ID based on the authenticated user's ID
        $student = DB::table('tblstudent')->where('user_id', $user->id)->first();
    
        // Check if the student exists
        if (!$student) {
            return response()->json(['error' => 'Student record not found for the authenticated user.'], 404);
        }
    
        // Retrieve the subject based on the provided gen_code
        $subject = DB::table('tblsubject')->where('gen_code', $request->input('gen_code'))->first();
    
        // Check if the subject exists
        if (!$subject) {
            return response()->json(['error' => 'The subject with the provided gen_code does not exist.'], 404);
        }
    
        // Check if the student is already enrolled in the subject
        $existingEnrollment = DB::table('addstudent')
            ->where('subject_id', $subject->id)
            ->where('student_id', $student->id)
            ->first();
    
        if ($existingEnrollment) {
            return response()->json(['error' => 'The student is already enrolled in this subject.'], 409);
        }
    
        // Insert the student into the addstudent table if the student is not already enrolled
        DB::table('addstudent')->insert([
            'subject_id' => $subject->id,
            'student_id' => $student->id,
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
        ->join('users', 'tblstudent.user_id', '=', 'users.id') // Join with users table using foreign key from tblstudent
        ->select(
            'addstudent.*', 
             'users.fname as student_fname',
             'users.mname as student_mname',
             'users.lname as student_lname',
            'tblsubject.subjectname as subject_name', 
            'tblstudent.strand as student_strand'
            
            
        ) // Select relevant columns with aliases
        ->get();
    
        return response()->json($addstudents);
    }

    public function newshow()
{
    $enrolledStudents = DB::table('addstudent')
        ->join('tblsubject', 'addstudent.subject_id', '=', 'tblsubject.id')
        ->join('tblstudent', 'addstudent.student_id', '=', 'tblstudent.id')
        ->join('users', 'tblstudent.user_id', '=', 'users.id')
        ->select(
            'addstudent.*', 
            'users.fname as student_fname',
            'users.mname as student_mname',
            'users.lname as student_lname',
            'tblsubject.subjectname as subject_name', 
            'tblstudent.strand as student_strand'
        )
        ->whereNotNull('addstudent.subject_id') // Ensure students have enrolled in a subject
        ->get();

    return response()->json($enrolledStudents);
}
}
