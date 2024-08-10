<?php

namespace App\Http\Controllers;

use App\Models\tblteacher;
use App\Models\User;
use App\Models\addchoices;
use App\Models\answeredQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class answeredQuestionController extends Controller
{
    public function resultexam(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            // No need to validate tblquestion_id, correctanswer_id, tblstudent_id
            // as they are assigned automatically
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

        // Determine the correctanswer_id automatically
        // Example: Get the most recent correct answer
        $correctAnswer = correctanswer::latest('created_at')->first();
        $correctanswer_id = $correctAnswer ? $correctAnswer->id : null;

        // If no correct answer is found, handle this scenario
        if (!$correctanswer_id) {
            return response()->json([
                'message' => 'No correct answer available to assign.',
            ], 404);
        }

        // Determine the tblstudent_id automatically
        // Assuming you want the ID of the currently authenticated user
        $tblstudent_id = Auth::user()->tblstudent_id; // Adjust as needed

        // Add tblquestion_id, correctanswer_id, and tblstudent_id to the validated data
        $validatedData['tblquestion_id'] = $tblquestion_id;
        $validatedData['correctanswer_id'] = $correctanswer_id;
        $validatedData['tblstudent_id'] = $tblstudent_id;

        // Create a new answered_question record
        $answeredQuestion = answeredQuestion::create($validatedData);

        // Return a JSON response with a custom message
        return response()->json([
            'message' => 'AnsweredQuestion record created successfully!',
            'data' => $answeredQuestion,
        ], 201);
    }
}
