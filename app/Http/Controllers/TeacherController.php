<?php

namespace App\Http\Controllers;

use App\Models\tblteacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

    public function index()
    {
        $results = DB::table('users')
            ->join('tblteacher', 'users.id', '=', 'tblteacher.user_id')
            ->select('users.*', 'tblteacher.teacher_Position')
            ->get();

        return response()->json($results);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'teacher_Position' => 'required|string|max:255',
        ]);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'updated_at' => now(),
            ]);

        DB::table('tblteacher')
            ->where('user_id', $id)
            ->update([
                'teacher_Position' => $request->input('teacher_Position'),
                'updated_at' => now(),
            ]);

        return response()->json(['message' => 'Record updated successfully.']);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'teacher_Position' => 'required|string|max:255',
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tblteacher')->insert([
            'user_id' => $userId,
            'teacher_Position' => $request->input('teacher_Position'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Record inserted successfully.']);
    }

    public function softDelete($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        DB::table('tblteacher')
            ->where('user_id', $id)
            ->update(['deleted_at' => now()]);

        return response()->json(['message' => 'Record soft deleted successfully.']);
    }
}
