<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\order;
use App\Models\ShippingProcess;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    function index(Request $request)
    {
        $data = $request->all();
        $register = $data;
        $ether_mac = isset($data['ether_mac']) ? str_replace(':', '', $data['ether_mac']) : null;
        $wifi_mac = isset($data['wifi_mac']) ? str_replace(':', '', $data['wifi_mac']) : null;
        $aid = isset($data['aid']) ? $data['aid'] : null;
        $ether_mac = strtoupper($ether_mac);
        $wifi_mac = strtoupper($wifi_mac);
        $product = Product::where('android_id', $aid)->first();
        if (($product != null) && ($product->warranty != null)) {
            $register['phone'] = $product->warranty->phone;
            $register['register_time'] = $product->warranty->register_time;
            $register['expire_date'] = $product->expire_date;
            $register['warranty_date'] = date('Y-m-d', strtotime('+3 years', strtotime($product->warranty->register_time)));
            $register['type_id'] = $product->type_id;
            $order = $product->warranty->order();
            if ($order != null) {
                $register['name'] = $order->name;
                $register['address'] = $order->address;
                $register['model_id'] = $order->product->model;
                //dd($order->product->model);
                $register['android_id'] = $aid;
            }
            return view('register.show', compact('register'));
        } else {
            return view('register.index', compact('register'));
        }
    }

    function store(Request $request)
    {
        $data = $request->all();
        $register = $data;
        $ether_mac = isset($data['ether_mac']) ? str_replace(':', '', $data['ether_mac']) : null;
        $wifi_mac = isset($data['wifi_mac']) ? str_replace(':', '', $data['wifi_mac']) : null;
        $register['register_time'] = date('Y-m-d h-m-s');
        $register['expire_date'] = '';
        $register['warranty_date'] = date('Y-m-d', strtotime('+3 years'));
        $register['type_id'] = 14;
        $aid = isset($data['aid']) ? $data['aid'] : null;
        $ether_mac = strtoupper($ether_mac);
        $wifi_mac = strtoupper($wifi_mac);
        $product = Product::where('android_id', $aid)->first();
        if ($product != null) {
            $data['product_id'] = $product->id;
            $warranty = Warranty::create($data);
            if ($product->android_id == null) {
                $product->android_id = $aid;
                $product->save();
            }
            if ($warranty->order() != null) {
                $order = $warranty->order();
                $order->flow = 5;
                $order->save();
                $register['name'] = $order->name;
                $register['address'] = $order->address;
                $productModel = $order->product->model;
                $register['model_id'] = $productModel;
                $register['android_id'] = $aid;
                $shipping = ShippingProcess::where('order_id', $order->id)->first();
                if ($shipping != null) {
                    $shipping->completion_time = $register['register_time'];
                    $shipping->installer_id = 7;
                    $shipping->save();
                }
            }
        }

        return view('register.show', compact('register'));
    }

}
