<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
        $this->middleware(['auth', 'email']);
     }

    public function index()
    {
        Notification::where('user_id', auth()->user()->id)->update(['is_read' => true]);
        @$notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('user.notification.index', ['notifications' => $notifications]);
    }

    public function adminNotifications(){
        $notifications = Announcement::orderBy('created_at', 'DESC')->get();
        return view('admin.notifications.index', ['notifications' => $notifications]);
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
    }

    public function changeNotificationStatus($id){
        $anouncement = Announcement::where('id', $id)->first();
        if($anouncement->status == true){
            $anouncement->status = false;
            $anouncement->save();
        }else{
            $anouncement->status = true;
            $anouncement->save();
        }
        return back()->with('success', 'Announcement status changed');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNotificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNotificationRequest  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }

    public function storeNotification(Request $request){

        switch ($request->type) {
            case 'announcement':
                    Announcement::orderBy('id', 'DESC')->update(['status' => false]);
                    Announcement::create(['user_id' => auth()->user()->id, 'content' => $request->content, 'status' => true]);
                break;
            case 'notification':
                    $users = User::where('role', 'regular')->get();
                    $content = $request->content;
                    foreach($users as $user){
                        systemNotification($user, 'success', $request->title, $content);
                    }
                break;
            case 'both':
                    Announcement::orderBy('id', 'DESC')->update(['status' => false]);
                    Announcement::create(['user_id' => auth()->user()->id, 'content' => $request->content]);
                    $users = User::where('role', 'regular')->get();
                    $content = $request->content;

                    foreach($users as $user){
                        systemNotification($user, 'success', $request->title, $content);
                    }
                break;
        }
        return back()->with('success', 'Posted successfully');
    }
}
