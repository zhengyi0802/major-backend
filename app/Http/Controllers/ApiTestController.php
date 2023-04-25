<?php

namespace App\Http\Controllers;

use App\Models\ApiTest;
use App\Models\Project;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ApiTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $apitests = ApiTest::get();

        return view('apitests.index', compact('apitests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $projects = Project::where('status', true)->get();

        return view('apitests.create', compact('projects'))
               ->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $apitest = new ApiTest;
        $apitest->user_id  = $user->id;
        $apitest->proj_id  = $request->proj_id;
        $apitest->type     = $request->type;
        $apitest->key      = $request->key;
        $apitest->value    = $request->value;
        $apitest->memo     = $request->memo;
        $apitest->status   = $request->status;
        $apitest->save();

        return redirect()->route('apitests.index')
                        ->with('success', 'API Test data store successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApiTest  $apiTest
     * @return \Illuminate\Http\Response
     */
    public function show(ApiTest $apitest)
    {
        return view('apitests.show', compact('apitest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApiTest  $apiTest
     * @return \Illuminate\Http\Response
     */
    public function edit(ApiTest $apitest)
    {
        $id = $apitest->id;
        $projects = Project::where('status', true)->get();

        return view('apitests.edit', compact('apitest'))
               ->with(compact('projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApiTest  $apiTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApiTest $apitest)
    {
        $apitest->update($request->all());

        return redirect()->route('apitests.index')
                        ->with('success', 'API Test data update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApiTest  $apiTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApiTest $apitest)
    {
        $apitest->delete();

        return redirect()->route('apitests.index')
                        ->with('success', 'API Test data delete successfully');
    }

    public function query(Request $request)
    {
        $mac = $request->input("mac");
        $key = $request->input("key");
        $proj_id = 0;

        if ($mac) {
            $product = Product::where("ether_mac", $mac)
                          ->orWhere("wifi_mac", $mac)
                          ->first();

            if ($product == null) {
                $proj_id = 0;
            } else {
                $proj_id = $product->proj_id;
            }
        }
        $apitest = ApiTest::select('type', 'key', 'value', 'status', 'updated_at')
                          ->where('proj_id', $proj_id)
                          ->where('key', $key)
                          ->where('status', true)
                          ->first();

        if ($apitest == null) {
             $apitest = ApiTest::select('type', 'key', 'value', 'status', 'updated_at')
                          ->where('proj_id', 0)
                          ->where('key', $key)
                          ->where('status', true)
                          ->first();
        }

        if ($apitest == null) {
            return "null";
        }

        return json_encode($apitest);
    }

}
