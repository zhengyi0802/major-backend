<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ImageUpload;
use App\Models\MediaCatagory;
use App\Models\MediaContent;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\MediaFile;
use App\Models\User;
use Illuminate\Http\Request;

class MediaCatagoryController extends Controller
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
            $mediacatagories = MediaCatagory::where('proj_id', $proj_id)->get();
        } else {
            $mediacatagories = MediaCatagory::get();
        }

        return view('mediacatagories.index',compact('mediacatagories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $uproj_id = $user->proj_id;
        $proj_id = $request->input('proj_id');
        if ($proj_id == null) {
            $proj_id = $uproj_id;
        }
        if ($user->role != 'operator') {
            $projects = Project::where('status', true)->get();
        } else {
            $projects = Project::where('id', $proj_id)->get();
        }
        $mediacatagories = MediaCatagory::where('proj_id', $proj_id)
                                        ->where('type', 'catalog')
                                        ->where('status', true)
                                        ->get();

        return view('mediacatagories.create', compact('projects'))
               ->with(compact('mediacatagories'))
               ->with('proj_id', $proj_id);
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
            'proj_id'  => 'required',
            'name'     => 'required',
            'status'   => 'required',
        ]);

        $mediacatagory = new MediaCatagory;
        $user = auth()->user();

        $mediacatagory->proj_id     = $request->proj_id;
        $mediacatagory->parent_id   = $request->parent_id;
        $mediacatagory->type        = $request->type;
        $mediacatagory->name        = $request->name;
        $mediacatagory->password    = $request->password;
        $mediacatagory->keywords    = $request->keywords;
        $mediacatagory->description = $request->description;
        $mediacatagory->status      = $request->status;
        $mediacatagory->user_id     = $user->id;

        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $mediacatagory->thumbnail = env('APP_URL').$file->file_path;
        }

        $mediacatagory->save();

        return redirect()->route('mediacatagories.index')
                        ->with('success','MediaCatagory created successfully');
    }

    public function copy(Request $request)
    {
        $id = $request->input('id');
        $projects = $request->input('proj_id');
        $mediacatagory = MediaCatagory::find($id);
        $data = $mediacatagory->toArray();

        foreach($projects as $proj_id) {
             $data['proj_id'] = $proj_id;
             MediaCatagory::create($data);
        }

        return redirect()->route('mediacatagories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaCatagory  $mediaCatagory
     * @return \Illuminate\Http\Response
     */
    public function show(MediaCatagory $mediacatagory)
    {
        $parent = "root";
        $projects = Project::where('status', true)->get();
        return view('mediacatagories.show', compact('mediacatagory'))
               ->with(compact('projects'))
               ->with(compact('parent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MediaCatagory  $mediaCatagory
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, MediaCatagory $mediacatagory)
    {
        $user = auth()->user();
        $proj_id = $request->input('proj_id');

        if ($proj_id == null) {
            $proj_id = $mediacatagory->proj_id;
        }
        if ($user->role != 'operator') {
            $projects = Project::where('status', true)->get();
        } else {
            $projects = Project::where('id', $proj_id)->where('status', true)->get();
        }

        $mediacatagories = MediaCatagory::where('proj_id', $proj_id)
                                        ->where('status', true)
                                        ->where('type', 'catalog')
                                        ->get();

        return view('mediacatagories.edit', compact('mediacatagory'))
               ->with(compact('projects'))
               ->with(compact('mediacatagories'))
               ->with('proj_id', $proj_id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaCatagory  $mediaCatagory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MediaCatagory $mediacatagory)
    {
        $request->validate([
            'proj_id'  => 'required',
            'name'     => 'required',
            'status'   => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;
        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $data['thumbnail'] = env('APP_URL').$file->file_path;
        }

        $mediacatagory->update($data);

        return redirect()->route('mediacatagories.index')
                        ->with('success','MediaCatagory created successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaCatagory  $mediaCatagory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MediaCatagory $mediacatagory)
    {
        $mediacatagory->delete();

        return redirect()->route('mediacatagories.index')
                        ->with('success', 'MediaCatagory deleted successfully');
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
            } else {
                return json_encode(null);
            }
        } else if ($request->input('id')) {
            $proj_id = $request->input('id');
        }

        $mediacontents = MediaContent::where('status', true)->orderBy('id', 'desc')->get();
        $contents = array();
        foreach ($mediacontents as $mediacontent) {
                $item = array(
                        'catagory_id' => $mediacontent->catagory_id,
                        'name'        => $mediacontent->name,
                        'password'    => is_null($mediacontent->password) ? null : md5($mediacontent->password),
                        'description' => $mediacontent->description,
                        'type'        => $mediacontent->mime_type,
                        'url'         => $mediacontent->url,
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
        $response = json_encode($data[0]['list']);
        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'MediaCatagory',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }

}
