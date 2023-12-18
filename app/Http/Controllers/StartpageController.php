<?php

namespace App\Http\Controllers;

use App\Http\Middleware\MediaUpload;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\MediaFile;
use App\Models\Startpage;
use App\Models\Video;
use App\Models\User;
use Illuminate\Http\Request;
//use Request;

class StartpageController extends Controller
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

        if ($proj_id > 0 ) {
            $startpages = Startpage::where('proj_id', $proj_id)->get();
        } else {
            $startpages = Startpage::get();
        }

        return view('startpages.index',compact('startpages'));
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

        if ($proj_id == 0)
            $projects = Project::where('status', true)->get();
        else
            $projects = Project::where('id', $proj_id)->get();

        return view('startpages.create', compact('projects'));
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
            'name' => 'required',
            'proj_id' => 'required',
            'mime_type' => 'required',
            'status' => 'required',
        ]);

        $startpage = new Startpage;
        $user = auth()->user();

        $startpage->name = $request->name;
        $startpage->proj_id = $request->proj_id;
        $startpage->mime_type = $request->mime_type;
        $startpage->url = $request->url;
        $startpage->status = $request->status;
        $startpage->user_id = $user->id;

        if ($request->file()) {
            $file = MediaUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            if ($request->mime_type == 'i_video') {
                $this->saveVideo($file);
                $startpage->url_http = env('VIDEOS_URL').'/'.$file->name;
            }
            $startpage->url = env('APP_URL').$file->file_path;
        }

        $startpage->save();

        return redirect()->route('startpages.index')
                        ->with('success','Startpage created successfully.');

    }

    public function newstore(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);

        $insertflag = false;
        $startpage = Startpage::where('proj_id', '=', $id)->first();
        if ($startpage == null) {
            $startpage = new Startpage;
            $insertflag = true;
        }
        $user = auth()->user();

        $startpage->proj_id = $id;
        $startpage->name = $request->name;
        $startpage->mime_type = $request->mime_type;
        if ($request->url != null) $startpage->url = $request->url;
        $startpage->detail = $request->detail;
        $startpage->status = $request->status;
        $startpage->start_datetime= $request->start_datetime;
        $startpage->stop_datetime = $request->stop_datetime;
        $startpage->user_id = $user->id;

        if ($insertflag || $request->file()) {
            $file = MediaUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            if ($request->mime_type == 'i_video') {
                $this->saveVideo($file);
                $startpage->url_http = env('VIDEOS_URL').'/'.$file->name;
            }
            $startpage->url = env('APP_URL').$file->file_path;
        }

        $startpage->save();

        if ( $id == null ) {
            return redirect()->route('projects.index')
                        ->with('success','Startpage created successfully.');
        } else {
            $project = Project::find($id);

            return redirect()->route('projects.index', $id)
                        ->with('success', 'Startpage created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Startpage  $startpage
     * @return \Illuminate\Http\Response
     */
    public function show(Startpage $startpage)
    {
        return view('startpages.show', compact('startpage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Startpage  $startpage
     * @return \Illuminate\Http\Response
     */
    public function edit(Startpage $startpage)
    {
        $user = auth()->user();
        $proj_id = $user->proj_id;
        if ($proj_id  == 0 || $proj_id == $startpage->proj_id) {
            $project = Project::where('id', '=', $startpage->proj_id)->first();
            return view('startpages.edit', compact('startpage'))
                   ->with(compact('project'));
        } else {
           return redirect()->route('startpages.index')
                            ->with('success', 'Startpage can not be edited by invalid user.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Startpage  $startpage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Startpage $startpage)
    {
        $request->validate([
            'name' => 'required',
            'mime_type' => 'required',
            'status' => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;

        if($request->file()) {
            $file = MediaUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            if ($request->mime_type == 'i_video') {
                $this->saveVideo($file);
                $data['url_http'] = env('VIDEOS_URL').'/'.$file->name;
            }
            $data['url'] = env('APP_URL').$file->file_path;
        }

        $startpage->update($data);

        return redirect()->route('startpages.index')
                         ->with('success', 'Startpage updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Startpage  $startpage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Startpage $startpage)
    {
        $startpage->delete();

        return redirect()->route('startpages.index')
                         ->with('success','Start Page deleted successfully');
    }

    public function saveVideo($file) {
            $video = array(
                   "user_id"     => auth()->user()->id,
                   "catagory_id" => 0,
                   "title"       => $file->name,
                   "video_url"   => env('APP_URL').$file->file_path,
                   'url_http'    => env('VIDEOS_URL').'/'.$file->name,
                   "status"      => true,
            );

            Video::create($video);

            return;
    }

    public function query(Request $request)
    {
        $product = null;
        $proj_id = $this->checkProject($request);
/*
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
*/
        $datetime = date('y-m-d h:i:s');
        $startpage = Startpage::where('proj_id', $proj_id)
                               ->where('status', true)
                               ->orderBy('id', 'desc')
                               ->first();
        if ($startpage) {
            $result = array(
                    'name'         => $startpage->name,
                    'mime_type'    => $startpage->mime_type,
                6bccbe58d7b4329b    'url'          => $startpage->url,
                    'url2'         => $startpage->url_http,
                    'intervals'    => $startpage->intervals,
                    'start_time'   => $startpage->start_time,
                    'stop_time'    => $startpage->stop_time,
            );

            $response = json_encode($result);
/*
            if ($product && ProductQuery::enabled()) {
                $record = array(
                          'product_id'  => $product->id,
                          'keywords'    => 'Startpage',
                          'query'       => $request->fullUrl(),
                          'response'    => $response,
6bccbe58d7b4329b                );
                ProductQuery::create($record);
            }
*/
            return $response;
        } else {
            return json_encode(null);
        }
    }

    function checkProject(Request $request)
    {
        $data = $request->all();
        if (isset($data['mac'])) {
            $mac = str_replace(':', '', $data['mac']);
            $mac = strtoupper($mac);
            $mproduct = Product::where('ether_mac', '=', $mac)
                               ->orWhere('wifi_mac', '=', $mac)->first();
        } else {
            $mproduct = null;
        }
        if (isset($data['aid'])) {
            $aid = $data['aid'];
            $aproduct = Product::where('android_id', $data['aid'])->first();
        } else {
            $aproduct = null;
            $aid = null;
        }
        if (isset($data['id'])) {
            $proJ_id = $data['id'];
        }
        if ($aproduct != null) {
            $proj_id = $aproduct->proj_id;
        } else if ($mproduct != null) {
            $proj_id = $mproduct->proj_id;
            $mproduct->android_id = $aid;
            $mproduct->save();
        } else {
            $project = Project::where('is_default', true)->first();
            $proj_id = $project->id;
            if ($aid != null) {
                $arr = [
                     'android_id'   => $aid,
                     'serialno'     => 'startpages',
                     'wifi_mac'     => $mac,
                     'type_id'      => 14,
                     'status_id'    => 1,
                     'proj_id'      => $proj_id,
                     'user_id'      => 2,
                     'expire_date'  => '2075-12-31 00:00:00',
                     'query_string' => json_encode($data),
                   ];
                $product = Product::create($arr);
            }
        }

        return $proj_id;
    }

}
