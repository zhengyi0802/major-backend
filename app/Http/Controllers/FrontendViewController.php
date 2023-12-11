<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\Logo;
use App\Models\Business;
use App\Models\Advertising;
use App\Models\MainVideo;
use App\Models\Bulletin;
use App\Models\AppMenu;
use App\Models\AppManager;
use App\Models\ApkManager;
use App\Models\OneKeyInstaller;
use App\Models\User;
use Illuminate\Http\Request;

class FrontendViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //const var $version = '0.11.00';

    public function index()
    {
        $user = auth()->user();
        $proj_id = $user->proj_id;
        if ($proj_id == 0) {
            $projects = Project::where('status', true)->get();
            return view('frontend_views.index', compact('projects'));
        } else {
            $project = Project::where('id', $proj_id)->first();
            return $this->edit($project);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend_views.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return view('frontend_views.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FrontendView  $frontendView
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('frontend_views.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FrontendView  $frontendView
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        if ($project->id == 0) $this->index();
        $frontend_view = $this->getElements($project->id);


        return view('frontend_views.edit', compact('project'))
               ->with(compact('frontend_view'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FrontendView  $frontendView
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        return view('frontend_views.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FrontendView  $frontendView
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        return view('frontend_views.index');
    }

    public function getElements($id)
    {
         $logo = Logo::select('image')
                     ->where('proj_id', $id)
                     ->orWhere('proj_id', 0)
                     ->where('status', true)
                     ->orderBy('updated_at', 'desc')
                     ->first();

         $business = Business::select('logo_url')
                             ->where('proj_id', $id)
                             ->where('status', true)
                             ->orderBy('updated_at', 'desc')
                             ->first();

         $advertising = Advertising::select('thumbnail')
                                   ->where('proj_id', $id)
                                   ->where('status', true)
                                   ->orderBy('updated_at', 'desc')
                                   ->first();

         $mainvideo = MainVideo::select('type', 'playlist')
                               ->where('proj_id', $id)
                               ->where('status', true)
                               ->orderBy('updated_at', 'desc')
                               ->first();
         if ($mainvideo) {
             $svideos = $mainvideo->toArray();
             $randvideos = collect($videos)->rand(sizeof($svideos));
             $videos = $randvideos->all();
         }

         $bulletin = Bulletin::select('title')
                             ->where('proj_id', $id)
                             ->where('status', true)
                             ->orderBy('updated_at', 'desc')
                             ->first();

         $appmenus = AppMenu::select('position', 'thumbnail')
                            ->where('proj_id', $id)
                            ->where('status', true)
                            ->orderBy('position', 'asc')
                            ->get();

         $apps = array(null, null, null, null, null, null, null, null, null);
         foreach ($appmenus as $appmenu) {
             $apps[$appmenu->position-1] = $appmenu->thumbnail;
         }

         $result = array(
                        'logo'       => ($logo) ? $logo->image : null,
                        'customLogo' => ($business) ? $business->logo_url : null,
                        'ad'         => ($advertising) ? $advertising->thumbnail : null,
                        'videos'     => $videos,
                        'bulletin'   => ($bulletin) ? $bulletin->title : null,
                        'apps'       => $apps,
                   );

         return $result;
    }

    public function queryBusiness($proj_id)
    {
        $businesses = Business::where('proj_id', $proj_id)
                            ->where('status', true)
                            ->orderBy('updated_at', 'desc')
                            ->get();
        $count = $businesses->count();
        $result = array();
        foreach($businesses as $business) {
                $sresult = array(
                   'serial'      => $business->serial,
                   'image'       => $business->logo_url,
                   'link_url'    => $business->link_url,
                   'intervals'   => $business->intervals,
                   'updated_at'  => $business->updated_at,
                );
                array_push($result, $sresult);
        }
        return $result;
    }

    public function queryAdvertisings($proj_id)
    {
         $advertistings = Advertising::select('index', 'thumbnail as image', 'link_url', 'updated_at')
                                     ->where('proj_id', $proj_id)
                                     ->where('status', true)
                                     ->orderBy('index', 'asc')
                                     ->get();
         $result = null;
         if ($advertistings) {
             $result = $advertistings->toArray();
         }

         return $result;
    }

    public function queryMainVideo($proj_id)
    {
         $mainvideo = MainVideo::select('type', 'playlist', 'updated_at')
                               ->where('proj_id', $proj_id)
                               ->where('status', true)
                               ->orderBy('updated_at', 'desc')
                               ->first();
         $result = null;
         if ($mainvideo) {
             $aplaylist = json_decode($mainvideo->playlist);
             if(sizeof($aplaylist) > 1) {
                $cplaylist = collect($aplaylist);
                $rplaylist = $cplaylist->random(sizeof($aplaylist)-1);
                $dplaylist = $cplaylist->diff($rplaylist);
                $cplaylist = $rplaylist->merge($dplaylist);
                $playlist  = $cplaylist->all();
             } else {
                $playlist = $aplaylist;
             }
             $result = array(
                       'type'        => $mainvideo->type,
                       'playlist'    => $playlist,
                       'updated_at'  => $mainvideo->updated_at,
             );
         }

         return $result;
    }

    public function queryOneKeyInstaller($proj_id)
    {
       $result = null;

       $apks = AppManager::leftJoin('apk_managers', 'apk_id', 'apk_managers.id')
                         ->select('apk_managers.package_name', 'app_managers.delaytime')
                         ->where('app_managers.installer_flag', true)
                         ->where('app_managers.proj_id', $proj_id)
                         ->orWhere('app_managers.proj_id', 0)
                         ->where('app_managers.installer_flag', true)
                         ->get();

       if ($apks) {
           $result = $apks->toArray();
       }

       return $result;
    }

    public function query(Request $request)
    {
       if ($request->input('mac')) {
            $mac = str_replace(':', '', $request->input('mac'));
            $mac = strtoupper($mac);
            $product = Product::where('ether_mac', '=', $mac)
                                ->orWhere('wifi_mac', '=', $mac)
                                ->first();
            if ($product) {
                $proj_id = $product->proj_id;
            } else {
                $proj = Project::where('is_default', true)->first();
                $proj_id = $proj->id;
            }
        } else if ($request->input('id')) {
            $proj_id = $request->input('id');
        }

        if ($request->input('aid')) {
            $aid = $request->input('aid');
            $product1 = Product::where('android_id', $aid)->first();
            if ($product1 == null) {
                if ($product) {
                    $data = $product->toArray();
                    $data['android_id'] = $request->input('aid');
                    $product->update($data);
                    $proj_id = $product->proj_id;
                } else {
                    $arr = [
                         'android_id'   => $aid,
                         'type_id'      => 14,
                         'status_id'    => 1,
                         'proj_id'      => 9,
                         'user_id'      => 2,
                         'expire_date'  => '2075-12-31 00:00:00',
                    ];
                    $product = Product::create($arr);
                    $proj_id = 9;
                }
            } else {
                $proj_id = $product1->proj_id;
            }
        }

        $customer   = $this->queryBusiness($proj_id);
        $ad         = $this->queryAdvertisings($proj_id);
        $videos     = $this->queryMainVideo($proj_id);
        $onekey     = $this->queryOneKeyInstaller($proj_id);
        $result = array(
            //'fixed'       => $fixed,
            'custom'      => ($customer != null) ? $customer[0] : null,
            'logos'       => $customer,
            'ad'          => $ad,
            'videos'      => $videos,
            'onekey'      => $onekey,
        );
        $response = json_encode($result);
/*
        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'FrontendView',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }
*/
        return $response;
    }

}
