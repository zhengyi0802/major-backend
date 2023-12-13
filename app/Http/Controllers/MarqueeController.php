<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\Marquee;
use App\Models\User;
use Illuminate\Http\Request;

class MarqueeController extends Controller
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
            $marquees = Marquee::where('proj_id', $proj_id)->get();
        } else {
            $marquees = Marquee::get();
        }

        return view('marquees.index', compact('marquees'));
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

        $products = Product::get();

        return view('marquees.create', compact('projects'))
               ->with(compact('products'));
        //return view('marquees.create', compact('projects'));
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
            'type'     => 'required',
            'name'     => 'required',
            'content'  => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);
        Marquee::create($request->all());

        return redirect()->route('marquees.index')
                        ->with('success','Marquee created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marquee  $marquee
     * @return \Illuminate\Http\Response
     */
    public function show(Marquee $marquee)
    {
        return view('marquees.show', compact('marquee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marquee  $marquee
     * @return \Illuminate\Http\Response
     */
    public function edit(Marquee $marquee)
    {
        $projects = Project::where('status', true)
                           ->get();
        $products = Product::get();
        $marquees = Marquee::where('proj_id', $marquee->proj_id)
                           ->get();

        return view('marquees.edit', compact('marquee'))
               ->with(compact('projects'))
               ->with(compact('products'))
               ->with(compact('marquees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marquee  $marquee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marquee $marquee)
    {
        $request->validate([
            'type'     => 'required',
            'name'     => 'required',
            'content'  => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);
        $marquee->update($request->all());

        return redirect()->route('marquees.index')
                        ->with('success','Marquee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marquee  $marquee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marquee $marquee)
    {
        $marquee->delete();

        return redirect()->route('marquees.index')
                        ->with('success','Marquee deleted successfully');
    }

    public function query(Request $request)
    {
        $product = null;
        $proj_id = $this->checkProject($request);
        if ($request->input('type')) {
            $type = $request->input('type');
            if ($type == 1) {
               if ($product == null) {
                   return json_encode(array());
               }
               $marquees = Marquee::select('type', 'name', 'content', 'url')
                                  ->where('product_id', $product->id)
                                  ->where('status', true)
                                  ->where('type', $type)
                                  ->get();
            } else if ($type == 2 || $type == 3) {
               $marquees = Marquee::select('type', 'name', 'content', 'url' )
                                  ->where('status', true)
                                  ->where('type', $type)
                                  ->get();
            }
        } else {
            $marquees = Marquee::select('type', 'name', 'content', 'url')
                        ->where('proj_id', $proj_id)
                        ->where('status', true)
                        ->orderBy('type', 'asc')
                        ->get();
        }
        //var_dump($marquees);

        $result = null;
        if ($marquees) {
            $result = $marquees->toArray();
        }

        $response = json_encode($result);
        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'Marquee',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return json_encode($result);
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
                     'serialno'     => 'marquee',
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
