<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\AppAdvertising;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Middleware\ImageUpload;

class AppAdvertisingController extends Controller
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

        if ($proj_id == 0) {
            $appadvertisings = AppAdvertising::get();
        } else {
            $appadvertisings = AppAdvertising::where('proj_id', $proj_id)->get();
        }

        return view('appadvertisings.index', compact('appadvertisings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $proj_id = $user->proj_id;

        if ($proj_id == 0) {
            $projects = Project::where('status', true)->get();
            return view('appadvertisings.create', compact('projects'));
        } else {
            $projects = Project::where('id', $proj_id)->get();
            return view('appadvertisings.create', compact('projects'));
        }
    }

    public function create2(Project $project)
    {
        $appadvertising = new AppAdvertising;
        $appadvertising->id = 0;

        return view('appadvertisings.create2', compact('appadvertising'))
               ->with(compact('project'));
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
            'interval'     => 'required',
            'status'       => 'required',
        ]);

        $user = auth()->user();
        $appadvertising = new AppAdvertising;

        $appadvertising->proj_id  = $request->proj_id;
        $appadvertising->interval = $request->interval;
        $appadvertising->link_url = $request->link_url;
        $appadvertising->status   = $request->status;
        $appadvertising->user_id  = $user->id;

        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $appadvertising->thumbnail = env('APP_URL').$file->file_path;
        }
        $appadvertising->save();

        return redirect()->route('appadvertisings.index')
                        ->with('success','APP Advertising created successfully');

    }

    public function store2(Request $request, Project $project, AppAdvertising $appadvertising)
    {
        $request->validate([
            'interval'     => 'required',
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $data['thumbnail'] = env('APP_URL').$file->file_path;
        } else {
            $data['thumbnail'] = $advertising->thumbnail;
        }

        $data['proj_id'] = $project->id;
        $data['user_id'] = $user->id;
        if ($appadvertising->id > 0) {
            $appadvertising->update($data);
        } else {
            AppAdvertising::create($data);
        }

        return redirect()->route('frontend_views.edit', compact('project'))
                        ->with('success','APP Advertising created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertising  $advertising
     * @return \Illuminate\Http\Response
     */
    public function show(AppAdvertising $appadvertising)
    {
        return view('appadvertisings.show', compact('appadvertising'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advertising  $advertising
     * @return \Illuminate\Http\Response
     */
    public function edit(AppAdvertising $appadvertising)
    {
        $projects = Project::where('status', true)->get();

        return view('appadvertisings.edit', compact('appadvertising'))
               ->with(compact('projects'));
    }

    public function edit2(Project $project)
    {
        $appadvertising = AppAdvertising::where('proj_id', $project->id)
                            ->orderBy('updated_at', 'desc')
                            ->first();

        if ($appadvertising == null) {
            return $this->create2($project);
        }

        return view('appadvertisings.edit2', compact('appadvertising'))
               ->with(compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advertising  $advertising
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppAdvertising $appadvertising)
    {
       $request->validate([
            'proj_id'      => 'required',
            'status'       => 'required',
        ]);

        $appadvertising->proj_id  = $request->proj_id;
        $appadvertising->interval = $request->interval;
        $appadvertising->link_url = $request->link_url;
        $appadvertising->status   = $request->status;

        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $appadvertising->thumbnail = env('APP_URL').$file->file_path;
        }
        $appadvertising->save();

        return redirect()->route('appadvertisings.index')
                        ->with('success','APP Advertising created successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertising  $advertising
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppAdvertising $appadvertising)
    {
        $appadvertising->delete();

        return redirect()->route('appadvertisings.index')
                        ->with('success','APP Advertising deleted successfully');
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

        $appadvertistings = AppAdvertising::select('thumbnail as image', 'link_url')
                                     ->where('proj_id', $proj_id)
                                     ->where('status', true)
                                     ->orderBy('id', 'asc')
                                     ->get();
        $result = $appadvertistings->toArray();
        $response = json_encode($result);

        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'AppAdvertising',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }

}
