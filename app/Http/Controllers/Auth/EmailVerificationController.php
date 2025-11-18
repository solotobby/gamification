<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));

            if (is_null($user->email_verification_attempted_at)) {
                $user->email_verification_attempted_at = now()->addDays(60);
                $user->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ], 500);
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
            return response()->json([
                'success' => false,
                'message' => 'No verification code found. Please request a new one.'
            ], 422);
        }

        if (now()->greaterThan($expiresAt)) {
            session()->forget(['verification_code', 'verification_code_expires']);
            return response()->json([
                'success' => false,
                'message' => 'Verification code expired. Please request a new one.'
            ], 422);
        }

        if ($request->code != $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ], 422);
        }

        // Mark email as verified
        $user = auth()->user();
        $user->email_verified_at = now();
        $user->save();

        session()->forget(['verification_code', 'verification_code_expires']);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.'
        ]);
    }

    public function sendNewEmailCode(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email'
        ]);

        $code = random_int(100000, 999999);

        session([
            'new_email_verification_code' => $code,
            'new_email_verification_code_expires' => now()->addMinutes(10),
            'pending_new_email' => $request->new_email
        ]);

        try {
            Mail::to($request->new_email)->send(new VerificationCodeMail($code, auth()->user()->name));

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to new email successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ], 500);
        }
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
            'code' => 'required|digits:6'
        ]);

        $storedCode = session('new_email_verification_code');
        $expiresAt = session('new_email_verification_code_expires');
        $pendingEmail = session('pending_new_email');

        if (!$storedCode || !$pendingEmail) {
            return response()->json([
                'success' => false,
                'message' => 'No verification code found. Please request a new one.'
            ], 422);
        }

        if (now()->greaterThan($expiresAt)) {
            session()->forget(['new_email_verification_code', 'new_email_verification_code_expires', 'pending_new_email']);
            return response()->json([
                'success' => false,
                'message' => 'Verification code expired. Please request a new one.'
            ], 422);
        }

        if ($request->code != $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ], 422);
        }

        if ($request->new_email !== $pendingEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Email mismatch. Please request a new verification code.'
            ], 422);
        }

        // Check again if email is unique (race condition check)
        $emailExists = User::where('email', $request->new_email)->exists();
        if ($emailExists) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already in use.'
            ], 422);
        }

        // Update email and mark as verified
        $user = auth()->user();
        $user->email = $request->new_email;
        $user->email_verified_at = now();
        $user->save();

        session()->forget(['new_email_verification_code', 'new_email_verification_code_expires', 'pending_new_email']);

        return response()->json([
            'success' => true,
            'message' => 'Email updated and verified successfully.'
        ]);
    }
}
