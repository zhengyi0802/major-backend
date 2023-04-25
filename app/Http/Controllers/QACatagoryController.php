<?php

namespace App\Http\Controllers;

use App\Models\QACatagory;
use App\Models\User;
use Illuminate\Http\Request;

class QACatagoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $user = auth()->user();

       $qacatagories = QACatagory::get();

        return view('qacatagories.index', compact('qacatagories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('qacatagories.create');
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
            'position' => 'required',
            'name'     => 'required',
            'status'   => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        QACatagory::create($request->all());

        return redirect()->route('qacatagories.index')
                        ->with('success','QACatagory created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QACatagory  $qACatagory
     * @return \Illuminate\Http\Response
     */
    public function show(QACatagory $qacatagory)
    {
        return view('qacatagories.show', compact('qacatagory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QACatagory  $qACatagory
     * @return \Illuminate\Http\Response
     */
    public function edit(QACatagory $qacatagory)
    {
        return view('qacatagories.edit', compact('qacatagory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QACatagory  $qACatagory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QACatagory $qacatagory)
    {
        $request->validate([
            'position' => 'required',
            'name'     => 'required',
            'status'   => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        $qacatagory->update($request->all());

        return redirect()->route('qacatagories.index')
                        ->with('success','QACatagory updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QACatagory  $qACatagory
     * @return \Illuminate\Http\Response
     */
    public function destroy(QACatagory $qacatagory)
    {
        $qacatagory->delete();

        return redirect()->route('qacatagories.index')
                        ->with('success', 'QACatagory deleted successfully');
    }

    public function query(Request $request)
    {
        $qacatagories = QACatagory::where('status', true)->get();


        if ($qacatagories != null)
            return json_encode($qacatagories);
    }

}
