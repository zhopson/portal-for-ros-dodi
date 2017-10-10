<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
//use Illuminate\Http\Request;
use Request;
use Response;

class AddressController extends Controller {

    public function list_regions() {
        //if (Request::ajax()) {
            $rname=Request::input('region');
            $uus_arr = DB::connection('pgsql_adr')->select("SELECT rtf_formalname, rtf_shortname,rtf_aoguid FROM fstf_AddressObjects_SearchByName('', '', ?) where rtf_aolevel=3", [$rname]);

            //return Response::json(array('atr' => 'Hello', 'status' => 1));
            return Response::json(['uus_arr' => [$uus_arr], 'status' => 1]);
        //}
        //return 'error';
        
    }

}
