<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FeedbackReplies;
use Illuminate\Http\Request;

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
        $replies = FeedbackReplies::where('feedback_id', $id)->get();
        return view('admin.feedback.view', ['feedback' => $feedback, 'replies' => $replies]);
    }

    public function store(Request $request){
        $request->validate([
            'message' => 'required',
        ]);
        FeedbackReplies::create($request->all());
        return back()->with('success', 'Reply sent');
    }
}
