<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemFeedback;
use Illuminate\Support\Facades\Auth;

class SystemFeedbackController extends Controller
{
    public function index()
    {
        $feedback = SystemFeedback::where('space_owner_id', Auth::id())->get();

        return view('space_owner.reviews', compact('feedback'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'feedback_content' => 'required|string|max:2000',
            'rate' => 'nullable|integer|min:1|max:5',
        ]);

        SystemFeedback::create([
            'space_owner_id' => Auth::id(), 
            'feedback_content' => $request->input('feedback_content'),
            'rating' => $request->input('rate'),
        ]);

        return redirect()->route('space.dashboard')->with('success', 'Feedback submitted successfully!');
    }
}
