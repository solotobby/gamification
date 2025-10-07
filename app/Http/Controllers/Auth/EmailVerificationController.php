<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Exception;

class EmailVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sendCode(Request $request)
    {
        $user = auth()->user();

        $code = random_int(100000, 999999);

        session([
            'verification_code' => $code,
            'verification_code_expires' => now()->addMinutes(10)
        ]);

        try {
            // Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));

            return view('auth.email_verification', [
                'success' => 'Verification code sent successfully.'
            ]);
        } catch (Exception $e) {
            return view('auth.email_verification', [
                'error' => 'Failed to send email. Please try again.'
            ]);
        }
    }

    public function showVerificationForm()
    {
        return view('auth.email_verification');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $storedCode = session('verification_code');
        $expiresAt = session('verification_code_expires');

        if (!$storedCode) {
            return view('auth.email_verification', [
                'error' => 'No verification code found. Please request a new one.'
            ]);
        }

        if (now()->greaterThan($expiresAt)) {
            session()->forget(['verification_code', 'verification_code_expires']);
            return view('auth.email_verification', [
                'error' => 'Verification code expired. Please request a new one.'
            ]);
        }

        // Verify code
        if ($request->code != $storedCode) {
            return view('auth.email_verification', [
                'error' => 'Invalid verification code.'
            ]);
        }

        // Mark email as verified
        $user = auth()->user();
        $user->email_verified_at = now();
        $user->save();

        session()->forget(['verification_code', 'verification_code_expires']);

        // dd('end');
        // return redirect(url('home'));

    }
}
