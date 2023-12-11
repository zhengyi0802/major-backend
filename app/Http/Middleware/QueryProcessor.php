<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Http\Request;

class QueryProcessor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $data = $request->all();
        if (isset($data['mac'])) {
            $mproduct = Product::where('ether_mac', $data['mac'])->orWhere('wifi_mac', $data['mac'])->first();
        } else {
            $mproduct = null;
        }
        if (isset($data['aid'])) {
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
            $mproduct->android_Id = $aid;
            $mproduct->save();
        } else {
            $proj_id = Project::where('is_default', true)->first();
            $arr = [
                     'android_id'   => $aid,
                     'type_id'      => 14,
                     'status_id'    => 1,
                     'proj_id'      => $proj_id,
                     'user_id'      => 2,
                     'expire_date'  => '2075-12-31 00:00:00',
                   ];
            $product = Product::create($arr);
        }

        return $next($request, $proj_id);
    }
}
