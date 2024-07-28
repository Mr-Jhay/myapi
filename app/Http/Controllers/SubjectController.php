<?php

namespace App\Http\Controllers;

use App\Models\tblsubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $subject = tblsubject::create([
            'teacher_id' => $teacherId,
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
        $subjects = tblsubject::where('teacher_id', Auth::user()->id)->get();

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
        $subject = tblsubject::findOrFail($id);
        $subject->delete();

        return response()->json([
            'message' => 'Subject deleted successfully'
        ]);
    }
}
