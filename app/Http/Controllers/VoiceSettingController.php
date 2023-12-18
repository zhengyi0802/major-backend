<?php
namespace App\Http\Controllers;

use App\Models\VoiceSetting;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\ApkManager;
use App\Models\User;
use App\Http\Middleware\PackageUpload;
use Illuminate\Http\Request;

class VoiceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $proj_id = $user->proj_id;

        if ($proj_id > 0) {
            $voicesettings = VoiceSetting::where('proj_id', $proj_id)->get();
        } else {
            $voicesettings = VoiceSetting::get();
        }

        return view('voicesettings.index', compact('voicesettings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('status', true)->get();

        return view('voicesettings.create', compact('projects'));
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
            'keywords' => 'required',
            'status'   => 'required',
        ]);

        if ($request->file()) {
            $apkmanager = new ApkManager;
            $filename = $request->app_file->getClientOriginalName();
            $file = PackageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('apkmanager', $fileName);
            }
            $data = PackageUpload::getPackageInfo($file->file_path, $filename);
            $apkmanager->launcher_id = -1;
            $apkmanager->status = true;
            $apkmanager->label = $data['label'];
            $apkmanager->package_name = $data['package_name'];
            $apkmanager->package_version_name = $data['package_version_name'];
            $apkmanager->package_version_code = $data['package_version_code'];
            $apkmanager->sdk_version = $data['sdk_version'];
            $apkmanager->icon = $data['icon'];
            $apkmanager->path = $data['package_path'];
            $apkmanager->save();

            $request->merge(['label' => $apkmanager->label]);
            $request->merge(['package' => $apkmanager->package_name]);
            $request->merge(['link_url' => $apkmanager->path]);
        }
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        VoiceSetting::create($request->all());

        return redirect()->route('voicesettings.index')
                        ->with('success','Voice Setting created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoiceSetting  $voiceSetting
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceSetting $voicesetting)
    {
        return view('voicesettings.show', compact('voicesetting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoiceSetting  $voiceSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(VoiceSetting $voicesetting)
    {
        $projects = Project::where('status', true)->get();

        return view('voicesettings.edit', compact('voicesetting'))
               ->with(compact('projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoiceSetting  $voiceSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoiceSetting $voicesetting)
    {
        $request->validate([
            'keywords' => 'required',
            'status'   => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        $voicesetting->update($request->all());

        return redirect()->route('voicesettings.index')
                        ->with('success','Voice Setting updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoiceSetting  $voiceSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoiceSetting $voicesetting)
    {
        $voicesetting->delete();

        return redirect()->route('voicesettings.index')
                        ->with('success','Voice Setting deleted successfully');
    }

    public function query(Request $request)
    {
        $product = null;
        $proj_id = $this->checkProject($request);
        $result = null;
        $voicesettings = VoiceSetting::select('keywords', 'label', 'package', 'link_url')->where('proj_id', $proj_id)->where('status', true)->get();
        if ($voicesettings) {
            $result = $voicesettings->toArray();
        }
        $response = json_encode($result);
        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'VoiceSetting',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }

   function checkProject(Request $request)
    {
        $data = $request->all();
        if (isset($data['mac'])) {
            $mac = str_replace(':', '', $data['mac']);
            $mac = strtoupper($mac);
            $mproduct = Product::where('ether_mac', '=', $mac)
                               ->orWhere('wifi_mac', '=', $mac)
                               ->first();
        } else {
            $mproduct = null;
        }
        if (isset($data['aid'])) {
            $aproduct = Product::where('android_id', $data['aid'])->first();
            $aid = $data['aid'];
        } else {
            $aproduct = null;
            $aid = null;
        }
        if (isset($data['id'])) {
            $proJ_id = $data['id'];
        }
        if ($aproduct != null) {
            $proj_id = $aproduct->proj_id;
        } else {
            if ($mproduct != null) {
                $proj_id = $mproduct->proj_id;
                $mproduct->android_id = $aid;
                $mproduct->save();
            } else if (false) {
                $project = Project::where('is_default', true)->first();
                $proj_id = $project->id;
                if ($aid != null) {
                    $arr = [
                         'android_id'   => $aid,
                          'serialno'     => 'voicesetting',
                          'type_id'      => 14,
                          'status_id'    => 1,
                          'proj_id'      => $proj_id,
                          'user_id'      => 2,
                          'wifi_mac'     => $mac,
                          'expire_date'  => '2075-12-31 00:00:00',
                          'query_string' => json_encode($data),
                    ];
                    $product = Product::create($arr);
                }
            }
        }

        return $proj_id;
    }

}
