<?php

namespace App\Http\Controllers;

use App\Models\ProductQuery;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $numbers = 20;

        $records = ProductQuery::latest()->paginate($numbers);

        if ($records) {
            foreach($records as $record) {
                $mac_array = str_split($record->ether_mac, 2);
                $macaddress = implode(':', $mac_array);
                $record->ether_mac = $macaddress;
                $mac_array = str_split($record->wifi_mac, 2);
                $macaddress = implode(':', $mac_array);
                $record->wifi_mac = $macaddress;
            }
        }

        return view('product_queries.index', compact('records'))
              ->with('i', (request()->input('page', 1) - 1) * $numbers);

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductQuery  $productQuery
     * @return \Illuminate\Http\Response
     */
    public function show(ProductQuery $product_query)
    {
        return view('product_queries.show', compact('product_query'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductQuery  $productQuery
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductQuery $productQuery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductQuery  $productQuery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductQuery $productQuery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductQuery  $productQuery
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductQuery $productQuery)
    {
        //
    }
}
