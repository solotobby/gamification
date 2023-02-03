<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        return view('user.feedback.index');
    }

    public function store(Request $request){
        $request->validate([
            'category' => 'required',
            'message' => 'required',
        ]);
        
        Feedback::create($request->all());
        
        $content = 'Thank you for taking the time to send this '.$request->category.'. We have recieved it and will act on it accordingly. Thank you once again.';
        $subject = 'Feedback Received';
        $user = User::where('id', auth()->user()->id)->first();
        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject));

        return back()->with('success', 'Thank you for your feedback, we will look into it.');
    }
}
