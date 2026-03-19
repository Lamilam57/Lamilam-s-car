<?php

namespace App\Http\Controllers;

use App\Models\AppFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppFeedbackController extends Controller
{

    public function index()
    {
        $feedbacks = AppFeedback::with('user')
            ->latest()
            ->paginate(10);

        return view('car.feedback',[
            'feedbacks'=>$feedbacks,
            'role'=>auth()->user()->role
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'rating' => ['nullable','integer','between:1,5'],
            'type' => ['required'],
            'subject' => ['nullable','string','max:255'],
            'message' => ['required','string','max:2000']
        ]);
        // dd($validated);

        $key = 'feedback-'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors('Too many feedback submissions. Please wait.');
        }

        RateLimiter::hit($key, 60);

        $exists = AppFeedback::where('user_id', auth()->id())
            ->where('message', $request->message)
            ->exists();

        if ($exists) {
            return back()->withErrors('Duplicate feedback detected.');
        }

        $validated['user_id'] = auth()->id();
        $validated['ip_address'] = $request->ip();
        // dd($validated);

        AppFeedback::create($validated, );

        return back()->with('success','Thank you for your feedback!');
    }
}