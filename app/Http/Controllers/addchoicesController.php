<?php

namespace App\Http\Controllers;

use App\Models\tblteacher;
use App\Models\User;
use App\Models\addchoices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class addchoicesController extends Controller
{
    public function addchoices(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'choices' => 'required|array',
            'choices.*' => 'required|string|max:255',
        ]);

        // Determine the tblquestion_id automatically
        // Example: Get the most recent question or based on some logic
        $tblquestion = tblquestion::latest('created_at')->first(); // Adjust logic as needed
        $tblquestion_id = $tblquestion ? $tblquestion->id : null;

        // If no question is found, handle this scenario
        if (!$tblquestion_id) {
            return response()->json([
                'message' => 'No question available to assign.',
            ], 404);
        }

        // Add tblquestion_id to the validated data
        $validatedData['tblquestion_id'] = $tblquestion_id;

        // Create a new addchoice record
        $addChoice = addchoices::create($validatedData);

        // Return a JSON response with a custom message
        return response()->json([
            'message' => 'Choice created successfully!',
            'data' => $addChoice,
        ], 201);
    }
}
