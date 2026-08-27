<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NotificationHelpers;
use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\Feedback;
use App\Models\FeedbackReplies;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FeedbackRepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET /admin/feedback
     * The merged inbox — list pane + thread pane in one page.
     * ?ticket={id} pre-opens a specific ticket (used by old deep links / email links).
     */
    public function index(Request $request)
    {
        $openId = $request->query('ticket');
        return view('admin.feedback.inbox', ['openId' => $openId]);
    }

    /**
     * GET /admin/feedback/unread  — backward-compat alias, opens inbox pre-filtered to Unread tab
     */
    public function unread(Request $request)
    {
        return redirect()->route('admin.feedback', array_filter(['tab' => 'unread', 'ticket' => $request->query('ticket')]));
    }

    /**
     * GET /admin/feedback/{id}  — backward-compat alias for old direct ticket links
     */
    public function view($id)
    {
        return redirect()->route('admin.feedback', ['ticket' => $id]);
    }

    /**
     * GET /admin/feedback/api/list
     * AJAX: paginated ticket list for the left pane.
     * Query: tab=all|unread, search, page
     */
    // public function apiList(Request $request)
    // {
    //     $query = Feedback::query()
    //         ->when($request->query('tab') === 'unread', fn($q) => $q->where('status', '0'))
    //         ->when($request->query('tab') === 'unreplied', fn($q) => $q->whereNull('respondent_id'))
    //         ->when($request->search, function ($q) use ($request) {
    //             $q->where(function ($sub) use ($request) {
    //                 $sub->where('category', 'like', "%{$request->search}%")
    //                     ->orWhere('message', 'like', "%{$request->search}%")
    //                     ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$request->search}%"));
    //             });
    //         })
    //         ->with('user:id,name,email')
    //         ->withCount(['replies as unread_count' => function ($q) {
    //             $q->where('user_id', '!=', auth()->id())->whereNull('read_at');
    //         }])
    //         ->orderBy('created_at', 'desc');
    //     $feedbacks = $query->paginate(20)->appends($request->query());
    //     $rows = $feedbacks->map(function ($f) {
    //         $lastReply = $f->replies()->latest()->first();
    //         return [
    //             'id'              => $f->id,
    //             'user_name'       => $f->user->name ?? 'Unknown',
    //             'user_email'      => $f->user->email ?? '',
    //             'user_id'         => $f->user_id,
    //             'category'        => $f->category,
    //             'message'         => strip_tags($f->message),
    //             'unread_count'    => $f->unread_count,
    //             'has_replies'     => $f->replies()->count() > 0,
    //             'awaiting_reply'  => is_null($f->respondent_id), // NEW
    //             'last_activity'   => ($lastReply ?? $f)->created_at->diffForHumans(),
    //             'created_at'      => $f->created_at->format('d M, Y \a\t h:i A'),
    //         ];
    //     });
    //     return response()->json([
    //         'status'     => true,
    //         'data'       => $rows,
    //         'pagination' => [
    //             'current_page' => $feedbacks->currentPage(),
    //             'last_page'    => $feedbacks->lastPage(),
    //             'total'        => $feedbacks->total(),
    //         ],
    //     ]);
    // }
    public function apiList(Request $request)
    {
        $query = Feedback::query()
            ->when($request->query('tab') === 'unread', fn($q) => $q->where('status', '0'))
            ->when($request->query('tab') === 'unreplied', fn($q) => $q->whereRaw('(
            select fr.user_id from feedback_replies fr
            where fr.feedback_id = feedback.id
            order by fr.id desc
            limit 1
        ) = feedback.user_id'))
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub
                        ->where('category', 'like', "%{$request->search}%")
                        ->orWhere('message', 'like', "%{$request->search}%")
                        ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$request->search}%"));
                });
            })
            ->with('user:id,name,email')
            ->withCount(['replies as unread_count' => function ($q) {
                $q->where('user_id', '!=', auth()->id())->whereNull('read_at');
            }])
            ->orderBy('created_at', 'desc');

        $feedbacks = $query->paginate(20)->appends($request->query());

        $rows = $feedbacks->map(function ($f) {
            $lastReply = $f->replies()->latest()->first();

            return [
                'id' => $f->id,
                'user_id' => $f->user_id,
                'user_name' => $f->user->name ?? 'Unknown',
                'user_email' => $f->user->email ?? '',
                'category' => $f->category,
                'message' => strip_tags($f->message),
                'unread_count' => $f->unread_count,
                'has_replies' => $f->replies()->count() > 0,
                'awaiting_reply' => $lastReply ? $lastReply->user_id === $f->user_id : false,  // FIXED — was is_null($f->respondent_id)
                'last_activity' => ($lastReply ?? $f)->created_at->diffForHumans(),
                'created_at' => $f->created_at->format('d M, Y \a\t h:i A'),
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $rows,
            'pagination' => [
                'current_page' => $feedbacks->currentPage(),
                'last_page' => $feedbacks->lastPage(),
                'total' => $feedbacks->total(),
            ],
        ]);
    }

    /**
     * GET /admin/feedback/api/{id}/thread
     * AJAX: full ticket detail + replies for the right pane. Marks the user's messages read.
     */
    public function apiThread($id)
    {
        $feedback = Feedback::with('user:id,name,email,is_verified')->find($id);

        if (!$feedback) {
            return response()->json(['status' => false, 'message' => 'Ticket not found.'], 404);
        }

        $feedback->status = true;
        $feedback->save();

        FeedbackReplies::where('feedback_id', $feedback->id)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => true,
            'data' => [
                'ticket' => $this->formatTicket($feedback),
                'replies' => $this->formatReplies($feedback->id),
            ],
        ]);
    }

    /**
     * GET /admin/feedback/api/{id}/poll
     * AJAX: lightweight poll for an open thread — new messages + read-state changes.
     * Also marks the user's messages read on every poll (covers messages that arrived after initial open).
     */
    public function apiPoll($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['status' => false, 'message' => 'Ticket not found.'], 404);
        }

        FeedbackReplies::where('feedback_id', $feedback->id)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => true,
            'replies' => $this->formatReplies($feedback->id),
        ]);
    }

    /**
     * POST /admin/feedback/store — reply to a ticket. Returns JSON for the inbox's AJAX reply form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'feedback_id' => 'required|integer|exists:feedback,id',
            'message' => 'required|string',
        ]);

        $feedback = Feedback::findOrFail($request->feedback_id);
        $feedback->respondent_id = auth()->id();
        $feedback->status = true;
        $feedback->save();

        FeedbackReplies::create([
            'feedback_id' => $feedback->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'text_message' => $request->message,
            'read_at' => null,
        ]);

        $recipient = $feedback->user;

        if ($recipient) {
            try {
                Mail::to($recipient->email)->send(new GeneralMail($recipient, $request->message, 'Admin Feedback Reply', route('admin.feedback', ['ticket' => $feedback->id])));
                app(NotificationHelpers::class)->createNotification($recipient, 'Admin Feedback Reply', $request->message, 'feedback');
            } catch (\Exception $e) {
                Log::error('Mail sending failed', ['user_id' => $recipient->id ?? null, 'error' => $e->getMessage()]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Reply sent',
                'replies' => $this->formatReplies($feedback->id),
            ]);
        }

        return back()->with('success', 'Reply sent');
    }

    // ─── Private formatting helpers ──────────────────────────────────────────

    private function formatTicket(Feedback $feedback): array
    {
        return [
            'id' => $feedback->id,
            'category' => $feedback->category,
            'message' => strip_tags($feedback->message),
            'proof_url' => $feedback->proof_url,
            'user' => [
                'id' => $feedback->user->id ?? null,
                'name' => $feedback->user->name ?? 'Unknown',
                'email' => $feedback->user->email ?? '',
                'is_verified' => (bool) ($feedback->user->is_verified ?? false),
            ],
            'created_at' => $feedback->created_at->format('d M, Y \a\t h:i A'),
        ];
    }

    private function formatReplies($feedbackId): array
    {
        return FeedbackReplies::where('feedback_id', $feedbackId)
            ->with('user:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($reply) {
                $type = 'text';
                if ($reply->image_url && $reply->text_message)
                    $type = 'mixed';
                elseif ($reply->image_url)
                    $type = 'image';

                return [
                    'id' => $reply->id,
                    'sender_id' => $reply->user_id,
                    'sender_name' => in_array($reply->user->role ?? '', ['admin', 'super_admin']) ? 'Freebyz Support' : ($reply->user->name ?? 'User'),
                    'sender_role' => $reply->user->role ?? null,
                    'type' => $type,
                    'message' => strip_tags($reply->text_message ?? $reply->message),
                    'image_url' => $reply->image_url,
                    'is_mine' => $reply->user_id === auth()->id(),
                    'is_read' => !is_null($reply->read_at),
                    'created_at' => $reply->created_at->format('d M, Y \a\t h:i A'),
                ];
            })
            ->values()
            ->all();
    }
}
