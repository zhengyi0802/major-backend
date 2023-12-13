<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ImageUpload;
use App\Models\ELearningCatagory;
use App\Models\ELearning;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\MediaFile;
use App\Models\User;
use Illuminate\Http\Request;

class ELearningCatagoryController extends Controller
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
            $elearningcatagories = ELearningCatagory::where('proj_id', $proj_id)->get();
        } else {
            $elearningcatagories = ELearningCatagory::get();
        }

        return view('elearningcatagories.index',compact('elearningcatagories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $oldproj_id = $request->input('proj_id');
        $user = auth()->user();
        $proj_id = $user->proj_id;

        if ($proj_id == 0) {
            $projects = Project::where('status', true)->get();
            if ($oldproj_id != null) {
                $proj_id = $oldproj_id;
            }
            $elearningcatagories = ELearningCatagory::where('proj_id', $proj_id)
                                                    ->where('status', true)
                                                    ->where('type', 'catalog')
                                                    ->get();
            return view('elearningcatagories.create', compact('projects'))
                   ->with(compact('elearningcatagories'))
                   ->with('proj_id', $proj_id);
        } else {
            $projects = Project::where('id', $proj_id)->get();
            //var_dump($projects);
            $elearningcatagories = ELearningCatagory::where('proj_id', $proj_id)
                                                    ->where('status', true)
                                                    ->where('type', 'catalog')
                                                    ->get();

            return view('elearningcatagories.create', compact('projects'))
                   ->with(compact('elearningcatagories'))
                   ->with('proj_id', $proj_id);

        }
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

        $elearningcatagory = new ELearningCatagory;
        $user = auth()->user();

        $elearningcatagory->proj_id     = $request->proj_id;
        $elearningcatagory->parent_id   = $request->parent_id;
        $elearningcatagory->type        = $request->type;
        $elearningcatagory->name        = $request->name;
        $elearningcatagory->password    = $request->password;
        $elearningcatagory->description = $request->description;
        $elearningcatagory->status      = $request->status;
        $elearningcatagory->user_id     = $user->id;

        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $elearningcatagory->thumbnail = env('APP_URL').$file->file_path;
        }

        $elearningcatagory->save();

        return redirect()->route('elearningcatagories.index')
                        ->with('success','ELearningCatagory created successfully');
    }

    public function copy(Request $request)
    {
        $id = $request->input('id');
        $projects = $request->input('proj_id');
        $elearningcatagory = ElearningCatagory::find($id);
        $data = $elearningcatagory->toArray();

        foreach($projects as $proj_id) {
             $data['proj_id'] = $proj_id;
             ELearningCatagory::create($data);
        }

        return redirect()->route('elearningcatagories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ELearningCatagory  $eLearningCatagory
     * @return \Illuminate\Http\Response
     */
    public function show(ELearningCatagory $elearningcatagory)
    {
        $parent = "root";
        $projects = Project::where('status', true)->get();
        return view('elearningcatagories.show', compact('elearningcatagory'))
               ->with(compact('projects'))
               ->with(compact('parent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ELearningCatagory  $eLearningCatagory
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ELearningCatagory $elearningcatagory)
    {
        $user = auth()->user();
        $proj_id = $request->input('proj_id');
        if ($proj_id == null) {
            $proj_id = $elearningcatagory->proj_id;
        }
        if ($user->role != 'operator') {
            $projects = Project::where('status', true)->get();
        } else {
            $projects = Project::where('id', $proj_id)->where('status', true)->get();
        }

        $elearningcatagories = ELearningCatagory::where('proj_id', $proj_id)
                                                ->where('status', true)
                                                ->where('type', 'catalog')
                                                ->get();

        return view('elearningcatagories.edit', compact('elearningcatagory'))
               ->with(compact('projects'))
               ->with(compact('elearningcatagories'))
               ->with('proj_id', $proj_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ELearningCatagory  $eLearningCatagory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ELearningCatagory $elearningcatagory)
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

        $elearningcatagory->update($data);

        return redirect()->route('elearningcatagories.index')
                        ->with('success','ELearningCatagory created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ELearningCatagory  $eLearningCatagory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ELearningCatagory $elearningcatagory)
    {
        $elearningcatagory->delete();

        return redirect()->route('elearningcatagories.index')
                        ->with('success', 'ELearningCatagory deleted successfully');
    }

    public function query(Request $request)
    {
        $product = null;
        $proj_id = $this->checkProject($request);
        $elearnings = ELearning::where('status', true)->orderBy('id', 'desc')->get();
        $contents = array();
        foreach ($elearnings as $elearning) {
                $item = array(
                        'catagory_id' => $elearning->catagory_id,
                        'name'        => $elearning->name,
                        'password'    => is_null($elearning->password) ? null : md5($elearning->password),
                        'description' => $elearning->description,
                        'type'        => $elearning->mime_type,
                        'url'         => $elearning->url,
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
                      'keywords'    => 'ElearningCatagory',
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
