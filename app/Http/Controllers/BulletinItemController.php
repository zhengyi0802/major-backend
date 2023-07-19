<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\BulletinItem;
use App\Models\Video;
use App\Models\User;
use App\Http\Middleware\MediaUpload;
use Illuminate\Http\Request;

class BulletinItemController extends Controller
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
           $bulletinitems = BulletinItem::get();
       } else {
           $bulletins = Bulletin::select('id')->where('proj_id', $proj_id)->get();
           $bulletin_ids = $bulletins->pluck('id');
           $bulletinitems = BulletinItem::whereIn('bulletin_id', $bulletin_ids)->get();
       }
       return view('bulletinitems.index', compact('bulletinitems'));
    }

    public function index2(Bulletin $bulletin)
    {
       $bulletinitems = $bulletin->items;
       return view('bulletinitems.index2', compact('bulletinitems'))
              ->with(compact('bulletin'));
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
            $bulletins = Bulletin::where('status', true)->get();
        else
            $bulletins = Bulletin::where('status', true)->where('proj_id', $proj_id)->get();

        return view('bulletinitems.create', compact('bulletins'));
    }

    public function create2(Bulletin $bulletin)
    {
        return view('bulletinitems.create2', compact('bulletin'));
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
             'bulletin_id' => 'required',
             'mime_type'   => 'required',
             'status'      => 'required',
        ]);

        $bulletinitem = new BulletinItem;
        $user = auth()->user();
        $bulletinitem->bulletin_id = $request->bulletin_id;
        $bulletinitem->mime_type   = $request->mime_type;
        $bulletinitem->url         = $request->url;
        $bulletinitem->status      = $request->status;
        $bulletinitem->user_id     = $user->id;

        if ($request->file()) {
            $file = MediaUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            if ($request->mime_type == 'i_video') {
                $this->saveVideo($file);
                $bulletinitem->url_http = env('VIDEOS_URL').'/'.$file->name;
            }
            $bulletinitem->url = env('APP_URL').$file->file_path;
        }

        $bulletinitem->save();

        return redirect()->route('bulletinitems.index')
               ->with('success','Bulletin Item created successfully');
    }

    public function store2(Bulletin $bulletin, Request $request)
    {
        $request->validate([
             'bulletin_id' => 'required',
             'mime_type'   => 'required',
             'status'      => 'required',
        ]);

        $bulletinitem = new BulletinItem;
        $user = auth()->user();
        $bulletinitem->bulletin_id = $request->bulletin_id;
        $bulletinitem->mime_type   = $request->mime_type;
        $bulletinitem->url         = $request->url;
        $bulletinitem->status      = $request->status;
        $bulletinitem->user_id     = $user->id;

        if ($request->file()) {
            $file = MediaUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            if ($request->mime_type == 'i_video') {
                $this->saveVideo($file);
                $bulletinitem->url_http = env('VIDEOS_URL').'/'.$file->name;
            }
            $bulletinitem->url = env('APP_URL').$file->file_path;
        }

        $bulletinitem->save();

        return redirect()->route('bulletinitems.index2')
               ->with(compact('bulletin'))
               ->with('success','Bulletin Item created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BulletinItem  $bulletinItem
     * @return \Illuminate\Http\Response
     */
    public function show(BulletinItem $bulletinitem)
    {
        return view('bulletinitems.show', compact('bulletinitem'));
    }

    public function show2(Bulletin $bulletin, BulletinItem $bulletinitem)
    {
        return view('bulletinitems.show2', compact('bulletinitem'))
               ->with(compact('bulletin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BulletinItem  $bulletinItem
     * @return \Illuminate\Http\Response
     */
    public function edit(BulletinItem $bulletinitem)
    {
        $bulletins = Bulletin::where('status', true)->get();

        return view('bulletinitems.edit', compact('bulletinitem'))
               ->with(compact('bulletins'));
    }

    public function edit2(Bulletin $bulletin, BulletinItem $bulletinitem)
    {
        return view('bulletinitems.edit2', compact('bulletinitem'))
               ->with(compact('bulletin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BulletinItem  $bulletinItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BulletinItem $bulletinitem)
    {
        $request->validate([
             'bulletin_id' => 'required',
             'mime_type'   => 'required',
             'status'      => 'required',
        ]);

        $user = auth()->user();

        $bulletinitem->bulletin_id = $request->bulletin_id;
        $bulletinitem->mime_type   = $request->mime_type;
        $bulletinitem->url         = $request->url;
        $bulletinitem->status      = $request->status;
        $bulletinitem->user_id     = $user->id;

        if ($request->file()) {
            $file = MediaUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            if ($request->mime_type == 'i_video') {
                $this->saveVideo($file);
                $bulletinitem->url_http = env('VIDEOS_URL').'/'.$file->name;
            }
            $bulletinitem->url = env('APP_URL').$file->file_path;
        }

        $bulletinitem->save();

        return redirect()->route('bulletinitems.index')
               ->with('success','Bulletin Item updated successfully');
    }

    public function update2(Request $request, Bulletin $bulletin, BulletinItem $bulletinitem)
    {
        $request->validate([
             'bulletin_id' => 'required',
             'mime_type'   => 'required',
             'status'      => 'required',
        ]);
        $user = auth()->user();
        $bulletinitem->bulletin_id = $request->bulletin_id;
        $bulletinitem->mime_type   = $request->mime_type;
        $bulletinitem->url         = $request->url;
        $bulletinitem->status      = $request->status;
        $bulletinitem->user_id     = $user->id;

        if ($request->file()) {
            $file = MediaUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            if ($request->mime_type == 'i_video') {
                $this->saveVideo($file);
                $bulletinitem->url_http = env('VIDEOS_URL').'/'.$file->name;
            }
            $bulletinitem->url = env('APP_URL').$file->file_path;
        }

        $bulletinitem->save();

        return redirect()->route('bulletinitems.index')
               ->with(compact('bulletin'))
               ->with('success','Bulletin Item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BulletinItem  $bulletinItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(BulletinItem $bulletinitem)
    {
        $bulletinitem->delete();

        return redirect()->route('bulletinitems.index')
               ->with('success','Bulletin Item deleted successfully');
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

    }

}
