<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelephonyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the index of the section.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('telephony.index_tel');
    }
}
