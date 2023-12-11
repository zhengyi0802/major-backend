<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\BulletinItem;
use App\Models\User;
use Illuminate\Http\Request;

class BulletinController extends Controller
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
            $bulletins = Bulletin::where('proj_id', $proj_id)->where('status', true)->get();
        } else {
            if ($user->role == 'admin') {
                $bulletins = Bulletin::get();
            } else {
                $bulletins = Bulletin::where('status', true)->get();
            }
        }

        return view('bulletins.index', compact('bulletins'));
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
        if ($proj_id == 0) {
            $projects = Project::where('status', true)->get();
            return view('bulletins.create', compact('projects'));
        } else {
            $projects = Project::where('id', $proj_id)->get();
            return view('bulletins.create', compact('projects'));
        }
    }

    public function create2(Project $project)
    {
        $bulletin = new Bulletin;
        $bulletin->id = 0;

        return view('bulletins.create2', compact('bulletin'))
               ->with(compact('project'));
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
            'title'        => 'required',
            'message'      => 'required',
            'date'         => 'required',
            'status'       => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        Bulletin::create($request->all());

        return redirect()->route('bulletins.index')
                         ->with('success','Bulletin store successfully');
    }

    public function store2(Request $request, Project $project, Bulletin $bulletin)
    {
        $data = $request->all();
        $user = auth()->user();
        $data['proj_id'] = $project->id;
        $data['user_id'] = $user->id;
        if ($bulletin->id > 0) {
            $bulletin->update($request->all());
        } else {
            Bulletin::create($request->all());
        }

        return redirect()->route('frontend_views.edit', compact('project'))
                        ->with('success','Bulletin created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function show(Bulletin $bulletin)
    {
        return view('bulletins.show', compact('bulletin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function edit(Bulletin $bulletin)
    {
        $projects = Project::where('status', true)->get();

        return view('bulletins.edit', compact('bulletin'))
               ->with(compact('projects'));
    }

    public function edit2(Project $project)
    {
        $bulletin = Bulletin::where('status', true)
                            ->where('proj_id', $project->id)
                            ->orderBy('updated_at', 'desc')
                            ->first();

        if ($bulletin == null) {
            return $this->create2($project);
        }

        return view('bulletins.edit2', compact('bulletin'))
               ->with(compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bulletin $bulletin)
    {
        $request->validate([
            'proj_id'      => 'required',
            'title'        => 'required',
            'message'      => 'required',
            'date'         => 'required',
            'status'       => 'required',
        ]);
        $user = auth()->user();
        $request->merge(['user_id' => $user->id]);

        $bulletin->update($request->all());

        return redirect()->route('bulletins.index')
                         ->with('success','Bulletin store successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bulletin $bulletin)
    {
        $user = auth()->user();

        if ($user->role == 'admin') {
            $bulletinItems = BulletinItem::where('bulletin_id', $bulletin->id)->get()->delete();
            $bulletin->delete();
        } else {
            $bulletin->status = false;
            $bulletinItems = BulletinItem::where('bulletin_id', $bulletin->id)->get();
            foreach($bulletinitems as $item) {
                $item->status = false;
                $item->save();
            }
            $bulletin->save();
        }
        return redirect()->route('bulletins.index')
                         ->with('success','Bulletin deleted successfully');
    }
/*
    public function querySingle($oroj_id)
    {

        $datetime = date('y-m-d h:i:s');
        $bulletin = Bulletin::where('proj_id', $proj_id)
                               ->where('status', true)
                               ->where('date', '<=', $datetime)
                               ->orderBy('date', 'desc')
                               ->first();

        if ($bulletin == null) {
            return array();
        }

        $bulletinitems = BulletinItem::where('bulletin_id', $bulletin->id)->get();

        $items = array();
        foreach($bulletinitems as $bulletinitem) {
                $item = array(
                        'id'      => $bulletinitem->id,
                        'type'    => $bulletinitem->type,
                        'content' => $bulletinitem->url,
                );
                array_push($items, $item);
        }

        $result = array(
                'id'       => $bulletin->id,
                'title'    => $bulletin->title,
                'message'  => $bulletin->message,
                'status'   => $bulletin->status,
                'date'     => $bulletin->date,
                'items'    => $items,
        );

        return $result;
    }
*/
    public function queryBulletinItems($bulletin, $flag)
    {
        $bulletinitems = BulletinItem::where('bulletin_id', $bulletin->id)->get();

        $items = array();
        foreach($bulletinitems as $bulletinitem) {
                $item = array(
                        'id'          => $bulletinitem->id,
                        'type'        => $bulletinitem->mime_type,
                        'content'     => $bulletinitem->url,
                        'content2'    => $bulletinitem->url_http,
                );
                if ($flag) {
                    $updated_at = $bulletinitem->updated_at;
                    $item['updated_at'] = $updated_at->format("Y-m-d H:t:s");
                }
                array_push($items, $item);
        }

        $result = array(
                'id'       => $bulletin->id,
                'title'    => $bulletin->title,
                'message'  => $bulletin->message,
                'date'     => $bulletin->date,
                'items'    => $items,
        );

        return $result;
    }

    public function queryBulletins($proj_id, $start, $page, $numbers)
    {
        $datetime = date('y-m-d H:i:s');
        $counts = Bulletin::where('proj_id', $proj_id)
                          ->where('status', true)
                          ->where('date', '<=', $datetime)
                          ->count();

        if ($page == -1) $page = 1;

        if ($start == -1) $start = ($page-1) * $numbers + 1;

        $totalpage = (int) ($counts / $numbers) + 1;

        $bulletins = Bulletin::where('proj_id', $proj_id)
                               ->where('status', true)
                               ->where('date', '<=', $datetime)
                               ->orderBy('date', 'desc')
                               ->orderBy('id', 'desc')
                               ->skip($start-1)
                               ->take($numbers)
                               ->get();
        if ($bulletins == null) {
            return array();
        }

        $array = array();
        foreach($bulletins as $bulletin) {
                array_push($array, $this->queryBulletinItems($bulletin, false));
        }

        $result = array(
                'page'      => (int) $page,
                'totalpage' => (int) $totalpage,
                'bulletins' => $array,
        );

        return $result;
    }

    public function query(Request $request)
    {
        $popup = $request->input('popup');

        if ($popup == null || $popup == "false") {
            $popup = 0;
        }
        $product = null;
        $proj_id = $this->checkProject($request);
        if ($request->input('start')) {
            $start = $request->input('start');
        } else {
            $start = -1;
        }

        $page = 1;
        if ($request->input('page')) {
            $page = $request->input('page');
        } else {
            $page = -1;
        }

        $numbers = 10;
        if ($request->input('numbers')) {
            $numbers = $request->input('numbers');
        }

        if ($popup) {
          $bulletin = Bulletin::where('proj_id', $proj_id)
                            ->where('popup', true)
                            ->where('status', true)
                            ->orderBy('date', 'DESC')
                            ->first();
          if ($bulletin) {
              $result = $this->queryBulletinItems($bulletin, true);
          } else {
              $result = null;
          }
        } else {
          $result = $this->queryBulletins($proj_id, $start, $page, $numbers);
        }
        $response = json_encode($result);

        if ($product && ProductQuery::enabled()) {
            $record = array(
                      'product_id'  => $product->id,
                      'keywords'    => 'Bulletin',
                      'query'       => $request->fullUrl(),
                      'response'    => $response,
            );
            ProductQuery::create($record);
        }

        return $response;
    }

    function checkProject(Request $request)
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
            $mproduct->android_id = $aid;
            $mproduct->save();
        } else {
            $project = Project::where('is_default', true)->first();
            $proj_id = $project->id;
            if ($aid != null) {
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
        }
        return $proj_id;
    }

}
