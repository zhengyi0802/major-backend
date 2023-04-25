<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\ProductStatus;
use App\Models\ProductRecord;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
//use Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $numbers = 500;
        $user = auth()->user();
        $proj_id = $user->proj_id;
        $search = $request->input("adminlteSearch");
        if ($search != null) {
            $product = $this->search($search);
            if ($product != null) {
                $records = ProductRecord::where('product_id', $product->id)->get();

                return view('products.show', compact('product'))
                       ->with(compact('records'));
            }
        }
        if ($proj_id == 0) {
           $products = Product::latest()->paginate($numbers);
        } else {
           $products = Product::where('proj_id', $proj_id)->paginate($numbers);
        }
        if ($products) {
            foreach($products as $product) {
                $product->serialno = strtoupper($product->serialno);
                $mac_array = str_split($product->ether_mac, 2);
                $macaddress = implode(':', $mac_array);
                $product->ether_mac = strtoupper($macaddress);
                $mac_array = str_split($product->wifi_mac, 2);
                $macaddress = implode(':', $mac_array);
                $product->wifi_mac = strtoupper($macaddress);
            }
        }
        return view('products.index',compact('products'))
              ->with('i', (request()->input('page', 1) - 1) * $numbers);
    }

    public function search($search)
    {
        if (strpos($search, ":") === false) {
            $product = Product::where('products.serialno', 'LIKE', $search)->first();
        } else {
            $mac = str_replace(":", "", $search);
            $mac = strtoupper($mac);
            $product = Product::where('products.ether_mac', 'LIKE', $mac)
                              ->orWhere('products.wifi_mac', 'LIKE', $mac)
                              ->first();
        }

        return $product;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productTypes = ProductType::get();
        $productStatuses = ProductStatus::get();
        $projects = Project::where('status', true)->get();

        return view('products.create', compact('productTypes'))
               ->with(compact('productStatuses'))
               ->with(compact('projects'));
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
            'type_id' => 'required',
            'status_id' => 'required',
        ]);

        $ether_mac = str_replace(":", "", $request->input('ether_mac'));
        $ether_mac = strtoupper($ether_mac);
        $request->merge(array('ether_mac' => $ether_mac));

        $wifi_mac = str_replace(":", "", $request->input('wifi_mac'));
        $wifi_mac = strtoupper($wifi_mac);
        $request->merge(array('wifi_mac' => $wifi_mac));

        Product::create($request->all());

        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        $mac_array = str_split($product->ether_mac, 2);
        $ether_mac = implode(':', $mac_array);
        $product->ether_mac = strtoupper($ether_mac);
        $mac_array1 = str_split($product->wifi_mac, 2);
        $wifi_mac = implode(":", $mac_array1);
        $product->wifi_mac = strtoupper($wifi_mac);
        $product->serialno = strtoupper($product->serialno);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $productTypes = ProductType::get();
        $productStatuses = ProductStatus::get();
        $projects = Project::where('status', true)->get();
        $mac_array = str_split($product->ether_mac, 2);
        $ether_mac = implode(':', $mac_array);
        $product->ether_mac = $ether_mac;
        $mac_array1 = str_split($product->wifi_mac, 2);
        $wifi_mac = implode(":", $mac_array1);
        $product->wifi_mac = $wifi_mac;

        return view('products.edit', compact('product'))
               ->with(compact('productTypes'))
               ->with(compact('projects'))
               ->with(compact('productStatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $user = auth()->user();
        $data = $request->all();
        if ($user->role == 'admin') {
            $ether_mac = str_replace(":", "", $data['ether_mac']);
            $ether_mac = strtoupper($ether_mac);
            $data['ether_mac'] = $ether_mac;

            $wifi_mac = str_replace(":", "", $data['wifi_mac']);
            $wifi_mac = strtoupper($wifi_mac);
            $data['wifi_mac'] = $wifi_mac;
        }

        $product->update($data);

        if (true) {
            $proj_id = $data['proj_id'];
            $expire_date = $data['expire_date'];
            $status_id = $data['status_id'];
            $user_id = $user->id;

            $data_record = array(
                      'proj_id'     => $proj_id,
                      'expire_date' => $expire_date,
                      'status_id'   => $status_id,
            );
            $jdata = json_encode($data_record);
            $record = array(
                      'product_id'  => $product->id,
                      'user_id'     => $user_id,
                      'from'        => 'browser',
                      'data'        => $jdata,
            );
            ProductRecord::create($record);
        }

        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }

    public function register(Request $request)
    {
        $mac = $request->input('mac');
        //$mac = str_replace(':', '', $request->input('mac'));
        $mac = strtoupper($mac);

        $product = Product::where('ether_mac', '=', $mac)
                           ->orWhere('wifi_mac', '=', $mac)
                           ->latest()
                           ->get();

        return json_encode($product);
    }

    public function query(Request $request)
    {
        if ($request->input('mac')) {
            $mac = $request->input('mac');
            //$mac = str_replace(':', '', $request->input('mac'));
            $mac = strtoupper($mac);
        } else {
          $mac = "001A79A75F37";
        }

        $str = "https://mundifar.com/mundi/API/index_api.php?mac=".$mac."&token=Wg7DZTmTapH2Ww2sAeNfmhhfXzYqEt6Y";
        //$str1 = "https://mundifar.com/mundi/API/index_api.php?mac=001A79A75F37&token=Wg7DZTmTapH2Ww2sAeNfmhhfXzYqEt6Y";

        $response = Http::get($str);

        //$a = array("sno"=>"1812SP003131", "mac"=> "001A79A75F37", "wmac"=>"001A79A75F37", "sales_id"=>"", "project_id"=>"", "exp_date" => "2021-06-01 16:53:25", "status"=> "n", "message"=>"ok");

        $str1=substr($response->body(), 3);

        //var_dump(json_decode($str1));

        return json_encode($str1);
    }

}

