<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\Feedback;
use App\Models\FeedbackReplies;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FeedbackRepliesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $feedbacks = Feedback::orderBy('created_at', 'DESC')->get();
        return view('admin.feedback.index', ['feedbacks' => $feedbacks]);
    }

    public function view($id){
        $feedback = Feedback::where('id', $id)->firstOrFail();
        $feedback->status = true;
        $feedback->save();
        return view('admin.feedback.view', ['feedback' => $feedback]);
    }

    public function store(Request $request){
        $request->validate([
            'message' => 'required',
        ]);
        $respondent = Feedback::where('id', $request->feedback_id)->first();
        $respondent->respondent_id = auth()->user()->id;
        $respondent->save();
        $replies = FeedbackReplies::create($request->all());

        $senderID = $replies->feedback->user->id;

        $user = User::where('id', $senderID)->first();
        $subject = 'Admin Feedback Reply';
        $content = $request->message;
        $url = 'feedback/view/'.$request->feedback_id;
        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, $url));
        return back()->with('success', 'Reply sent');
    }
}
