<?php

namespace App\Http\Controllers;

use App\Models\tblteacher;
use App\Models\studentexam;
use App\Models\tblschedule;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class studentexamController extends Controller
{
    public function schedule(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            // Any other validations you need
        ]);

        // Determine the tblstudent_id based on the authenticated user
        $user = Auth::user(); // Get the authenticated user
        $tblstudent_id = $user->tblstudent_id; // Assuming the user model has tblstudent_id

        // Get the most recent tblschedule_id
        $tblschedule = tblschedule::latest('created_at')->first();
        $tblschedule_id = $tblschedule ? $tblschedule->id : null;

        // If no schedule is found, you might want to handle this scenario
        if (!$tblschedule_id) {
            return response()->json([
                'message' => 'No schedule available to assign.',
            ], 404);
        }

        // Add tblstudent_id and tblschedule_id to the validated data
        $validatedData['tblstudent_id'] = $tblstudent_id;
        $validatedData['tblschedule_id'] = $tblschedule_id;

        // Create a new studentexam record
        $studentExam = studentexam::create($validatedData);

        // Return a JSON response with a custom message
        return response()->json([
            'message' => 'StudentExam record created successfully!',
            'data' => $studentExam,
        ], 201);
    }
}
