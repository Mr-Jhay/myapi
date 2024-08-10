<?php

namespace App\Http\Controllers;

use App\Models\tblstudent; // Assuming your model is named Student
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
class StudentController extends Controller
{
    public function store2(Request $request)
    {
        $request->validate([
            'strand' => 'required|string|max:255',
            'gradelevel' => 'required|string|max:255',
        ]);
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Check if the user has a usertype of 'student'
        if ($user->usertype !== 'student') {
            return response()->json(['error' => 'User is not a student'], 403);
        }
    
        // Check if a record already exists for this user
        $existingStudent = tblstudent::where('user_id', $user->id)->first();
    
        if ($existingStudent) {
            return response()->json(['message' => 'Student record already exists'], 409);
        }
    
        // Create the student record
        $student = tblstudent::create([
            'user_id' => $user->id,
            'strand' => $request->strand,
            'gradelevel' => $request->gradelevel,
        ]);
    
        return response()->json(['message' => 'Student record created successfully', 'student' => $student], 201);
    }

    public function index2()
    {
        // Fetch data
        $results = DB::table('users')
            ->join('tblstudent', 'users.id', '=', 'tblstudent.user_id')
            ->select('users.*', 'tblstudent.user_id', 'tblstudent.strand', 'tblstudent.gradelevel')
            ->get();
    
        // Define the timezone offset (e.g., +8 hours for Asia/Manila)
        $timezoneOffset = 8 * 60 * 60; // Offset in seconds
    
        // Convert timestamps
        foreach ($results as $result) {
            // Convert created_at and updated_at to the desired timezone
            $result->created_at = date('Y-m-d H:i:s', strtotime($result->created_at) + $timezoneOffset);
            $result->updated_at = date('Y-m-d H:i:s', strtotime($result->updated_at) + $timezoneOffset);
        }
    
        // Return JSON response
        return response()->json($results, 200);
    }
    
    
    // UPDATE PROFILE
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
            'strand' => 'sometimes|string|max:255',
            'gradelevel' => 'sometimes|string|max:255',
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

        // Update the student record
        DB::table('tblstudent')
            ->where('user_id', $id)
            ->update([
                'strand' => $request->input('strand'),
                'gradelevel' => $request->input('gradelevel'),
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

    // Count strands
    public function countStrandStudents()
    {
        // Count students in HUMMS 
        $countHumms11 = tblstudent::where('strand', 'HUMMS')->where('gradelevel', 'GRADE 11')->count();
        $countHumms12 = tblstudent::where('strand', 'HUMMS')->where('gradelevel', 'GRADE 12')->count();
        $countStem11 = tblstudent::where('strand', 'STEM')->where('gradelevel', 'GRADE 11')->count();
        $countStem12 = tblstudent::where('strand', 'STEM')->where('gradelevel', 'GRADE 12')->count();
        $countAbm11 = tblstudent::where('strand', 'ABM')->where('gradelevel', 'GRADE 11')->count();
        $countAbm12 = tblstudent::where('strand', 'ABM')->where('gradelevel', 'GRADE 12')->count();
        $countTVL11 = tblstudent::where('strand', 'TVL-ICT')->where('gradelevel', 'GRADE 11')->count();
        $countTVL12 = tblstudent::where('strand', 'TVL-ICT')->where('gradelevel', 'GRADE 12')->count();
        // Total count
        $totalHumms = $countHumms11 + $countHumms12;
        $totalStem = $countStem11 + $countStem12;
        $totalAbm = $countAbm11 + $countAbm12;
        $totalTVL = $countTVL11 + $countTVL12;

        // Return response with counts
        return response()->json([
            'HUMMS_11_count' => $countHumms11,
            'HUMMS_12_count' => $countHumms12,
            'total_humms' => $totalHumms,

            'STEM_11_count' => $countStem11,
            'STEM_12_count' => $countStem12,
            'total_stem' => $totalStem,

            'ABM_11_count' => $countAbm11,
            'ABM_12_count' => $countAbm12,
            'total_abm' => $totalAbm,

            'TVL-ICT_11_count' => $countTVL11,
            'TVL-ICT 12_count' => $countTVL12,
            'total_tvl' => $totalTVL,
        ], 200);
    }

}