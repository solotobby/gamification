<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the failed login response instance.
     */
    protected function sendFailedLoginResponse(\Illuminate\Http\Request $request)
    {
        $deletedUser = \App\Models\User::onlyTrashed()->where('email', $request->{$this->username()})->first();
        if ($deletedUser && $deletedUser->deleted_at) {
            $daysPassed = (int) $deletedUser->deleted_at->diffInDays(now());
            $daysLeft = max(0, 60 - $daysPassed);
            $daysText = $daysLeft === 1 ? '1 day' : ($daysLeft === 0 ? 'less than 24 hours' : "{$daysLeft} days");
            $message = "Your account has been scheduled for deletion. You have {$daysText} left before final deletion. If you do not want to delete your account, please contact support (holla@freebyz.com) to help reactivate your account.";

            throw \Illuminate\Validation\ValidationException::withMessages([
                $this->username() => [$message],
            ]);
        }

        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
