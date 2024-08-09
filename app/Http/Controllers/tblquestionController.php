<?php

namespace App\Http\Controllers;

use App\Models\tblteacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class tblquestionController extends Controller
{
    public function addquestion(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'question_type' => 'required|string|max:255',
            'question' => 'required|string',
        ]);

        // Determine the tblschedule_id automatically
        // Example: Get the most recent schedule or based on some logic
        $tblschedule = Schedule::latest('created_at')->first();
        $tblschedule_id = $tblschedule ? $tblschedule->id : null;

        // If no schedule is found, you might want to handle this scenario
        if (!$tblschedule_id) {
            return response()->json([
                'message' => 'No schedule available to assign.',
            ], 404);
        }

        // Add tblschedule_id to the validated data
        $validatedData['tblschedule_id'] = $tblschedule_id;

        // Create a new question record
        $question = Question::create($validatedData);

        // Return a JSON response with a custom message
        return response()->json([
            'message' => 'Question created successfully!',
            'data' => $question,
        ], 201);
    }
}
