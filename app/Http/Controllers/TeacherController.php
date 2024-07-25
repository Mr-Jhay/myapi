<?php

namespace App\Http\Controllers;

use App\Models\tblteacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

class TeacherController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:tblteacher,user_id',
            'teacher_Position' => 'required|string|max:255',
        ]);

        // Check if the user has a usertype of teacher
        $user = User::find($request->user_id);

        if ($user->usertype !== 'teacher') {
            return response()->json(['error' => 'User is not a teacher'], 403);
        }

        $teacher = tblteacher::create([
            'user_id' => $request->user_id,
            'teacher_Position' => $request->teacher_Position,
        ]);

        return response()->json($teacher, 201);
    }
}

