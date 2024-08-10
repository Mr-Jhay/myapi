<?php

namespace App\Http\Controllers;

use App\Models\tblteacher;
use App\Models\User;
use App\Models\tblschedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class tblscheduleController extends Controller
{
    public function addexam(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'quarter' => 'required|string|max:255',
            'subjectname' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        // Determine the subject_id based on the authenticated user or other logic
        // For example, if each user has a subject associated with them
        $user = Auth::user(); // Get the authenticated user
        $subject_id = $user->subject_id; // Or use any other logic to get the subject_id

        // Add the subject_id to the validated data
        $validatedData['subject_id'] = $subject_id;

        // Create a new schedule record
        $schedule = tblschedule::create($validatedData);

        // Return a JSON response with a custom message
        return response()->json([
            'message' => 'Schedule created successfully!',
            'data' => $schedule,
        ], 201);
    }
}
