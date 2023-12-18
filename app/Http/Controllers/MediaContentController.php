<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ImageUpload;
use App\Http\Middleware\MediaUpload;
use App\Models\Video;
use App\Models\Product;
use App\Models\Project;
use App\Models\MediaCatagory;
use App\Models\MediaContent;
use App\Models\User;
use Illuminate\Http\Request;

class MediaContentController extends Controller
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
            $mediacontents = MediaContent::get();
        } else {
            $mediacatagories = MediaCatagory::select('id')->where('proj_id', $proj_id)->get();
            $catagories = $mediacatagories->pluck('id');
            $mediacontents = MediaContent::whereIn('catagory_id', $catagories)->get();
        }
        return view('mediacontents.index',compact('mediacontents'));
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
            $mediacatagories = MediaCatagory::where('status', true)
                                         ->where('type', 'contents')
                                         ->get();
        } else {
            $mediacatagories = MediaCatagory::where('status', true)
                                         ->where('proj_id', $proj_id)
                                         ->where('type', 'contents')
                                         ->get();
         }
        return view('mediacontents.create', compact('mediacatagories'));
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
            'catagory_id'   => 'required',
            'name'          => 'required',
            'mime_type'     => 'required',
            'status'        => 'required',
        ]);

        $mediacontent = new MediaContent;
        $user = auth()->user();

        $mediacontent->catagory_id = $request->catagory_id;
        $mediacontent->name        = $request->name;
        $mediacontent->password    = $request->password;
        $mediacontent->description = $request->description;
        $mediacontent->mime_type   = $request->mime_type;
        $mediacontent->url         = $request->url;
        $mediacontent->status      = $request->status;
        $mediacontent->user_id     = $user->id;

        if ($request->mime_type == 'i_video') {
            if ($request->file('file')) {
                $file = MediaUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('video', $fileName);
                }
                $mediacontent->url = env('APP_URL').$file->file_path;
                $mediacontent->url_http = env('VIDEOS_URL').'/'.$file->name;
                $this->saveVideo($file);
            }
        }

        if ($request->file('image')) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $mediacontent->preview = $file->file_path;
        }

        $mediacontent->save();

        return redirect()->route('mediacontents.index')
                        ->with('success', 'Media Content added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaContent  $mediaContent
     * @return \Illuminate\Http\Response
     */
    public function show(MediaContent $mediacontent)
    {
        return view('mediacontents.show', compact('mediacontent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MediaContent  $mediaContent
     * @return \Illuminate\Http\Response
     */
    public function edit(MediaContent $mediacontent)
    {
/*
        $mediacatagories = MediaCatagory::where('status', true)
                                        ->where('type', 'contents')
                                        ->get();
*/
        $user = auth()->user();
        $proj_id = $user->proj_id;
        if ($proj_id == 0) {
            $mediacatagories = MediaCatagory::where('status', true)
                                         ->where('type', 'contents')
                                         ->get();
        } else {
            $mediacatagories = MediaCatagory::where('status', true)
                                         ->where('proj_id', $proj_id)
                                         ->where('type', 'contents')
                                         ->get();
         }

        return view('mediacontents.edit', compact('mediacontent'))
               ->with(compact('mediacatagories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaContent  $mediaContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MediaContent $mediacontent)
    {
        $request->validate([
            'catagory_id'   => 'required',
            'name'          => 'required',
            'mime_type'     => 'required',
            'status'        => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;

        if ($request->mime_type == 'i_video') {
            if ($request->file('file')) {
                $file = MediaUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('video', $fileName);
                }
                $data['url'] = env('APP_URL').$file->file_path;
                $data['url_http'] = env('VIDEOS_URL').'/'.$file->name;
                $this->saveVideo($file);
            }
        }

        if ($request->file('image')) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $data['preview'] = $file->file_path;
        }

        $mediacontent->update($data);

        return redirect()->route('mediacontents.index')
                        ->with('success', 'Media Content updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaContent  $mediaContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(MediaContent $mediacontent)
    {
        $mediacontent->delete();

        return redirect()->route('mediacontents.index')
                        ->with('success', 'MediaContent deleted successfully');

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

    public function query2(Request $request)
    {
         $catagory_id = $request->input('catagory_id');
         $mediacontents = MediaContent::where('catagory_id', $catagory_id)
                        ->where('status', true)
                        ->get();

         if ($mediacontents != null)
            return json_encode($mediacontents);

    }

    public function query(Request $request)
    {
        $product = null;
        $proj_id = $this->checkProject($request);
        $mediacontents = MediaContent::where('status', true)->orderBy('id', 'desc')->get();
        $contents = array();
        foreach ($mediacontents as $mediacontent) {
                $item = array(
                        'catagory_id' => $mediacontent->catagory_id,
                        'name'        => $mediacontent->name,
                        'password'    => is_null($mediacontent->password) ? null : md5($mediacontent->password),
                        'description' => $mediacontent->description,
			'thumbnail'   => $mediacontent->preview,
                        'type'        => $mediacontent->mime_type,
                        'content'     => $mediacontent->url,
                        'content2'    => $mediacontent->url_http,
                );
                array_push($contents, $item);
        }

        $mediacatagories = MediaCatagory::where('status', true)
                                  ->where('proj_id', $proj_id)
                                  ->orderBy('id', 'desc')
                                  ->get();

        $data = array();
        $data[0] = array(
                   'name'      => 'root',
                   'list'      => array(),
        );

        foreach ($mediacatagories as $mediacatagory) {
            $data[$mediacatagory->id] = array(
                    //'id'          => $mediacatagory->id,
                    'parent_id'   => $mediacatagory->parent_id,
                    'name'        => $mediacatagory->name,
                    'password'    => is_null($mediacatagory->password) ? null : md5($mediacatagory->password),
                    'type'        => $mediacatagory->type,
                    'keywords'    => $mediacatagory->keywords,
                    'description' => $mediacatagory->description,
                    'thumbnail'   => $mediacatagory->thumbnail,
                    'list'        => array(),
            );
            if ($mediacatagory->type == 'contents') {
                foreach ($contents as $content) {
                   if ($content['catagory_id'] == $mediacatagory->id) {
                       array_push($data[$mediacatagory->id]['list'], $content);
                   }
                }
            }
            //echo "Media Catagory ID : ".$mediacatagory->id."<br>";
            //echo "JSON String : ".json_encode($data[$mediacatagory->id])."<br>--------------------<br>";
        }

        $parents = MediaCatagory::distinct()->select('parent_id')
                                  ->where('status', true)
                                  ->where('proj_id', $proj_id)
                                  ->orderBy('parent_id', 'desc')
                                  ->get();

        foreach ($parents as $parent) {
            //echo "parent : ". $parent->parent_id."<br>";
            $p1 = $data[$parent->parent_id];
            foreach ($data as $list) {
                if ($list['name'] != 'root') {
                    if ($parent->parent_id == $list['parent_id']) {
                        array_push($data[$parent->parent_id]['list'], $list);
                        //echo "data : ". json_encode($p1)."<br>";
                    }
                }
            }
            //echo "string = ".json_encode($p1)."<br><br>";
        }

        return json_encode($data[0]['list']);
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
        } else if ($mproduct != null) {
            $proj_id = $mproduct->proj_id;
            $mproduct->android_id = $aid;
            $mproduct->save();
        } else {
            $project = Project::where('is_default', true)->first();
            $proj_id = $project->id;
            if ($aid == null) {
                $arr = [
                     'android_id'   => $aid,
                     'serialno'     => 'mediacontents',
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
