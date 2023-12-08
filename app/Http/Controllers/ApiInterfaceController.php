<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProductType;
use App\Models\ProductCatagory;
use App\Models\ProductStatus;
use App\Models\Product;
use App\Models\ProductRecord;
use App\Models\ProductQuery;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ApiInterfaceController extends Controller
{

/*
    public function projectapi(Request $request)
    {
        $func = $request->input('func');

        $rid        = $request->input('id');
        $sales_id   = $request->input('sales_id');
        $name       = $request->input('name');
        $status     = $request->input('status');
        $start_time = $request->input('start');
        $stop_time  = $request->input('stop');

        $project = new Project;
        $project->rid = $rid;
        $project->sales_id = $sales_id;
        $project->name = $name;
        $project->status = $status;
        $project->start_time = $start_time;
        $project->stop_time = $stop_time;
        $project->save();

    }
*/

    public function queryRechargeData(Request $request)
    {
        $ether_mac = $request->input('ether_mac');
        $wifi_mac = $request->input('wifi_mac');
        $serialno = $request->input('serialno');

        //$mainurl = sprintf(env('RECHARGE_MAIN_URL'), $ether_mac, $wifi_mac, $serialno);
        $PASS = '827GgqPxbwcnSCYbhtAbvaVDVfazAZXp';
        $str = 'index_server'.$serialno.$wifi_mac.$ether_mac.$PASS;
        $enc = strtolower(md5($str));
        $url = 'https://shop.mdo.tw/index.php?route_url=index_server&SerialNo='.$serialno.'&wMAC='.$wifi_mac.'&eMAC='.$ether_mac.'&chackCode='.$enc;

        $main = array(
                'title'  => env('RECHARGE_MAIN_TITLE'),
                'url'    => $url,
        );
        $custom1 = array(
                'title'  => env('RECHARGE_CUSTOM1_TITLE'),
                'url'    => env('RECHARGE_CUSTOM1_URL'),
        );
        $custom2 = array(
                'title'  => env('RECHARGE_CUSTOM2_TITLE'),
                'url'    => env('RECHARGE_CUSTOM2_URL'),
        );

        $result = array(
                  'main'    => $main,
                  'custom1' => $custom1,
                  'custom2' => $custom2,
        );

        return json_encode($result);
    }

    public function selectProject(Request $request)
    {
        $serialno  = $request->input('serialno');
        $ether_mac = $request->input('ether_mac');
        $wifi_mac  = $request->input('wifi_mac');
        $mac       = $request->input('mac');
        $project_id = $request->input('project_id');
        $project = null;

        if ($project_id != null) {
            $project = Project::find($project_id);
        }

        if ($project == null) {
            $err = "No Project";
            return $err;
        }

        if ($ether_mac) {
            $mac = str_replace(':', '', $ether_mac);
            $mac = strtoupper($mac);
            $product = Product::select('id', 'serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('ether_mac', '=', $mac)
                              ->first();
        } else if($wifi_mac) {
            $mac = str_replace(':', '', $wifi_mac);
            $mac = strtoupper($mac);
            $product = Product::select('id', 'serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('wifi_mac', '=', $mac)
                              ->first();
        } else if ($serialno) {
            $product = Product::select('id', 'serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('serialno', '=', $serialno)
                              ->first();
        } else if ($mac) {
            $product = Product::select('id', 'serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('ether_mac', '=', $mac)
                              ->orWhere('wifi_mac', '=', $mac)
                              ->first();
        }

        if ($product == null) {
                $err = "No Product";
                return $err;
        }

        $product->proj_id = $project_id;
        $product->save();

        $resp = 'OK';
        return $resp;
    }

    public function checkdate(Request $request)
    {
        $serialno  = $request->input('serialno');
        $ether_mac = $request->input('ether_mac');
        $wifi_mac  = $request->input('wifi_mac');
        $mac       = $request->input('mac');
        $aid       = $request->input('aid');

        if ($ether_mac) {
            $mac = str_replace(':', '', $ether_mac);
            $mac = strtoupper($mac);
            $product = Product::select('id', 'android_id','serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('ether_mac', '=', $mac)
                              ->first();
        } else if($wifi_mac) {
            $mac = str_replace(':', '', $wifi_mac);
            $mac = strtoupper($mac);
            $product = Product::select('id', 'android_id', 'serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('wifi_mac', '=', $mac)
                              ->first();
        } else if ($serialno) {
            $product = Product::select('id', 'android_id', 'serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('serialno', '=', $serialno)
                              ->first();
        } else if ($mac) {
            $product = Product::select('id', 'android_id', 'serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('ether_mac', '=', $mac)
                              ->orWhere('wifi_mac', '=', $mac)
                              ->first();
        }
        if ($aid) {
            $product = Product::select('id', 'android_id', 'serialno', 'ether_mac', 'wifi_mac', 'expire_date')
                              ->where('android_id', $aid)
                              ->first();
        }

        if ($product == null) {
             $data = [
                      'android_id'    => $aid,
                      'serialno'      => $serialno,
                      'ether_mac'     => $ether_mac,
                      'wifi_mac'      => $wifi_mac,
                      'type_id'       => 14,
                      'proj_id'       => 9,
                      'status_id'     => 1,
                      'user_id'       => 2,
                      'expire_date'   => '2075-12-31 00:00:00',
             ];
             $data1 = Product::create($data);
             $arr = [
                      'android_id'    => $data1['android_id'],
                      'serlalno'      => $data1['serialno'],
                      'ether_mac'     => $data1['ether_mac'],
                      'wifi_mac'      => $data1['wifi_mac'],
                      'expire_date'   => $data1['expire_date'],
             ];
             return json_encode($arr);
        } else {
           if ($aid) {
                $data = $product;
                $data['android_id'] = $aid;
                $product->update($data);
           }
        }
/*
        if ($product == null) {
            $PASS = '827GgqPxbwcnSCYbhtAbvaVDVfazAZXp';
            $str = 'index_bond'.$serialno.$wifi_mac.$ether_mac.$PASS;
            //var_dump($str);
            $enc = strtolower(md5($str));
            $url = 'https://shop.mdo.tw/index.php?route_url=index_bond&SerialNo='.$serialno.'&wMAC='.$wifi_mac.'&eMAC='.$ether_mac.'&chackCode='.$enc;
            $result = array(
                      'qrcode' => $url,
                      );
            return json_encode($result);
        }
*/
        $result = $product->toArray();
        unset($result['id']);
        $response = json_encode($result);

        if ($product && ProductQuery::enabled()) {
                $record = array(
                          'product_id'  => $product->id,
                          'keywords'    => 'checkdate',
                          'query'       => $request->fullUrl(),
                          'response'    => $response,
                );
                ProductQuery::create($record);
        }
        return $response;
    }

    public function register(Request $request)
    {
        $mac_eth = null;
        $mac_wifi = null;
        $sno = null;
        if ($request->input('mac_eth')) {
            $mac_eth = $request->input('mac_eth');
        }
        if ($request->input('mac_wifi')) {
            $mac_wifi = $request->input('mac_wifi');
        }
        if ($request->input('sno')) {
            $sno = $request->input('sno');
        }

        $url   = "https://mundifar.com/mundi/api_test.php";
        $str1  = "token=lakjhfklbyrcf;oasdniuvfp%27omsaigvepo";
        $str2  = "wMAC=".$mac_wifi;
        $str3  = "eMAC=".$mac_eth;
        $str4  = "SerialNo=".$sno;

        $url = $url."?".$str1."&".$str2."&".$str3."&".$str4;

        $response = Http::get($url);

        return $response;

    }

    // sample : https://major.mdo.tw/api/productRegister?type_id=3&proj_id=1&sno=&ether=112233445566&wifi=112233445567&expired=2021/12/01
    public function productRegister(Request $request)
    {
        $type_id = $request->input('type_id');
        $proj_id = $request->input('proj_id');
        $sno     = $request->input('sno');
        $ether   = $request->input('ether');
        $wifi    = $request->input('wifi');
        $expired = $request->input('expired');
        $status  = $request->input('status_id');
        if (($ether != null) && (strlen($ether) != 12)) {
            return "ether mac is invalid";
        }
        if (($wifi != null) && (strlen($wifi) != 12)) {
            return "wifi mac is invalid";
        }
        if ($status == null) {
            $status = 1;
        }

        if (($ether != null) && ($wifi != null)) {
            $product = Product::Where('ether_mac', 'LIKE', $ether)
                              ->Where('wifi_mac', 'LIKE', $wifi)
                              ->first();
        } else if ($ether == null) {
            $product = Product::Where('wifi_mac', 'LIKE', $wifi)
                              ->first();
        } else if ($wifi == null) {
            $product = Product::Where('ether_mac', 'LIKE', $ether)
                              ->first();
        }

        if ($product) {
            $data = array(
                    'proj_id'     => $proj_id,
                    'expire_date' => $expired,
                    'status_id'   => $status,
            );
            $product->update($data);

            $record = array(
                    'user_id'    => 0,
                    'product_id' => $product->id,
                    'from'       => 'api',
                    'data'       => json_encode($data),
                    'result'     => "Update OK",
            );
            ProductRecord::create($record);

            return "update ok";
        } else {
            $data = array(
                'type_id'      => $type_id,
                'proj_id'      => $proj_id,
                'serialno'     => $sno,
                'ether_mac'    => $ether,
                'wifi_mac'     => $wifi,
                'expire_date'  => $expired,
                'status_id'    => $status,
           );

           $result = true;
           try {
               Product::create($data);
               $product = Product::latest()->first();
               $record = array(
                    'user_id'    => 0,
                    'product_id' => $product->id,
                    'from'       => 'api',
                    'data'       => json_encode($data),
                    'result'     => "Insert OK",
               );
               ProductRecord::create($record);
               return "insert ok";
           } catch(\Illuminate\Database\QueryException $exception) {
               $result = false;
               $record = array(
                    'user_id'    => 0,
                    'product_id' => 0,
                    'from'       => 'api',
                    'data'       => json_encode($data),
                    'result'     => "Insert Error",
               );
               ProductRecord::create($record);
               return "insert error";
           }
       }

    }

    public function queryProductCatagory(Request $request)
    {
       $catagories = ProductCatagory::select('id', 'name')
                                    ->get();

       if ($catagories !=null) {
           $result = $catagories->toArray();
           return json_encode($result);
       }
       return "null";
    }

    public function queryProductType(Request $request)
    {
       $productTypes = ProductType::where('status', true)
                                 ->orderBy('id', 'ASC')
                                 ->get();
       if ($productTypes != null) {
           $result = $productTypes->toArray();
           return json_encode($result);
       }
       return "null";

    }

    public function queryProductStatus(Request $request)
    {
       $productStatuses = ProductStatus::select('id', 'name')
                                       ->get();

       if (count($productStatuses) > 0) {
           $result = $productStatuses->toArray();
           return json_encode($result);
       }

       return "null";
    }

    public function queryProject(Request $request)
    {
       $sales_id = $request->input('sales_id');

       if ($sales_id == null) {
           $projects = Project::where('status', true)
                              ->orderBy('id', 'ASC')
                              ->get();
           if ($projects != null) {
              $result = $projects->toArray();
              return json_encode($result);
           } else {
              return "null";
           }
       } else {
           $projects = Project::where('status', true)
                              ->where('sales_id', $sales_id)
                              ->orderBy('id', 'ASC')
                              ->get();
           if ($projects !=null) {
               $result = $projects->toArray();
               return json_encode($result);
           } else {
               return "null";
           }
       }

    }

    public function saveProductCatagory(Request $request)
    {

       $name = $request->input('name');
       $catagory = ProductCatagory::select('name')
                                  ->where('name', $name)
                                  ->first();
       if ($catagory != null) {
           return "Product Catagory name is exist!\n";
       }

       $descriptions = $request->input('descriptions');

       $data = array(
               'name'          => $name,
               'descriptions'  => $descriptions,
       );

       ProductCatagory::create($data);

       return "Product Catagory insert OK!\n";
    }

    public function saveProductType(Request $request)
    {
       $catagory_id  = $request->input('catagory');
       $name         = $request->input('name');
       $descriptions = $request->input('descriptions');

       $type = ProductType::select('name')
                          ->where('name', $name)
                          ->first();
       if ($type) {
           return "Product Type name is exist!\n";
       }

       $data = array(
               'catagory_id'    => $catagory_id,
               'name'           => $name,
               'descriptions'   => $descriptions,
               'status'         => true,
       );

       ProductType::create($data);

       return "Product Tupe insert OK!\n";
    }

    public function saveProductStatus(Request $request)
    {
       $name = $request->input('name');
       $detail = $request->input('detail');

       $status = ProductStatus::where('name', $name)->first();

       if ($status) {
           return "Product Status name is exist!\n";
       }

       $data = array(
               'name'      => $name,
               'detail'    => $detail,
       );

       ProductStatus::create($data);

       return "Product Status insert OK!\n";
    }

    public function timestamp()
    {
        date_default_timezone_set("Asia/Taipei");

        $array = array(
              'ZONE' => 'Asia/Taipei',
              'time' => microtime(true),
        );
        return json_encode($array);
    }
}

