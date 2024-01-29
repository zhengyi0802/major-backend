<?php

namespace App\Http\Controllers;

use App\Models\AppManager;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\ApkManager;
use Illuminate\Http\Request;

class AppManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appmanagers = AppManager::get();

        return view('appmanagers.index',compact('appmanagers'));
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

        return view('appmanagers.create', compact('projects'))
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

        $data = $request->all();
        /*
        if (!$data['market_id']) {
            $data['installer_flag'] = false;
            $data['delaytime'] = -1;
        }
        */
        $user = auth()->user();
        $data['user_id'] = $user->id;

        AppManager::create($data);

        return redirect()->route('appmanagers.index')
                        ->with('success','App Manager added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppManager  $appmanager
     * @return \Illuminate\Http\Response
     */
    public function show(AppManager $appmanager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AppManager  $appmanager
     * @return \Illuminate\Http\Response
     */
    public function edit(AppManager $appmanager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AppManager  $appmanager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppManager $appmanager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppManager  $appmanager
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppManager $appmanager)
    {
        $appmanager->delete();

        return redirect()->route('appmanagers.index')
                        ->with('success','App Manager deleted successfully');
    }

    public function query(Request $request)
    {
        if ($request->input('aid')) {
            $aid = $request->input('aid');
            $product = Product::where('android_id', $aid)->first();
            if ($product) {
                $proj_id = $product->proj_id;
            } else {
                $proj = Project::where('is_default', true)->first();
                $proj_id = $proj->id;
            }
        } else if ($request->input('mac')) {
            $mac = str_replace(':', '', $request->input('mac'));
            $mac = strtoupper($mac);
            $product = Product::where('ether_mac', '=', $mac)
                              ->orWhere('wifi_mac', '=', $mac)
                              ->first();

            if ($product) {
                $proj_id = $product->proj_id;
            } else {
                $proj = Project::where('is_default', true)->first();
                $proj_id = $proj->id;
            }
        } else if ($request->input('id')) {
            $proj_id = $request->input('id');
        }

        $apks = AppManager::leftJoin('apk_managers', 'apk_id', 'apk_managers.id')
                          ->select('apk_managers.label', 'apk_managers.package_name',
                                   'apk_managers.icon as thumbnail', 'apk_managers.path as url',
                                   'app_managers.market_id', 'app_managers.installer_flag', 'app_managers.delaytime')
                          ->where('app_managers.status', true)
                          ->where('app_managers.proj_id', $proj_id)
                          ->orWhere('app_managers.proj_id', 0)
                          ->get();
        if ($apks) {
            $result = $apks->toArray();
        } else {
            return json_encode(null);
        }
        $response = json_encode($result);
        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'AppManager',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }

}
