<?php

namespace App\Http\Controllers;

use App\Models\tblteacher;
use App\Models\User;
use App\Models\addchoices;
use App\Models\correctanswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class correctanswerController extends Controller
{
    public function correct(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'correct_answer' => 'required|string|max:255',
        ]);

        // Determine the tblquestion_id automatically
        // Example: Get the most recent question
        $tblquestion = tblquestion::latest('created_at')->first();
        $tblquestion_id = $tblquestion ? $tblquestion->id : null;

        // If no question is found, handle this scenario
        if (!$tblquestion_id) {
            return response()->json([
                'message' => 'No question available to assign.',
            ], 404);
        }

        // Determine the addchoices_id automatically
        // Example: Get the most recent choice or based on some logic
        $addChoice = addchoices::latest('created_at')->first();
        $addchoices_id = $addChoice ? $addChoice->id : null;

        // If no choice is found, handle this scenario
        if (!$addchoices_id) {
            return response()->json([
                'message' => 'No choice available to assign.',
            ], 404);
        }

        // Add tblquestion_id and addchoices_id to the validated data
        $validatedData['tblquestion_id'] = $tblquestion_id;
        $validatedData['addchoices_id'] = $addchoices_id;

        // Create a new correctanswer record
        $correctAnswer = correctanswer::create($validatedData);

        // Return a JSON response with a custom message
        return response()->json([
            'message' => 'CorrectAnswer record created successfully!',
            'data' => $correctAnswer,
        ], 201);
    }
}
