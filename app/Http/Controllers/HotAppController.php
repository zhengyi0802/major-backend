<?php

namespace App\Http\Controllers;

use App\Models\HotApp;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\ApkManager;
use App\Models\AppManager;
use App\Models\User;
use Illuminate\Http\Request;

class HotAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $hotapps = HotApp::get();

        return view('hotapps.index',compact('hotapps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('status', true)->get();
        $apkmanagers = ApkManager::where('status', true)->get();

        return view('hotapps.create', compact('projects'))
               ->with(compact('apkmanagers'));
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
        $request->merge(['user_id' => $user->id]);

        HotApp::create($request->all());

        return redirect()->route('hotapps.index')
                        ->with('success','Hot App added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HotApp  $hotApp
     * @return \Illuminate\Http\Response
     */
    public function show(HotApp $hotApp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HotApp  $hotApp
     * @return \Illuminate\Http\Response
     */
    public function edit(HotApp $hotApp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HotApp  $hotApp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotApp $hotApp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HotApp  $hotApp
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotApp $hotapp)
    {
        $hotapp->delete();

        return redirect()->route('hotapps.index')
                        ->with('success','Hot App deleted successfully');
    }

    public function query(Request $request)
    {
        if ($request->input('mac')) {
            $mac = str_replace(':', '', $request->input('mac'));
            $mac = strtoupper($mac);
            $product = Product::where('ether_mac', '=', $mac)
                              ->orWhere('wifi_mac', '=', $mac)
                              ->first();
            //var_dump($product);
            if ($product) {
                $proj_id = $product->proj_id;
                //var_dump($proj_id);
            } else {
                $proj = Project::where('is_default', true)->first();
                $proj_id = $proj->id;
            }
        } else if ($request->input('id')) {
            $proj_id = $request->input('id');
        }

        $apks = HotApp::leftJoin('apk_managers', 'apk_id', 'apk_managers.id')
                               ->select('apk_managers.label', 'apk_managers.package_name',
                                        'apk_managers.icon as thumbnail', 'apk_managers.path as url')
                               ->where('hot_apps.status', true)
                               ->where('hot_apps.proj_id', $proj_id)
                               ->orWhere('hot_apps.proj_id', 0)
                               ->get();
        if ($apks != null) {
            $result = $apks->toArray();
        } else {
            return json_encode(null);
        }
        $response = json_encode($result);
        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'HotApp',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }

}
