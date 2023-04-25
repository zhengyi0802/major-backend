<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Advertising;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Middleware\ImageUpload;

class AdvertisingController extends Controller
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
        if ($proj_id == 0 ) {
            $advertisings = Advertising::get();
        } else {
            $advertisings = Advertising::where('proj_id', $proj_id)->get();
        }
        return view('advertisings.index', compact('advertisings'));
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
        if ($proj_id > 0) {
            $projects = Project::where('id', $proj_id)->get();
            return view('advertisings.create', compact('projects'));
        } else {
            $projects = Project::where('status', true)->get();
            return view('advertisings.create', compact('projects'));
        }
    }

    public function create2(Project $project)
    {
        $advertising = new Advertising;
        $advertising->id = 0;

        return view('advertisings.create2', compact('advertising'))
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
            'index'        => 'required',
            'status'       => 'required',
        ]);

        $advertising = new Advertising;
        $user = auth()->user();
        $advertising->proj_id  = $request->proj_id;
        $advertising->index    = $request->index;
        $advertising->link_url = $request->link_url;
        $advertising->status   = $request->status;
        $advertising->user_id  = $user->id;

        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $advertising->thumbnail = env('APP_URL').$file->file_path;
        }
        $advertising->save();

        return redirect()->route('advertisings.index')
                        ->with('success','Advertising created successfully');

    }

    public function store2(Request $request, Project $project, Advertising $advertising)
    {
        $request->validate([
            'index'        => 'required',
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

        if ($advertising->id > 0) {
            $advertising->update($data);
        } else {
            Advertising::create($data);
        }

        return redirect()->route('frontend_views.edit', compact('project'))
                        ->with('success','Advertising created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertising  $advertising
     * @return \Illuminate\Http\Response
     */
    public function show(Advertising $advertising)
    {
        return view('advertisings.show', compact('advertising'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advertising  $advertising
     * @return \Illuminate\Http\Response
     */
    public function edit(Advertising $advertising)
    {
        $user = auth()->user();
        $proj_id = $user->proj_id;

        if ($proj_id == 0 || $proj_id == $advertising->proj_id) {
            $projects = Project::where('status', true)->get();

            return view('advertisings.edit', compact('advertising'))
                   ->with(compact('projects'));
        } else {
            return redirect()->route('advertisings.index')
                        ->with('failure','Advertising can not be edited by invalid user!');
        }
    }

    public function edit2(Project $project)
    {
        $advertising = Advertising::where('proj_id', $project->id)
                            ->orderBy('updated_at', 'desc')
                            ->first();

        if ($advertising == null) {
            return $this->create2($project);
        }

        return view('advertisings.edit2', compact('advertising'))
               ->with(compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advertising  $advertising
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertising $advertising)
    {
       $request->validate([
            'proj_id'      => 'required',
            'status'       => 'required',
        ]);

        $user = auth()->user();
        $advertising->proj_id  = $request->proj_id;
        $advertising->index    = $request->index;
        $advertising->link_url = $request->link_url;
        $advertising->status   = $request->status;
        $advertising->user_id  = $user->id;

        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $advertising->thumbnail = env('APP_URL').$file->file_path;
        }
        $advertising->save();

        return redirect()->route('advertisings.index')
                        ->with('success','Advertising created successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertising  $advertising
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertising $advertising)
    {
        $user = auth()->user();
        $proj_id = $user->proj_id;

        if ($proj_id == 0 || $advertising->proj_id == $proj_id) {
            $advertising->delete();

            return redirect()->route('advertisings.index')
                        ->with('success','Advertising deleted successfully');
        } else {
            return redirect()->route('advertisings.index')
                        ->with('failure','Advertising can not ne deleted by invalid user!');
        }
    }

    public function query(Request $request)
    {


    }

}
