<?php

namespace App\Http\Controllers;

use App\Models\QAList;
use App\Models\QACatagory;
use App\Models\User;
use App\Models\Video;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Http\Middleware\MediaUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class QAListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $qalists = QAList::get();

        return view('qalists.index', compact('qalists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $qacatagories = QACatagory::where('status', true)->get();

        return view('qalists.create', compact('qacatagories'));
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
            'catagory_id'  => 'required',
            'type'         => 'required',
            'question'     => 'required',
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;

        if ($request->type == 'i_video') {
            if ($request->file('file')) {
                $file = MediaUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('video', $fileName);
                }
                $data['answer'] = env('VIDEOS_URL').'/'.$file->name;
                $this->saveVideo($file);
            }
        }

        QAList::create($data);

        return redirect()->route('qalists.index')
                        ->with('success','QAList created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QAList  $qAList
     * @return \Illuminate\Http\Response
     */
    public function show(QAList $qalist)
    {
        return view('qalists.show', compact('qalist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QAList  $qAList
     * @return \Illuminate\Http\Response
     */
    public function edit(QAList $qalist)
    {
        $qacatagories = QACatagory::where('status', true)->get();

        return view('qalists.edit', compact('qalist'))
               ->with(compact('qacatagories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QAList  $qAList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QAList $qalist)
    {
        $request->validate([
            'catagory_id'  => 'required',
            'type'         => 'required',
            'question'     => 'required',
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;

        if ($request->type == 'i_video') {
            if ($request->file('file')) {
                $file = MediaUpload::fileUpload($request);
                if ($file == null) {
                    return back()->with('video', $fileName);
                }
                $data['answer'] = env('VIDEOS_URL').'/'.$file->name;
                $this->saveVideo($file);
            }
        }

        $qalist->update($data);

        return redirect()->route('qalists.index')
                        ->with('success','QAList created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QAList  $qAList
     * @return \Illuminate\Http\Response
     */
    public function destroy(QAList $qalist)
    {
        $qalist->delete();

        return redirect()->route('qalists.index')
                        ->with('success','QAList deleted successfully');
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
         $catagory_id = $request->input('catagory_id');
         $qalists = QAList::where('catagory_id', $catagory_id)
                    ->where('status', true)
                    ->get();

         //var_dump($qalists);
         if ($qalists != null)
             return json_encode($qalists);
    }

    public function queryall(Request $request)
    {
        $qacatagories = QACatagory::where('status', true)->get();
        $qalists = QAList::where('status', true)->get();

        $qaarray = array();

        foreach ($qacatagories as $qacatagory) {
                 $items = array();
                 foreach ($qalists as $qalist) {
                    if($qalist->catagory_id == $qacatagory->id) {
                        if ($qalist->type == "e_video" || $qalist->type == "i_video") {
                            $type = "video";
                        } else {
                            $type = $qalist->type;
                        }
                        $item = array(
                              'label'     => $qalist->question,
                              'type'      => $type,
                              'content'   => $qalist->answer,
                        );
                        array_push($items, $item);
                    }
                 }
                 $menu = array(
                    'position' => $qacatagory->position,
                    'title'    => $qacatagory->name,
                    'items'    => $items,
                 );

                 array_push($qaarray, $menu);
        }
        $response = json_encode($qaarray);

        $mac = $request->input("mac");
        if ($mac) {
            $mac = str_replace(':', '', $request->input('mac'));
            $mac = strtoupper($mac);
            $product = Product::where('ether_mac', '=', $mac)
                              ->orWhere('wifi_mac', '=', $mac)
                              ->first();
            if ($product && ProductQuery::enabled()) {
                $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'QAList',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
                );
                ProductQuery::create($record);
            }
        }
        return $response;
    }

}
