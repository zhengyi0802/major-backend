<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\ProductCatagory;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productTypes = ProductType::get();

        return view('product_types.index',compact('productTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productCatagories = ProductCatagory::get();

        return view('product_types.create', compact('productCatagories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'catagory_id' => 'required',
            'status' => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        ProductType::create($request->all());

        return redirect()->route('product_types.index')
                        ->with('success','Product Types created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function show(ProductType $productType)
    {
        return view('product_types.show',compact('productType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductType $productType)
    {
        $productCatagories = ProductCatagory::get();

        return view('product_types.edit', compact('productType'), compact('productCatagories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductType $productType)
    {
        $request->validate([
            'name' => 'required',
            'catagory_id' => 'required',
            'status' => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        $productType->update($request->all());

        return redirect()->route('product_types.index')
                        ->with('success','Product Types created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductType $productType)
    {
        $productType->delete();

        return redirect()->route('product_types.index')
                        ->with('success','Product Type deleted successfully');
    }
}
