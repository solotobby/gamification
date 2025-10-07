<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKnowledgeBaseRequest;
use App\Http\Requests\UpdateKnowledgeBaseRequest;
use App\Models\KnowledgeBase;

class KnowledgeBaseController extends Controller
{
    public function __construct()
    {
         // $this->middleware(['auth', 'email']);
        $this->middleware('auth');
    }

    public function adminList(){
        $knowledgeBase = KnowledgeBase::all();
        return view('admin.knowledgebase.index', ['knowledgebase' => $knowledgeBase]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listKnowledgeBase = KnowledgeBase::where('status', true)->get();

        foreach ($listKnowledgeBase as $question) {
            $category = $question['category'];
            $groupedQuestions[$category][] = $question;
        }
        // return $groupedQuestions;
        return view('user.knowledgebase.index', ['lists' => $groupedQuestions]);
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
     * @param  \App\Http\Requests\StoreKnowledgeBaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKnowledgeBaseRequest $request)
    {
        KnowledgeBase::create($request->all());
        return back()->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KnowledgeBase  $knowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KnowledgeBase  $knowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function edit(KnowledgeBase $knowledgeBase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKnowledgeBaseRequest  $request
     * @param  \App\Models\KnowledgeBase  $knowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKnowledgeBaseRequest $request, KnowledgeBase $knowledgeBase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KnowledgeBase  $knowledgeBase
     * @return \Illuminate\Http\Response
     */
    public function destroy(KnowledgeBase $knowledgeBase)
    {
        //
    }
}
