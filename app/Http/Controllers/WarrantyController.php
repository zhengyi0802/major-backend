<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Models\Product;
use Illuminate\Http\Request;

class WarrantyController extends Controller
{
    //
    function index()
    {
        $warranties = Warranty::get();

        return view('warranties.index', compact('warranties'));
    }

    function create()
    {
        return redirect()->route('warranties.index');
    }

    function edit(Warranty $warranty)
    {
        return redirect()->route('warranties.index');
    }

    function store(Request $request)
    {
        return redirect()->route('warranties.index');
    }

    function show(Warranty $warranty)
    {
        return view('warranties.show', compact('warranty'));
    }

    function update(Request $request, Warranty $warranty)
    {
        return redirect()->route('warranties.index');
    }

    function destroy(Warranty $warranty)
    {
        $warranty->delete();
        return redirect()->route('warranties.index');
    }
}
