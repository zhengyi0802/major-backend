<?php

namespace App\Http\Controllers;

use App\Models\CustomerSupport;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\User;
use App\Http\Middleware\ImageUpload;
use Illuminate\Http\Request;

class CustomerSupportController extends Controller
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
            $customersupports = CustomerSupport::where('proj_id', $proj_id)->get();

        } else {
            $customersupports = CustomerSupport::get();
        }
        return view('customersupports.index', compact('customersupports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::where('status', true)->get();

        return view('customersupports.create', compact('projects'));
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
            'qrcode_type'  => 'required',
            'rcapp'        => 'required',
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;
        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $data['qrcode_content'] = env('APP_URL').$file->file_path;
        }

        CustomerSupport::create($data);

        return redirect()->route('customersupports.index')
                        ->with('success','CustomerSupport created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerSupport  $customerSupport
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerSupport $customersupport)
    {
        return view('customersupports.show', compact('customersupport'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerSupport  $customerSupport
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerSupport $customersupport)
    {
        $projects = Project::where('status', true)->get();

        return view('customersupports.edit', compact('customersupport'))
               ->with(compact('projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerSupport  $customerSupport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerSupport $customersupport)
    {
        $request->validate([
            'proj_id'      => 'required',
            'qrcode_type'  => 'required',
            'rcapp'        => 'required',
            'status'       => 'required',
        ]);

        $data = $request->all();
        $user = auth()->user();
        $data['user_id'] = $user->id;
        if ($request->file()) {
            $file = ImageUpload::fileUpload($request);
            if ($file == null) {
                return back()->with('image', $fileName);
            }
            $data['qrcode_content'] = env('APP_URL').$file->file_path;
            //var_dump($data);
        }

        var_dump($data);

        $customersupport->update($data);

        return redirect()->route('customersupports.index')
                        ->with('success','CustomerSupport updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerSupport  $customerSupport
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerSupport $customersupport)
    {
        $customersupport->delete();

        return redirect()->route('customersupports.index')
                        ->with('success','CustomerSupport deleted successfully');
    }

    public function query(Request $request)
    {
        $product = null;
        $proj_id = $this->checkProject($request);
        if ($proj_id == 0) {
            return json_encode(null);
        }
        $customersupport = CustomerSupport::where('proj_id', $proj_id)
                                          ->orWhere('proj_id', '9')
                                          ->where('status', true)
                                          ->select('qrcode_type', 'qrcode_content', 'message',
                                                   'rcapp_label', 'rcapp', 'rcapp_url', 'updated_at')
                                          ->first();
        if ($customersupport) {
            $response = json_encode($customersupport);
            if ($product && ProductQuery::enabled()) {
                $record = array(
                          'product_id'  => $product->id,
                          'keywords'    => 'CustomerSupport',
                          'query'       => $request->fullUrl(),
                          'response'    => $response,
                );
                ProductQuery::create($record);
            }

            return $response;
        } else
           return json_encode(null);
    }

    function checkProject(Request $request)
    {
        $data = $request->all();
        $proj_id = 0;
        $mac=null;
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
                     'serialno'     => 'customersupport',
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
