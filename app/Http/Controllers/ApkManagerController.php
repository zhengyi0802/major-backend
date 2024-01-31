<?php

namespace App\Http\Controllers;

use App\Models\ApkManager;
use App\Models\Project;
use App\Models\ProductType;
use App\Models\Product;
use App\Models\User;
use App\Http\Middleware\PackageUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ApkManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $apkmanagers = ApkManager::get();

        return view('apkmanagers.index', compact('apkmanagers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('status', true)->get();
        $types = ProductType::where('status', true)->get();

        return view('apkmanagers.create')
               ->with(compact('projects'))
               ->with(compact('types'));
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
                 'app_file' => 'required',
        ]);

        $values = $request->all();
        $user = auth()->user();
        if ($request->file()) {
           $filename = $request->app_file->getClientOriginalName();
           $file = PackageUpload::fileUpload($request);
           if ($file == null) {
               return back()->with('apkmanager', $fileName);
           }
           $data = PackageUpload::getPackageInfo($file->file_path, $filename);
           $values['label'] = $data['label'];
           $values['package_name'] = $data['package_name'];
           $values['package_version_name'] = $data['package_version_name'];
           $values['package_version_code'] = $data['package_version_code'];
           $values['sdk_version'] = $data['sdk_version'];
           $values['icon'] = $data['icon'];
           $values['path'] = $data['package_path'];
/*
           $request->merge(['label' => $data['label']]);
           $request->merge(['package_name' => $data['package_name']]);
           $request->merge(['package_version_name' => $data['package_version_name']]);
           $request->merge(['package_version_code' => $data['package_version_code']]);
           $request->merge(['sdk_version' => $data['sdk_version']]);
           $request->merge(['icon' => $data['icon']]);
           $request->merge(['path' => $data['package_path']]);
*/
        }
        $types = $request->input('type');
        $projects = $request->input('project');
        $mac_addresses = trim($request->input('mac_addresses'));
        $macaddresses = explode("\r\n", $mac_addresses);
        $mac_array = array();
        foreach ($macaddresses as $macaddress) {
                $mac = strtoupper(str_replace(':', '', $macaddress));
                array_push($mac_array, $mac);
        }
        if (count($mac_array) > 0) {
           $values['mac_addresses'] = json_encode($mac_array);
        } else {
           $values['mac_addresses'] = null;
        }
        $values['type_id'] = json_encode($types);
        $values['proj_id'] = json_encode($projects);
        //$request->merge(['type_id' => json_encode($types)]);
        //$request->merge(['proj_id' => json_encode($projects)]);
        $values['user_id'] = $user->id;

        ApkManager::create($values);

        return redirect()->route('apkmanagers.index')
                        ->with('success', 'APK Package store successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApkManager  $apkManager
     * @return \Illuminate\Http\Response
     */
    public function show(ApkManager $apkmanager)
    {
        return view('apkmanagers.show', compact('apkmanager'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApkManager  $apkManager
     * @return \Illuminate\Http\Response
     */
    public function edit(ApkManager $apkmanager)
    {
        $projects = Project::where('status', true)->get();
        $types = ProductType::where('status', true)->get();

        return view('apkmanagers.edit', compact('apkmanager'))
               ->with(compact('projects'))
               ->with(compact('types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApkManager  $apkManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApkManager $apkmanager)
    {
        return redirect()->route('apkmanagers.index')
                        ->with('success', 'APK Package update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApkManager  $apkManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApkManager $apkmanager)
    {
        $apkmanager->delete();

        return redirect()->route('apkmanagers.index')
                        ->with('success', 'APK Package deleted successfully');
    }

    public function checkLauncher($launcher_id)
    {
        $result = null;
        $package = ApkManager::where('status', true)
                                 ->where('launcher_id', $launcher_id)
                                 ->orderBy('created_at', 'DESC')
                                 ->first();
        if ($package) {
            $result = array (
                  'label'                => $package->label,
                  'package_name'         => $package->package_name,
                  'package_version_name' => $package->package_version_name,
                  'package_version_code' => $package->package_version_code,
                  'sdk_version'          => $package->sdk_version,
                  'icon'                 => $package->icon,
                  'path'                 => $package->path,
                  'description'          => $package->description,
                  'created_at'           => $package->created_at,
            );
        }

        return $result;
    }

    public function checkMAC($mac, $type, $launcher)
    {
        $result = null;
        $packages = ApkManager::where('launcher_id', $launcher)
                             ->where('status', true)
                             ->orderBy('created_at', 'desc')
                             ->get();

        foreach ($packages as $package) {
            if ($package) {
                $macaddress = json_decode($package->mac_addresses);
                //var_dump($macaddress);
                if ( is_array($macaddress) && in_array($mac, $macaddress) ) {
                    $result = array (
                      'label'                => $package->label,
                      'package_name'         => $package->package_name,
                      'package_version_name' => $package->package_version_name,
                      'package_version_code' => $package->package_version_code,
                      'sdk_version'          => $package->sdk_version,
                      'icon'                 => $package->icon,
                      'path'                 => $package->path,
                      'description'          => $package->description,
                      'created_at'           => $package->created_at,
                    );
                    break;
                }
                if ( $macaddress[0] == null ) {
                    $types = json_decode($package->type_id);
                    if (is_array($types) && in_array($type, $types)) {
                        $result = array (
                          'label'                => $package->label,
                          'package_name'         => $package->package_name,
                          'package_version_name' => $package->package_version_name,
                          'package_version_code' => $package->package_version_code,
                          'sdk_version'          => $package->sdk_version,
                          'icon'                 => $package->icon,
                          'path'                 => $package->path,
                          'description'          => $package->description,
                          'created_at'           => $package->created_at,
                        );
                        break;
                    }
                }
            }
        }
/*
        if ($result == null) {
            $result = $this->checkLauncher($launcher);
        }
*/
        return $result;
    }

    public function checkAID($type, $launcher)
    {
        $result = null;
        $packages = ApkManager::where('launcher_id', $launcher)
                              ->where('status', true)
                              ->orderBy('created_at', 'desc')
                              ->get();

        foreach($packages as $package) {
                $types = json_decode($package->type_id);
                if (is_array($types) && in_array($type, $types)) {
                    $result = array (
                          'label'                => $package->label,
                          'package_name'         => $package->package_name,
                          'package_version_name' => $package->package_version_name,
                          'package_version_code' => $package->package_version_code,
                          'sdk_version'          => $package->sdk_version,
                          'icon'                 => $package->icon,
                          'path'                 => $package->path,
                          'description'          => $package->description,
                          'created_at'           => $package->created_at,
                        );
                        break;
                }
        }
        return $result;
    }

    public function query(Request $request)
    {
        $result = null;
        if ($request->input('aid')) {
            $aid = $request->input('aid');
            $product = Product::where('android_id', $aid)->first();
            if ($request->input('launcher')) {
                $launcher = $request->input('launcher');
            } else {
                $launcher = -1;
            }
            if ($product == null) {
                $producttype = ProductType::find(14);
            } else {
                $producttype = ProductType::where('id', $product->type_id)->first();
            }
            $type = $producttype->name;
            $result = $this->checkAID($type, $launcher);
        } else if ($request->input('mac')) {
            $mac = str_replace(':', '', $request->input('mac'));
            $mac = strtoupper($mac);
            $product = Product::where('ether_mac', $mac)->orWhere('wifi_mac', $mac)->first();
/*
            if ($product == null) {
                return json_encode(null);
            }
*/
            if ($request->input('launcher')) {
                $launcher = $request->input('launcher');
            } else {
                $launcher = -1;
            }
            if ($product == null) {
                $producttype = ProductType::find(14);
            } else {
                $producttype = ProductType::where('id', $product->type_id)->first();
            }
            $type = $producttype->name;
            $result = $this->checkMAC($mac, $type, $launcher);
        } else if ($request->input('launcher')) {
            $result = $this->checkLauncher($request->input('launcher'));
        }

        return json_encode($result);
    }

}
