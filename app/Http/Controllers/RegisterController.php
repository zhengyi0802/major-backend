<?php

namespace App\Http\Controllers;

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
        $register['register_date'] = date('Y-m-d h-m-s');
        return view('register.show', compact('register'));
    }

}
