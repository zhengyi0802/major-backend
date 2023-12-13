<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Models\Product;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    function index()
    {
        return view('register.index');
    }

    function store(Request $request)
    {
        $data = $request->all();
        $register = $data;
        $register['register_date'] = date('Y-m-d h-m-s');
        $aid = $data['aid'];
        $ether_mac = $data['ether_mac'];
        $wifi_mac = $data['wifi_mac'];
        $product = Product::when($aid != null, function($q) use($aid) {
                     $q->where('android_id', $aid);})
                  ->when($ether_mac != null, function($q) use($ether_mac) {
                     $q->orWhere('ether_mac', $ether_mac);})
                  ->when($wifi_mac != null, function($q) use($wifi_mac) {
                     $q->orWhere('wifi_mac', $wifi_mac);})->first();
        if ($product->android_id == null) {
            $product->android_id = $aid;
            $product->save();
        }
        if ($project != null) {
            $data['product_id'] = $product->id;
            Warranty::create($data);
        }

        return view('register.show', compact('register'));
    }

}
