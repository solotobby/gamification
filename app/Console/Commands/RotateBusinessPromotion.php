<?php

namespace App\Console\Commands;

use App\Mail\GeneralMail;
use App\Models\Business;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RotateBusinessPromotion extends Command
{
    protected $signature = 'business:rotate-promotion';
    protected $description = 'Rotate business promotion - select random business for 24-hour promotion';

    public function handle()
    {
        Business::query()->where('status', 'ACTIVE')->update(['is_live' => false]);

        $randomBusiness = Business::where('status', 'ACTIVE')->inRandomOrder()->first();

        if ($randomBusiness) {
            $randomBusiness->update(['is_live' => true]);

            $user = User::where('id', $randomBusiness->user_id)->first();
            $subject = 'Freebyz Business Promotion - Business Selected';
            $content = 'Your business has been selected for Freebyz Business Promotion. This will last for 24hours';

            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

            $this->info('Business promotion rotated. Selected: ' . $randomBusiness->name ?? $randomBusiness->id);
        } else {
            $this->warn('No active business found for promotion.');
        }
    }

    //    $schedule->call(function () {


    //         Business::query()->where('status', 'ACTIVE')->update(['is_live' => false]);

    //         // Then, select a random business and set its 'is_live' to true
    //         $randomBusiness = Business::where('status', 'ACTIVE')->inRandomOrder()->first();
    //         if ($randomBusiness) {
    //             $randomBusiness->update(['is_live' => true]);
    //         }

    //         $user = User::where('id', $randomBusiness->user_id)->first();
    //         $subject = 'Freebyz Business Promotion - Business Selected';
    //         $content = 'Your business has been selected for Freebyz Business Promotion. This will last for 24hours';


    //         Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));


    //         // $user = User::where('id', 4)->first(); //$user['name'] = 'Oluwatobi';
    //         // $subject = 'New Business Promotion selected';
    //         // $content = 'Automatic Business Promotion Selected';
    //         // Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));


    //     })->daily();
}
