<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Models\Product;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    function index(Request $request)
    {
        $register = $request->all();

        return view('register.index', compact('register'));
    }

    function store(Request $request)
    {
        $data = $request->all();
        $register = $data;
        $ether_mac = isset($data['ether_mac']) ? str_replace(':', '', $data['ether_mac']) : null;
        $wifi_mac = isset($data['wifi_mac']) ? str_replace(':', '', $data['wifi_mac']) : null;
        $register['register_date'] = date('Y-m-d h-m-s');
        $aid = isset($data['aid']) ? $data['aid'] : null;
        $ether_mac = strtoupper($ether_mac);
        $wifi_mac = strtoupper($wifi_mac);
        $product = Product::Where('wifi_mac', '=', $wifi_mac)->first();

        if ($product != null) {
            $data['product_id'] = $product->id;
            Warranty::create($data);
            if ($product->android_id == null) {
                $product->android_id = $aid;
                $product->save();
            }
        }

        return view('register.show', compact('register'));
    }

}
