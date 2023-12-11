<?php

namespace App\Http\Controllers;

use App\Models\OneKeyInstaller;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\ApkManager;
use App\Models\AppManager;
use App\Models\User;
use Illuminate\Http\Request;

class OneKeyInstallerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $onekeyinstallers = OneKeyInstaller::get();

        return view('onekeyinstallers.index',compact('onekeyinstallers'));
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

        return view('onekeyinstallers.create', compact('projects'))
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
        $user = auth()->user();
        $data['user_id'] = $user->id;
        if ($data["external_flag"] == 0) {
            $apk = ApkManager::where('id', $data['apk_id'])->first();
            $data['label'] = $apk->label;
            $data['package_name'] = $apk->package_name;
            $data['thumbnail'] = $apk->icon;
            $data['url'] = $apk->path;
        }

        OneKeyInstaller::create($data);

        return redirect()->route('onekeyinstallers.index')
                        ->with('success','One Key Installer added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OneKeyInstaller  $oneKeyInstaller
     * @return \Illuminate\Http\Response
     */
    public function show(OneKeyInstaller $onekeyinstaller)
    {

        $id = $onekeyinstaller->id;

        $onekeyinstaller = OneKeyInstaller::where('id', $id)->first();
        //var_dump($onekeyinstaller);
        return view('onekeyinstallers.show', compact('onekeyinstaller'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OneKeyInstaller  $oneKeyInstaller
     * @return \Illuminate\Http\Response
     */
    public function edit(OneKeyInstaller $onekeyinstaller)
    {
        $projects = Project::where('status', true)->get();

        return view('onekeyinstallers.edit', compact('onekeyinstaller'))
               ->with(compact('projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OneKeyInstaller  $oneKeyInstaller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OneKeyInstaller $onekeyinstaller)
    {
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        $onekeyinstaller->update($request->all());

        return redirect()->route('onekeyinstallers.index')
                        ->with('success','One Key Installer updateed successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OneKeyInstaller  $oneKeyInstaller
     * @return \Illuminate\Http\Response
     */
    public function destroy(OneKeyInstaller $onekeyinstaller)
    {
        $onekeyinstaller->delete();

        return redirect()->route('onekeyinstallers.index')
                        ->with('success','One Key Installer deleted successfully');
    }

    public function query(Request $request)
    {
        if ($request->input('mac')) {
            $mac = str_replace(':', '', $request->input('mac'));
            $mac = strtoupper($mac);
            $product = Product::where('ether_mac', '=', $mac)
                              ->orWhere('wifi_mac', '=', $mac)
                              ->first();
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

        if ($request->input('aid')) {
            $aid = $request->input('aid');
            $product1 = Product::where('android_id', $aid)->first();
            if ($product1 == null) {
                if ($product) {
                    $data = $product->toArray();
                    $data['android_id'] = $request->input('aid');
                    $product->update($data);
                    $proj_id = $product->proj_id;
                } else {
                    $arr = [
                         'android_id'   => $aid,
                         'type_id'      => 14,
                         'status_id'    => 1,
                         'proj_id'      => 9,
                         'user_id'      => 2,
                         'expire_date'  => '2075-12-31 00:00:00',
                    ];
                    $product = Product::create($arr);
                    $proj_id = 9;
                }
            } else {
                $proj_id = $product1->proj_id;
            }
        }

        if (true) {
            $apks = OnekeyInstaller::select('apk_id', 'label', 'package_name', 'thumbnail', 'url')
                               ->where('proj_id', $proj_id)
                               ->orWhere('proj_id', 0)
                               ->where('status', true)
                               ->get();

            $result = array();
            foreach($apks as $apk) {
                  if ($apk->label == null) {
                      $apk2 = ApkManager::select('label', 'package_name as package_name', 'icon as thumbnail', 'path as url')
                                        ->where('id', $apk->apk_id)
                                        ->first();
                      $apk->label = $apk2->label;
                      $apk->package_name= $apk2->package_name;
                      $apk->thumbnail = $apk2->thumbnail;
                      $apk->url = $apk2->url;
                  }
                  array_push($result, $apk);
            }
        } else {
            $apks = OnekeyInstaller::leftJoin('apk_managers', 'apk_id', 'apk_managers.id')
                               ->select('apk_managers.label', 'apk_managers.package_name',
                                        'apk_managers.icon as thumbnail', 'apk_managers.path as url')
                               ->where('one_key_installers.status', true)
                               ->where('one_key_installers.proj_id', $proj_id)
                               ->orWhere('one_key_installers.proj_id', 0)
                               ->get();
            if ($apks != null) {
                $result = $apk->toArray();
            } else {
                return json_encode(null);
            }
        }

        return json_encode($result);
    }

    public function queryOneKeyInstaller($proj_id)
    {
       $result = null;
       $onekeys = OneKeyInstaller::where('status', true)->get();
       $apks = AppManager::leftJoin('apk_managers', 'apk_id', 'apk_managers.id')
                         ->select('apk_managers.package_name', 'app_managers.delaytime')
                         ->where('app_managers.proj_id', $proj_id)
                         ->where('app_managers.installer_flag', true)
                         ->get();

       if ($apks) {
           $result = $apks->toArray();
       }

       return $result;
    }

    public function queryInstaller(Request $request)
    {
        if ($request->input('mac')) {
            $mac = str_replace(':', '', $request->input('mac'));
            $mac = strtoupper($mac);
            $product = Product::where('ether_mac', '=', $mac)
                              ->orWhere('wifi_mac', '=', $mac)
                              ->first();
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

        if ($request->input('aid')) {
            $aid = $request->input('aid');
            $product1 = Product::where('android_id', $aid)->first();
            if ($product1 == null) {
                if ($product) {
                    $data = $product->toArray();
                    $data['android_id'] = $request->input('aid');
                    $product->update($data);
                    $proj_id = $product->proj_id;
                } else {
                    $arr = [
                         'android_id'   => $aid,
                         'type_id'      => 14,
                         'status_id'    => 1,
                         'proj_id'      => 9,
                         'user_id'      => 2,
                         'expire_date'  => '2075-12-31 00:00:00',
                    ];
                    $product = Product::create($arr);
                    $proj_id = 9;
                }
            } else {
                $proj_id = $product1->proj_id;
            }
        }

        $result = $this->queryOneKeyInstaller($proj_id);
        $response = json_encode($result);
        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'OneKeyInstaller',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }

}
