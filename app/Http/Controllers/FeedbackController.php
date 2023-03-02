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
        if($request->category == 'transfer_issue'){
            $category = 'transfer issue';
            $content = 'Thank you for taking the time to send this '.$category.'. We have recieved it and will act on it accordingly. Please send a screenshot of proof of payment to info@dominahl.com.  Thank you once again.';
        }elseif($request->category == 'complaint'){
            $category = 'complaint';
            $content = 'Thank you for taking the time to send this '.$category.'. We have recieved it and will act on it accordingly. Thank you once again.';
        }else{
            $category = 'feedback';
            $content = 'Thank you for taking the time to send this '.$category.'. We have recieved it and will act on it accordingly. Thank you once again.';
        }
        Feedback::create($request->all());
        $subject = 'Feedback Received';
        $user = User::where('id', auth()->user()->id)->first();
        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject));
        return back()->with('success', 'Thank you for your feedback, we will look into it.');
    }
}
