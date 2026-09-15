<?php

namespace App\Mail;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountDeletionScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $daysRemaining;
    public $purgeDate;
    public $deletedAt;
    public $supportEmail;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param int $daysRemaining
     */
    public function __construct(User $user, int $daysRemaining = 60)
    {
        $this->user = $user;
        $this->daysRemaining = $daysRemaining;

        $deletionTime = $user->deleted_at ? Carbon::parse($user->deleted_at) : Carbon::now();
        $this->deletedAt = $deletionTime->format('F d, Y \a\t h:i A');
        $this->purgeDate = $deletionTime->copy()->addDays(60)->format('F d, Y');
        $this->supportEmail = 'holla@freebyz.com';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.account_deletion_scheduled')
            ->subject('Important: Your Freebyz Account is Scheduled for Deletion')
            ->with([
                'name' => $this->user->name ?: 'Freebyz User',
                'email' => $this->user->email,
                'deletedAt' => $this->deletedAt,
                'purgeDate' => $this->purgeDate,
                'daysRemaining' => $this->daysRemaining,
                'supportEmail' => $this->supportEmail,
            ]);
    }
}
