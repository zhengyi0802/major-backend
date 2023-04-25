<?php
namespace App\Http\Controllers;

use Hash;
use App\Models\Reseller;
use App\Models\User;
use Illuminate\Http\Request;

class ResellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resellers = Reseller::get();

        return view('resellers.index',compact('resellers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('resellers.create');
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
            'company'  => 'required',
            'account'  => 'required',
            'password' => 'required',
            'contact'  => 'required',
            'cotype'   => 'required',
            'zipcode'  => 'required',
            'address'  => 'required',
            'phones'   => 'required',
            'status'   => 'required',
        ]);

        //Reseller::create($request->all());
        $user = new User;
        $user->name = $request->contact;
        $user->email = $request->account;
        $user->password = Hash::make($request->password);
        $user->role = "reseller";
        $user->save();

        $reseller = new Reseller;
        $reseller->company = $request->company;
        $reseller->user_id = $user->id;
        $reseller->contact = $request->contact;
        $reseller->cotype  = $request->cotype;
        $reseller->zipcode = $request->zipcode;
        $reseller->address = $request->address;
        $reseller->status  = $request->status;
        $reseller->phones  = $request->phones;
        $reseller->save();

        return redirect()->route('resellers.index')
                        ->with('success','Reseller created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reseller  $reseller
     * @return \Illuminate\Http\Response
     */
    public function show(Reseller $reseller)
    {
        return view('resellers.show', compact('reseller'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reseller  $reseller
     * @return \Illuminate\Http\Response
     */
    public function edit(Reseller $reseller)
    {
        $user = User::where('id', $reseller->user_id)->first();

        return view('resellers.edit', compact('reseller'))
               ->with('account', $user->email);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reseller  $reseller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reseller $reseller)
    {
        $request->validate([
            'company'  => 'required',
            'account'  => 'required',
            'password' => 'required',
            'contact'  => 'required',
            'cotype'   => 'required',
            'zipcode'  => 'required',
            'address'  => 'required',
            'phones'   => 'required',
            'status'   => 'required',
        ]);

        //Reseller::update($request->all());
        $user = User::where('id', $request->user_id)->first();
        if ($user == null) {
            $user           = new User;
            $user->name     = $request->contact;
            $user->email    = $request->account;
            $user->password = Hash::make($request->password);
            $user->role     = "reseller";
            $user->save();
        }

        $reseller->user_id  = $user->id;
        $reseller->company  = $request->company;
        $reseller->contact  = $request->contact;
        $reseller->cotype   = $request->cotype;
        $reseller->zipcode  = $request->zipcode;
        $reseller->address  = $request->address;
        $reseller->status   = $request->status;
        $reseller->save();

        return redirect()->route('resellers.index')
                        ->with('success','Reseller created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reseller  $reseller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reseller $reseller)
    {
        $user = User::where('id', $reseller->user_id)->first();
        $reseller->delete();
        $user->delete();

        return redirect()->route('resellers.index')
                        ->with('success','Reseller deleted successfully');
    }
}
