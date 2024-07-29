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


    //UPDATE PROFILE
    public function update(Request $request, $id)
    {
        $request->validate([
            'idnumber' => [
                'sometimes', 'string', 'min:8', 'max:12',
                Rule::unique('users', 'idnumber')->ignore($id),
            ],
            'fname' => 'sometimes|string',
            'mname' => 'sometimes|string',
            'lname' => 'sometimes|string',
            'sex' => 'sometimes|string',
            'usertype' => 'sometimes|string',
            'email' => [
                'sometimes', 'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'Mobile_no' => [
                'sometimes', 'string', 'digits:11',
                Rule::unique('users', 'Mobile_no')->ignore($id),
            ],
            'password' => [
                'sometimes', 'string', 'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'teacher_Position' => 'sometimes|string|max:255',
        ]);
    
        $data = $request->only([
            'idnumber', 'fname', 'mname', 'lname', 'sex', 'usertype', 'email', 'Mobile_no', 'password'
        ]);
    
        // Update the user record
        DB::table('users')
            ->where('id', $id)
            ->update([
                'idnumber' => $data['idnumber'],
                'fname' => $data['fname'],
                'mname' => $data['mname'],
                'lname' => $data['lname'],
                'sex' => $data['sex'],
                'usertype' => $data['usertype'],
                'email' => $data['email'],
                'Mobile_no' => $data['Mobile_no'],
                'password' => Hash::make($data['password']),
                'updated_at' => now(),
            ]);
    
        // Update the teacher record
        DB::table('tblteacher')
            ->where('user_id', $id)
            ->update([
                'teacher_Position' => $request->input('teacher_Position'),
                'updated_at' => now(),
            ]);
    
        // Assuming you want to create a new token for the updated user
        $user = User::find($id);
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Record updated successfully.',
            'token' => $token
        ]);
    }
    

    // public function insert(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255|unique:users,email',
    //         'teacher_Position' => 'required|string|max:255',
    //     ]);

    //     $userId = DB::table('users')->insertGetId([
    //         'name' => $request->input('name'),
    //         'email' => $request->input('email'),
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     DB::table('tblteacher')->insert([
    //         'user_id' => $userId,
    //         'teacher_Position' => $request->input('teacher_Position'),
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     return response()->json(['message' => 'Record inserted successfully.']);
    // }

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
