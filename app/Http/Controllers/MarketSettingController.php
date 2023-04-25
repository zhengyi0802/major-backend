<?php

namespace App\Http\Controllers;

use App\Models\MarketSetting;
use App\Models\Project;
use App\Models\ApkManager;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\User;
use Illuminate\Http\Request;

class MarketSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $marketsettings = MarketSetting::get();

        return view('marketsettings.index',compact('marketsettings'));
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

        return view('marketsettings.create', compact('projects'))
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
        MarketSetting::create($request->all());

        return redirect()->route('marketsettings.index')
                        ->with('success','Market Settings added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MarketSetting  $marketSetting
     * @return \Illuminate\Http\Response
     */
    public function show(MarketSetting $marketsetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MarketSetting  $marketSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(MarketSetting $marketsetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MarketSetting  $marketSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarketSetting $marketsetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MarketSetting  $marketsetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarketSetting $marketsetting)
    {
        $marketsetting->delete();

        return redirect()->route('marketsettings.index')
                        ->with('success','Market Settings deleted successfully');
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
                return json_encode(null);
            }
        } else if ($request->input('id')) {
            $proj_id = $request->input('id');
        }

        $apks = MarketSetting::leftJoin('apk_managers', 'apk_id', 'apk_managers.id')
                               ->select('apk_managers.label', 'apk_managers.package_name',
                                        'apk_managers.icon as thumbnail', 'apk_managers.path as url',
                                        'market_settings.flag as flag')
                               ->where('market_settings.status', true)
                               ->where('market_settings.proj_id', $proj_id)
                               ->orWhere('market_settings.proj_id', 0)
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
                      'keywords'    => 'MarketSetting',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }

}
