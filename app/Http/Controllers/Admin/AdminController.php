<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Games;
use App\Models\Question;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createGame()
    {
        $user = auth()->user();

        if($user->hasRole('admin'))
        {
            //$game = Games::find($id);
            return view('admin.create_game');
        }
    }

    public function createQuestion()
    {
        return view('admin.add_question');
    }

    publiC function storeQuestion(Request $request)
    {   
        $question = Question::create($request->all());
        $question->save();
        return back()->with('status', 'Question Created Successfully');
    }

    public function gameStatus($id)
    {

        $game = Games::where('id', $id)->first();

        if($game->status == '1'){
            $game->status = '0';
            $game->save();
        }else{
            $game->status = '1';
            $game->save();
        }

         return back()->with('status', 'Status Changed Successfully');

    }

    public function gameCreate()
    {
        $user = auth()->user();

        if($user->hasRole('admin'))
        {
            //$game = Games::find($id);
            return view('admin.create_game');
        }
    }

    public function gameStore(Request $request)
    {

        $slug = Str::slug($request->name);
        $game = Games::create(['name' => $request->name, 'type' => $request->type, 'number_of_winners' => $request->number_of_winners, 'slug' => $slug]);
        // $game->save();

        return back()->with('status', 'Game Created Successfully');

    }

}
