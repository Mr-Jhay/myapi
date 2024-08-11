<?php

namespace App\Http\Controllers;
use App\Models\tblsubject;
use App\Models\tblstudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
    
    
    
    
    public function index4(Request $request)
{
    // Retrieve the authenticated user
    $user = auth()->user();

    // Validate if the student record exists
    $student = DB::table('tblstudent')->where('user_id', $user->id)->first();

    if (!$student) {
        return response()->json(['error' => 'Student record not found for the authenticated user.'], 404);
    }

    $addstudents = DB::table('addstudent')
        ->join('tblsubject', 'addstudent.subject_id', '=', 'tblsubject.id')
        ->join('tblstudent', 'addstudent.student_id', '=', 'tblstudent.id')
        ->join('users as student_users', 'tblstudent.user_id', '=', 'student_users.id') // Alias for student user info
        ->join('tblteacher', 'tblsubject.teacher_id', '=', 'tblteacher.id')
        ->join('users as teacher_users', 'tblteacher.user_id', '=', 'teacher_users.id') // Alias for teacher user info
        ->where('tblstudent.user_id', $user->id) 
        ->select(
            'addstudent.*',
            'student_users.fname as student_fname',
            'student_users.mname as student_mname',
            'student_users.lname as student_lname',
            'tblsubject.subjectname as subject_name',
            'tblstudent.strand as student_strand',
            'teacher_users.fname as teacher_fname', // Teacher's first name
            'teacher_users.mname as teacher_mname', // Teacher's middle name
            'teacher_users.lname as teacher_lname'  // Teacher's last name
        )
        ->get();

    return response()->json($addstudents);
}

public function newshow()
{
    $enrolledStudents = DB::table('addstudent')
        ->join('tblsubject', 'addstudent.subject_id', '=', 'tblsubject.id')
        ->join('tblstudent', 'addstudent.student_id', '=', 'tblstudent.id')
        ->join('users as students', 'tblstudent.user_id', '=', 'students.id')
        ->join('tblteacher', 'tblsubject.teacher_id', '=', 'tblteacher.id') // Join tblteacher with tblsubject
        ->join('users as teachers', 'tblteacher.user_id', '=', 'teachers.id') // Join users table with tblteacher
        ->select(
            'addstudent.*', 
            'students.fname as student_fname',
            'students.mname as student_mname',
            'students.lname as student_lname',
            'tblsubject.subjectname as subject_name', 
            'tblstudent.strand as student_strand',
            'teachers.fname as teacher_fname', // Teacher's first name
            'teachers.mname as teacher_mname', // Teacher's middle name
            'teachers.lname as teacher_lname', // Teacher's last name
            'tblteacher.teacher_Position as teacher_position' // Teacher's position
        )
        ->whereNotNull('addstudent.subject_id') // Ensure students have enrolled in a subject
        ->get();

    return response()->json($enrolledStudents);
}

public function listSubjectsForTeacher()
{
    // Retrieve the authenticated user
    $user = auth()->user();

    // Retrieve the teacher's record based on the authenticated user's ID
    $teacher = DB::table('tblteacher')->where('user_id', $user->id)->first();

    // Check if the teacher exists
    if (!$teacher) {
        return response()->json(['error' => 'Teacher record not found for the authenticated user.'], 404);
    }

    // Retrieve the subjects associated with the authenticated teacher
    $subjects = DB::table('tblsubject')
        ->where('teacher_id', $teacher->id)
        ->select(
            'id as subject_id',
            'subjectname',
            'gen_code'
        )
        ->get();

    return response()->json($subjects);
}
}
