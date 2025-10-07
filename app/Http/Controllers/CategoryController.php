<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
         // $this->middleware(['auth', 'email']);
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::all();
        $sub = SubCategory::all();
        return view('admin.category.create', ['category' => $category, 'sub' => $sub]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = Category::create($request->all());
        $category->save();
        return back()->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }

    public function storeSubcategory(Request $request)
    {
        $subcate = SubCategory::create($request->all());
        $subcate->save();
        return back()->with('success', 'SubCategory created successfully');
    }

    public function updateSubcategory(Request $request){

        for ($i = 0; $i < count($request->id); $i++) {
            $id = $request->id[$i];
            $usd = $request->usd[$i];
            $record = SubCategory::find($id);
            if ($record) {
                $record->usd = $usd;
                $record->save();
            }
        }
        return back()->with('success', 'SubCategory updated successfully');
    }

    public function updateSubcategoryNaira(Request $request){
        for ($i = 0; $i < count($request->id); $i++) {
            $id = $request->id[$i];
            $amount = $request->amount[$i];
            $record = SubCategory::find($id);
            if ($record) {
                $record->amount = $amount;
                $record->save();
            }
        }
        return back()->with('success', 'SubCategory updated successfully');
    }
}
