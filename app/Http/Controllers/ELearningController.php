<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ImageUpload;
use App\Http\Middleware\MediaUpload;
use App\Models\Video;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\ELearningCatagory;
use App\Models\ELearning;
use App\Models\User;
use Illuminate\Http\Request;

class ELearningController extends Controller
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

        $elearnings = ELearning::get();

        return view('elearnings.index',compact('elearnings'));
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
            $elearningcatagories = ELearningCatagory::where('status', true)
                                                ->where('type', 'contents')
                                                ->get();
        } else {
            $elearningcatagories = ELearningCatagory::where('status', true)
                                                ->where('proj_id', $proj_id)
                                                ->where('type', 'contents')
                                                ->get();
        }

        return view('elearnings.create', compact('elearningcatagories'));
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

        $elearning = new ELearning;
        $user = auth()->user();

        $elearning->catagory_id = $request->catagory_id;
        $elearning->name        = $request->name;
        $elearning->password    = $request->password;
        $elearning->description = $request->description;
        $elearning->mime_type   = $request->mime_type;
        $elearning->url         = $request->url;
        $elearning->status      = $request->status;
        $elearning->user_id     = $user->id;

        if ($request->mime_type == 'i_video' || $request->mime_type == 'ppt' || $request->mime_type == 'pdf') {
            if ($request->file('file')) {
                $file = MediaUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('video', $fileName);
                }
                $elearning->url = env('APP_URL').$file->file_path;
                $elearning->url_http = env('VIDEOS_URL').'/'.$file->name;
                if ($request->mime_type == 'i_video') {
                    $this->saveVideo($file);
                }
            }
        }

        if ($request->file('image')) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $elearning->preview = $file->file_path;
        }

        $elearning->save();

        return redirect()->route('elearnings.index')
                        ->with('success', 'ELearning deleted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ELearning  $eLearning
     * @return \Illuminate\Http\Response
     */
    public function show(ELearning $elearning)
    {
        return view('elearnings.show', compact('elearning'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ELearning  $eLearning
     * @return \Illuminate\Http\Response
     */
    public function edit(ELearning $elearning)
    {
        $elearningcatagories = ELearningCatagory::where('status', true)
                                                ->where('type', 'contents')
                                                ->get();

        return view('elearnings.edit', compact('elearning'))
               ->with(compact('elearningcatagories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ELearning  $eLearning
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ELearning $elearning)
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

        if ($request->mime_type == 'i_video' || $request->mime_type= 'ppt' || $request->mime_type == 'pdf') {
            if ($request->file('file')) {
                $file = MediaUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('video', $fileName);
                }
                $data['url'] = env('APP_URL').$file->file_path;
                $data['url_http'] = env('VIDEOS_URL').'/'.$file->name;
                if ($request->mime_type == 'i_video') {
                    $this->saveVideo($file);
                }
            }
        }

        if ($request->file('image')) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $data['preview'] = $file->file_path;
        }

        $elearning->update($data);

        return redirect()->route('elearnings.index')
                        ->with('success', 'ELearning deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ELearning  $eLearning
     * @return \Illuminate\Http\Response
     */
    public function destroy(ELearning $elearning)
    {
        $elearning->delete();

        return redirect()->route('elearnings.index')
                        ->with('success', 'ELearning deleted successfully');
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
         $elearnings = ELearning::where('catagory_id', $catagory_id)
                       ->where('status', true)
                       ->get();

         if ($elearnings != null)
            return json_encode($elearnings);

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

        $elearnings = ELearning::where('status', true)->orderBy('id','desc')->get();
        $contents = array();
        foreach ($elearnings as $elearning) {
                $item = array(
                        'catagory_id' => $elearning->catagory_id,
                        'name'        => $elearning->name,
                        'password'    => is_null($elearning->password) ? null : md5($elearning->password),
                        'description' => $elearning->description,
			'thumbnail'   => $elearning->preview,
                        'type'        => $elearning->mime_type,
                        'content'     => $elearning->url,
                        'content2'    => $elearning->url_http,
                );
                array_push($contents, $item);
        }

        $elearningcatagories = ELearningCatagory::where('status', true)
                                  ->where('proj_id', $proj_id)
                                  ->orderBy('id', 'desc')
                                  ->get();

        $data = array();
        $data[0] = array(
                   'name'      => 'root',
                   'list'      => array(),
        );

        foreach ($elearningcatagories as $elearningcatagory) {
            $data[$elearningcatagory->id] = array(
                    //'id'          => $elearningcatagory->id,
                    'parent_id'   => $elearningcatagory->parent_id,
                    'name'        => $elearningcatagory->name,
                    'password'    => is_null($elearningcatagory->password) ? null : md5($elearningcatagory->password),
                    'type'        => $elearningcatagory->type,
                    'description' => $elearningcatagory->description,
                    'thumbnail'   => $elearningcatagory->thumbnail,
                    'list'        => array(),
            );
            if ($elearningcatagory->type == 'contents') {
                foreach ($contents as $content) {
                   if ($content['catagory_id'] == $elearningcatagory->id) {
                       array_push($data[$elearningcatagory->id]['list'], $content);
                   }
                }
            }
            //echo "Elearning Catagory ID : ".$elearningcatagory->id."<br>";
            //echo "JSON String : ".json_encode($data[$elearningcatagory->id])."<br>--------------------<br>";
        }

        $parents = ELearningCatagory::distinct()->select('parent_id')
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
                      'query'       => $request->fullUrl(),
                      'keywords'    => 'elearnings',
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }


}
