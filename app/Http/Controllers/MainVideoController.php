<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\MainVideo;
use App\Models\User;
use Illuminate\Http\Request;

class MainVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $proj_id = $user->proj_id;

        $pname = $request->input("adminlteSearch");
        if ($pname != null) {
            $project = Project::where('name', 'LIKE', $pname)->first();
            if ($project) {
                $proj_id = $project->id;
            }
        }

        if ($proj_id == 0) {
            $mainvideos = MainVideo::get();
        } else {
            $mainvideos = MainVideo::where('main_videos.proj_id', $proj_id)->get();
        }
        return view('mainvideos.index', compact('mainvideos'));
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
            return view('mainvideos.create', compact('projects'));
        } else {
            $projects = Project::where('id', $proj_id)->get();
            return view('mainvideos.create', compact('projects'));
        }
    }

    public function create2(Project $project)
    {
        $mainvideo = new MainVideo;
        $mainvideo->id = 0;

        return view('mainvideos.create2', compact('mainvideo'))
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
            'playlist'     => 'required',
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $str = $request->input('playlist');
        $array = explode("\r\n", $str);
        $json = json_encode($array);
        $data['playlist'] = $json;
        $data['user_id'] = $user->id;

        MainVideo::create($data);

        return redirect()->route('mainvideos.index')
                        ->with('success','Main Video created successfully');
    }

    public function store2(Request $request, Project $project, MainVideo $mainvideo)
    {
        if ($mainvideo > 0) {
            return $this->update($request, $mainvideo);
        } else {
            return $this->store($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainVideo  $mainVideo
     * @return \Illuminate\Http\Response
     */
    public function show(MainVideo $mainvideo)
    {
        $playlist = json_decode($mainvideo->playlist);

        return view('mainvideos.show', compact('mainvideo'))
               ->with(compact('playlist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainVideo  $mainVideo
     * @return \Illuminate\Http\Response
     */
    public function edit(MainVideo $mainvideo)
    {
        $projects = Project::where('status', true)->get();

        $playlist = json_decode($mainvideo->playlist);
        $mainvideo->playlist = implode("\r\n", $playlist);

        return view('mainvideos.edit', compact('mainvideo'))
                 ->with(compact('projects'));
    }

    public function edit2(Project $project)
    {
        $mainvideo = MainVideo::where('proj_id', $project->id)
                                ->orderBy('updated_at', 'desc')
                                ->first();

        if ($mainvideo == null) {
            return $this->create2($project);
        }
        $playlist = json_decode($mainvideo->playlist);
        $mainvideo->playlist = implode("\r\n", $playlist);

        return view('mainvideos.edit2', compact('mainvideo'))
               ->with(compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainVideo  $mainVideo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MainVideo $mainvideo)
    {
        $request->validate([
            'proj_id'      => 'required',
            'playlist'     => 'required',
            'status'       => 'required',
        ]);

        $data = $request->all();
        $str = $request->input('playlist');
        $array = explode("\r\n", $str);
        $json = json_encode($array);
        $data['playlist'] = $json;

        $mainvideo->update($data);

        return redirect()->route('mainvideos.index')
                        ->with('success','Main Video updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainVideo  $mainVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainVideo $mainvideo)
    {
        $mainvideo->delete();

        return redirect()->route('mainvideos.index')
                        ->with('success','Main Video deleted successfully');
    }

    public function query(Request $request)
    {

    }

}
