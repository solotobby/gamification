<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
use App\Models\Feedback;
use App\Models\FeedbackReplies;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{

    public function __construct()
    {
         // $this->middleware(['auth', 'email']);
        $this->middleware('auth');
    }

    public function index(){
        $feedbacks = Feedback::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        return view('user.feedback.index', ['feedbacks' => $feedbacks]);
    }
    public function create(){
        return view('user.feedback.create');
    }
    public function view($feedback_id){
        $feedbacks = Feedback::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        $feedbackReplies = Feedback::where('id', $feedback_id)->first();
        $feedbackReplies->replies()->where('feedback_id', $feedback_id)->where('user_id', $feedbackReplies->respondent_id)->where('status', 1)->update(['status' => 0]);
        // $updateUserReadStatus = FeedbackReplies::where('feedback_id', $feedback_id)->where('status', 1)->get();  //
        // foreach($updateUserReadStatus as $update){
        //     $df = FeedbackReplies::where('id', $update->id)->first();
        //     $df->status = '0';
        //     $df->save();
        // }
        return view('user.feedback.view', ['replies' => $feedbackReplies, 'feedbacks' => $feedbacks]);
    }

    public function reply(Request $request){
        $this->validate($request, [
            'message' => 'required|string',
        ]);

        $fd = FeedbackReplies::create($request->all());
        $fd->status = true;
        $fd->save();

        // $fedbackRespondent = Feedback::where('id', $request->feedback_id)->first();
        // $content = $request->message;
        // $subject = 'Feedback Reply from '.auth()->user()->name;
        // $user = User::where('role', 'staff')->where('id', $fedbackRespondent->respondent_id)->firstOrFail();
        //  Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
        return back()->with('success', 'Thank you for your reply, we will get back to you soon.');
    }

    public function store(Request $request){

        $this->validate($request, [
            'proof' => 'image|mimes:png,jpeg,gif,jpg',
            'category' => 'required',
            'message' => 'required|string',
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

        if($request->hasFile('proof')){

            // $fileBanner = $request->file('proof');
            // $Bannername = time() . $fileBanner->getClientOriginalName();
            // $filePathBanner = 'feedbacks/' . $Bannername;

            // Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
            // $proofUrl = Storage::disk('s3')->url($filePathBanner);

            $imageName = time().'.'.$request->proof->extension();
            $request->proof->move(public_path('images'), $imageName);

            $feedback['user_id'] = auth()->user()->id;
            $feedback['category'] = $request->category;
            $feedback['message'] = $request->message;
            $feedback['status'] = false;
            $feedback['proof_url'] = $imageName == '' ? 'no image' : 'images/'.$imageName; //$proofUrl;
            Feedback::create($feedback);

            $subject = 'Feedback Received';
            $user = User::where('id', auth()->user()->id)->first();
            //  Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

            return back()->with('success', 'Thank you for your feedback, we will look into it.');

        }else{
            return back()->with('error', 'Image not uploaded');
        }
    }
}
