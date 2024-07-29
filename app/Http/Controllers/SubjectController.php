<?php

namespace App\Http\Controllers;

use App\Models\tblsubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function store3(Request $request)
    {
        $request->validate([
            'subjectname' => 'required|string|max:255',
            'yearlevel' => 'required|string|max:255',
            'strand' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'gen_code' => 'required|string|max:255',
        ]);

        $teacherId = Auth::user()->id;

        // Verify that the teacher_id exists in the tblteacher table
        $teacherExists = DB::table('tblteacher')->where('user_id', $teacherId)->exists();

        if (!$teacherExists) {
            return response()->json(['message' => 'Teacher not found.'], 404);
        }

        // Get the actual teacher ID from the tblteacher table
        $teacher = DB::table('tblteacher')->where('user_id', $teacherId)->first();

        $subject = tblsubject::create([
            'teacher_id' => $teacher->id,
            'subjectname' => $request->input('subjectname'),
            'yearlevel' => $request->input('yearlevel'),
            'strand' => $request->input('strand'),
            'semester' => $request->input('semester'),
            'gen_code' => $request->input('gen_code'),
        ]);

        return response()->json([
            'message' => 'Subject created successfully',
            'data' => $subject,
        ], 201);
    }

    public function index()
    {
        $teacherId = Auth::user()->id;

        // Get the actual teacher ID from the tblteacher table
        $teacher = DB::table('tblteacher')->where('user_id', $teacherId)->first();

        $subjects = tblsubject::where('teacher_id', $teacher->id)->get();

        return response()->json([
            'data' => $subjects
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subjectname' => 'required|string|max:255',
            'yearlevel' => 'required|string|max:255',
            'strand' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'gen_code' => 'required|string|max:255',
        ]);

        $subject = tblsubject::findOrFail($id);
        $subject->update($request->all());

        return response()->json([
            'message' => 'Subject updated successfully',
            'data' => $subject,
        ]);
    }

    public function destroy($id)
    {
        $teacherId = Auth::user()->id;
    
        // Verify that the teacher_id exists in the tblteacher table
        $teacher = DB::table('tblteacher')->where('user_id', $teacherId)->first();
    
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found.'], 404);
        }
    
        $subject = tblsubject::where('id', $id)->where('teacher_id', $teacher->id)->first();
    
        if (!$subject) {
            return response()->json(['message' => 'Subject not found.'], 404);
        }
    
        $subject->delete();
    
        return response()->json([
            'message' => 'Subject deleted successfully',
        ]);
    }
}
