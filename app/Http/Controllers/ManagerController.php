<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\Project;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $managers = Manager::get();

       return view('managers.index',compact('managers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('status', true)->get();
        return view('managers.create', compact('projects'));
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
            'name'      => 'required',
            'account'   => 'required',
            'password'  => 'required',
            'job_title' => 'required',
            'proj_id'   => 'required',
            'status'    => 'required',
        ]);

        $user           = new User;
        $user->name     = $request->name;
        $user->email    = $request->account;
        $user->proj_id  = $request->proj_id;
        $user->password = Hash::make($request->password);
        if ($request->proj_id == 0) {
            $user->role     = 'manager';
        } else {
            $user->role     = 'operator';
        }
        $user->save();

        $manager          = new Manager;
        $manager->name    = $request->name;
        $manager->user_id = $user->id;
        $manager->job_title = $request->job_title;
        $manager->proj_id = $request->proj_id;
        $manager->description = $request->description;
        $manager->status = $request->status;
        $manager->save();

        return redirect()->route('managers.index')
                        ->with('success','Manager created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function show(Manager $manager)
    {
        return view('managers.show', compact('manager'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function edit(Manager $manager)
    {
        $id = $manager->id;
        $manager = Manager::leftJoin('projects', 'managers.proj_id', 'projects.id')
                          ->select('managers.*', 'projects.name as project')
                          ->where('managers.id', $id)
                          ->first();
        $user = User::where('id', $manager->user_id)->first();
        $projects = Project::where('status', true)->get();

        return view('managers.edit', compact('manager'))
               ->with(compact('projects'))
               ->with('account', $user->email);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manager $manager)
    {
        $request->validate([
            'name'      => 'required',
            'account'   => 'required',
            'job_title' => 'required',
            'status'    => 'required',
        ]);

        $user = User::where('email', $request->account)->first();
        if ($user == null) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->account;
            $user->proj_id = $request->proj_id;
            $user->password = Hash::make($request->password);
            if ($request->proj_id == 0) {
                $user->role = 'manager';
            } else {
                $user->role = 'operator';
            }
            $user->save();
        }
        $manager->user_id     = $user->id;
        $manager->name        = $request->name;
        $manager->job_title   = $request->job_title;
        //$manager->proj_id     = $request->proj_id;
        $manager->description = $request->description;
        $manager->status      = $request->status;
        $manager->proj_id     = $request->proj_id;
        $user->proj_id  = $request->proj_id;
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        if ($request->proj_id == 0) {
            $user->role = 'manager';
        } else {
            $user->role = 'operator';
        }
        $user->save();
        $manager->save();

        return redirect()->route('managers.index')
                        ->with('success','Manager created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manager $manager)
    {
        $user = User::where('id', $manager->user_id)->first();
        $manager->delete();
        $user->delete();

        return redirect()->route('managers.index')
                        ->with('success','Project deleted successfully');
    }
}
