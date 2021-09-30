<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Games;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createGame($id)
    {
        $user = auth()->user();

        if($user->hasRole('admin'))
        {
            $game = Games::find($id);
            return view('admin.create_game', ['game' => $game]);
        }
    }

    publiC function storeQuestion(Request $request)
    {
        // $this->validate($request, [
        //     'pin_id' => 'required|string',
        //     'otp' => 'required|string'
        // ]);

        
    }
}
