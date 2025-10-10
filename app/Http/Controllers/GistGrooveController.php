<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class GistGrooveController extends Controller
{
    public function __construct()
    {
         $this->middleware(['auth', 'email']);
        // $this->middleware('auth');
    }

    public function index(){
        $posts = Post::with('pageViews')
                 ->where('user_id', auth()->user()->id)
                 ->latest()->paginate(5);
        $categories = PostCategory::all();
        return view('user.gistgroove.index', ['posts' => $posts, 'categories' => $categories]);
    }

    public function savePost(Request $request){

        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string'
        ]);

        $slug = Str::slug($request->title);
        $categoryName = PostCategory::where('id', $request->category_id)->first();
        Post::create([
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'category_id' => $request->category_id,
            'category_name' => $categoryName->name,
            'slug' => $slug,
            'title' => $request->title,
            'body' => $request->body
        ]);

        return redirect('success/'.$slug);

        // return back()->with('success', 'Post successfully sent to Gist Groove');


    }

    public function savePostSuccess($slug){
        $checkPost = Post::where('slug', $slug)->first();
        // $status = '';
        // if($checkPost){
        //     return $status = true;
        // }else{
        //     return $status = false;
        // }
        return view('user.gistgroove.success', ['slug' => $slug]);
    }
}
