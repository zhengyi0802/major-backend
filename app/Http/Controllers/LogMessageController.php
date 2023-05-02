<?php

namespace App\Http\Controllers;

use App\Models\LogMessage;
use Illuminate\Http\Request;
//use Request;

class LogMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input("adminlteSearch");
        $numbers = 2000;
        $actions = array('start', 'info', 'error');
        if ($search != null) {
            $elements = explode(' ', $search);
            if (isset($elements[1])) {
                if (str_contains($elements[1], ":")) {
                    $logmessages = LogMessage::where('action', $elements[0])
                                     ->where('mac_eth', $elements[1])
                                     ->latest()->paginate($numbers);
                } else {
                    $logmessages = LogMessage::where('action', $elements[0])
                                     ->where('sn', $elements[1])
                                     ->latest()->paginate($numbers);
                }
            } else {
                if (in_array($elements[0], $actions)) {
                   $logmessages = LogMessage::where('action', $elements[0])
                                     ->latest()->paginate($numbers);
                } else {
                   if (str_contains($elements[0], ":")) {
                       $logmessages = LogMessage::where('mac_eth', $elements[0])
                                     ->latest()->paginate($numbers);
                   } else {
                       $logmessages = LogMessage::where('sn', $elements[0])
                                     ->latest()->paginate($numbers);
                   }
                }
            }
        } else {
           $logmessages = LogMessage::latest()->paginate($numbers);
        }
        foreach($logmessages as $logmessage) {
           $logmessage->mac_eth = strtoupper($logmessage->mac_eth);
           $logmessage->mac_wifi = strtoupper($logmessage->mac_wifi);
        }
        return view('logmessages.index', compact('logmessages'))
               ->with('i', (request()->input('page', 1) - 1) * $numbers);
    }

    public function browse($mac)
    {
        $numbers= 20;

        $logmessages = LogMessage::where('mac_eth', 'LIKE', $mac)
                                   ->latest()
                                   ->paginate($numbers);

        return view('logmessages.browse', compact('logmessages'))
               ->with('mac', $mac)
               ->with('i', (request()->input('page', 1) -1) * $numbers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logmessages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        LogMessage::create($request->all());

        return redirect()->route('logmessages.index')
                        ->with('success','Log Message created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LogMessage  $logMessage
     * @return \Illuminate\Http\Response
     */
    public function show(LogMessage $logmessage)
    {
        $logmessage->sn = strtoupper($logmessage->sn);
        $logmessage->mac_eth = strtoupper($logmessage->mac_eth);
        $logmessage->mac_wifi = strtoupper($logmessage->mac_wifi);
        return view('logmessages.show', compact('logmessage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LogMessage  $logMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(LogMessage $logmessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LogMessage  $logMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogMessage $logmessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogMessage  $logMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogMessage $logmessage)
    {
        //
    }

    public function savelog(Request $request)
    {

       $postbody='';
       // Check for presence of a body in the request
       if (count($request->json()->all())) {
          $postbody = $request->json()->all();
       }

       LogMessage::create($postbody);

       $response = "ok\n";

       return $response;
    }

}
