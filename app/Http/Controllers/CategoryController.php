<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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
        // return $request;
        $subCate = SubCategory::where('category_id', $request->id)->get();
        $usds = $request->usd;

        // $data['usd'] = $usds;
        // $data['ids'] = $request->id;//$subCate['id'];
    //  $data =[
    //         ['usd' => $usds],
    //         ['id' => $request->id]
    //     ];

        $data = [
            "usd" => ["1", "2", "4.5"],
            "id" => ["3","3","3"]
        ];

                // Get the single "id" value
        $id = $data['id'][0];

        foreach ($data['usd'] as $key => $usdValue) {
            $id = $data['id'][$key];
        
            // Update the record in the database
            SubCategory::where('category_id', $id)->update(['usd' => $usdValue]);
        }

        // Loop through the "usd" array and update all the records in the database
        // foreach ($data['usd'] as $usdValue) {
        //     // Update the record in the database
        //     SubCategory::where('category_id', $id)->update(['usd' => $usdValue]);
        //     //CurrencyData::where('id', $id)->update(['usd' => $usdValue]);
        // }

        // $count = count($data['usd']);

        // $countId = count($data['id']);

        // // Ensure both arrays have the same number of elements
        // if ($count !== $countId) {
        //     throw new \InvalidArgumentException('The "usd" and "id" arrays must have the same number of elements.');
        // }
        // for ($i = 0; $i < $count; $i++) {
        //     $usdValue = $data['usd'][$i];
        //     $id = $data['id'][$i];
        
        //     // Update the record in the database
        //     SubCategory::where('category_id', $id)->update(['usd' => $usdValue]);
        // }

        // foreach ($data['usd'] as $key => $usd) {
        //     $id = $data['id'];
    
        //     \DB::table('sub_categories')
        //         ->where('category_id', $id)
        //         ->update(['usd' => $usd]);
        // }

        
        return back()->with('success', 'SubCategory created successfully');
    }
}
