<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ImageUpload;
use App\Http\Middleware\PackageUpload;
use App\Models\AppMenu;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\ApkManager;
use App\Models\User;
use Illuminate\Http\Request;

class AppMenuController extends Controller
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
           $appmenus = AppMenu::where('proj_id', $proj_id)->get();
        } else {
           $appmenus = AppMenu::get();
        }
        return view('appmenus.index', compact('appmenus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('status', true)->get();

        return view('appmenus.create', compact('projects'));
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
            'proj_id'      => 'required',
            'position'     => 'required',
            'status'       => 'required',
        ]);

        $appmenu = new AppMenu;
        $user = auth()->user();

        $appmenu->proj_id  = $request->proj_id;
        $appmenu->position = $request->position;
        $appmenu->status   = $request->status;
        $appmenu->label    = $request->label;
        $appmenu->user_id  = $user->id;

        if ($request->file()) {
            if ($request->image) {
               $file = ImageUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('image', $fileName);
                }
                $appmenu->name      = $request->name;
                $appmenu->url       = $request->url;
                $appmenu->thumbnail = env('APP_URL').$file->file_path;
            }
            if ($request->app_file) {
                $apkmanager = new ApkManager;
                $filename = $request->app_file->getClientOriginalName();
                $file = PackageUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('apkmanager', $fileName);
                }
                $data = PackageUpload::getPackageInfo($file->file_path, $filename);
                $apkmanager->launcher_id          = -1;
                $apkmanager->status               = true;
                $apkmanager->label                = $data['label'];
                $apkmanager->package_name         = $data['package_name'];
                $apkmanager->package_version_name = $data['package_version_name'];
                $apkmanager->package_version_code = $data['package_version_code'];
                $apkmanager->sdk_version          = $data['sdk_version'];
                $apkmanager->icon                 = $data['icon'];
                $apkmanager->path                 = $data['package_path'];
                $apkmanager->save();

                $appmenu->label     = $data['label'];
                $appmenu->name      = $data['package_name'];
                $appmenu->url       = $data['package_path'];
                $appmenu->thumbnail = $data['icon'];
            }
        }
        $appmenu->save();

        return redirect()->route('appmenus.index')
                        ->with('success','AppMenu created successfully');
    }

    public function store2(Request $request, Project $project, $position)
    {
        $request->validate([
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();

        $data['proj_id']  = $project->id;
        $data['position'] = $position;
        $data['label']    = $request->input('label');
        $data['name']     = $request->input('name');
        $data['user_id']  = $user->id;
        if ($request->file()) {
            if ($request->image) {
                $file = ImageUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('image', $fileName);
                }
                $data['thumbnail'] = env('APP_URL').$file->file_path;
            }
            if ($request->app_file) {
                $apkmanager = new ApkManager;
                $filename = $request->app_file->getClientOriginalName();
                $file = PackageUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('apkmanager', $fileName);
                }
                $package = PackageUpload::getPackageInfo($file->file_path, $filename);
                $apkmanager->launcher_id          = -1;
                $apkmanager->status               = true;
                $apkmanager->label                = $package['label'];
                $apkmanager->package_name         = $package['package_name'];
                $apkmanager->package_version_name = $package['package_version_name'];
                $apkmanager->package_version_code = $package['package_version_code'];
                $apkmanager->sdk_version          = $package['sdk_version'];
                $apkmanager->icon                 = $package['icon'];
                $apkmanager->path                 = $package['package_path'];
                $apkmanager->save();

                $data['label']     = $package['label'];
                $data['name']      = $package['package_name'];
                $data['url']       = $package['package_path'];
                $data['thumbnail'] = $package['icon'];
            }
        }

       AppMenu::create($data);
       return redirect()->route('frontend_views.edit', compact('project'))
                        ->with('success','AppMenu created successfully.');
    }

    public function update2(Request $request, Project $project, $position, AppMenu $appmenu)
    {
        $request->validate([
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['proj_id']  = $project->id;
        $data['position'] = $position;
        $data['label']    = $request->input('label');
        $data['name']     = $request->input('name');
        $data['user_id']  = $user->id;

        if ($request->file()) {
            if ($request->image) {
                $file = ImageUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('image', $fileName);
                }
                $data['thumbnail'] = env('APP_URL').$file->file_path;
            }
            if ($request->app_file) {
                $apkmanager = new ApkManager;
                $filename = $request->app_file->getClientOriginalName();
                $file = PackageUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('apkmanager', $fileName);
                }
                $package = PackageUpload::getPackageInfo($file->file_path, $filename);
                $apkmanager->launcher_id          = -1;
                $apkmanager->status               = true;
                $apkmanager->label                = $package['label'];
                $apkmanager->package_name         = $package['package_name'];
                $apkmanager->package_version_name = $package['package_version_name'];
                $apkmanager->package_version_code = $package['package_version_code'];
                $apkmanager->sdk_version          = $package['sdk_version'];
                $apkmanager->icon                 = $package['icon'];
                $apkmanager->path                 = $package['package_path'];
                $apkmanager->save();

                $data['label']     = $package['label'];
                $data['name']      = $package['package_name'];
                $data['url']       = $package['package_path'];
                $data['thumbnail'] = $package['icon'];
            }
        }

        $appmenu->update($data);

        return redirect()->route('frontend_views.edit', compact('project'))
                        ->with('success','AppMenu created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppMenu  $appMenu
     * @return \Illuminate\Http\Response
     */
    public function show(AppMenu $appmenu)
    {
        return view('appmenus.show', compact('appmenu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AppMenu  $appMenu
     * @return \Illuminate\Http\Response
     */
    public function edit(AppMenu $appmenu)
    {
        return view('appmenus.edit', compact('appmenu'));
    }

    public function edit2(Project $project, $position)
    {
        $appmenu = AppMenu::where('proj_id', $project->id)
                          ->where('position', $position)
                          ->orderBy('updated_at', 'desc')
                          ->first();

        if ($appmenu == null) {
            $appmenu = new AppMenu;
            $appmenu->id = 0;
            $appmenu->status = true;
            $appmenu->proj_id = $project->id;
            $appmenu->position = $position;
            return view('appmenus.create2', compact('appmenu'))
                   ->with(compact('project'))
                   ->with('position', $position);
        }

        return view('appmenus.edit2', compact('appmenu'))
               ->with(compact('project'))
               ->with('position', $position);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AppMenu  $appMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppMenu $appmenu)
    {
        $request->validate([
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['label']     = $request->input('label');
        $data['name']      = $request->input('name');
        $data['url']       = $request->input('url');
        $data['user_id']   = $user->id;

        if ($request->file()) {
            if ($request->image) {
                $file = ImageUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('image', $fileName);
                }
                $data['thumbnail'] = env('APP_URL').$file->file_path;
            }
            if ($request->app_file) {
                $apkmanager = new ApkManager;
                $filename = $request->app_file->getClientOriginalName();
                $file = PackageUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('apkmanager', $fileName);
                }
                $data1 = PackageUpload::getPackageInfo($file->file_path, $filename);
                $apkmanager->launcher_id          = -1;
                $apkmanager->status               = true;
                $apkmanager->label                = $data1['label'];
                $apkmanager->package_name         = $data1['package_name'];
                $apkmanager->package_version_name = $data1['package_version_name'];
                $apkmanager->package_version_code = $data1['package_version_code'];
                $apkmanager->sdk_version          = $data1['sdk_version'];
                $apkmanager->icon                 = $data1['icon'];
                $apkmanager->path                 = $data1['package_path'];
                $apkmanager->save();

                $data['label']     = $data1['label'];
                $data['name']      = $data1['package_name'];
                $data['url']       = $data1['package_path'];
                $data['thumbnail'] = $data1['icon'];
            }
        }
        $appmenu->update($data);

        return redirect()->route('appmenus.index')
                        ->with('success','AppMenu updated successfully - '. $data['label']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppMenu  $appMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppMenu $appmenu)
    {
        $appmenu->delete();

        return redirect()->route('appmenus.index')
                        ->with('success','APP Menus deleted successfully');
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
            } else {
                return json_encode(null);
            }
        } else if ($request->input('id')) {
            $proj_id = $request->input('id');
        }

        $appmenus = AppMenu::where('proj_id', $proj_id)
                             ->where('status', true)
                             ->get();
        if ($appmenus) {
            $results = array();
            foreach ($appmenus as $appmenu) {
                     $item = array(
                          'position'          => $appmenu->position,
                          'label'             => $appmenu->label,
                          'package_name'      => $appmenu->name,
                          'thumbnail'         => $appmenu->thumbnail,
                          'url'               => $appmenu->url,
                     );
                     array_push($results, $item);
            }
            $response = json_encode($results);
            if ($product && ProductQuery::enabled()) {
                $record = array(
                          'product_id'  => $product->id,
                          'keywords'    => 'AppMenu',
                          'query'       => $request->fullUrl(),
                          'response'    => $response,
                );
                ProductQuery::create($record);
            }
            return $results;
        } else return json_encode(null);
    }

}
