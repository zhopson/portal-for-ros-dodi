<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Input;
//use Illuminate\Http\Request;
use Request;
use Response;

class AddressController extends Controller {

    public function list_adr_components() {
        if (Request::ajax()) {
            $rname = Request::input('adr_part_val');
            $adr_part = Request::input('adr_part');
            //$parent = Request::input('parent');
            
            $adr_arr = '';
            $city_arr = '';
            $punkt_arr = '';
            $st_arr = '';
            
            if ($adr_part === 'raion') {
                $adr_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoguid FROM fias_addressobjects where aolevel=3 and currstatus=0 and parentguid = ? order by formalname", [$rname]);                
                $city_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoguid FROM fias_addressobjects where aolevel=4 and currstatus=0 and parentguid = ? order by formalname", [$rname]);                
                $punkt_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoguid FROM fias_addressobjects where aolevel=6 and currstatus=0 and parentguid = ? order by formalname", [$rname]);                
            }
            else if ($adr_part === 'np_city') {
                $adr_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoguid FROM fias_addressobjects where aolevel=6 and currstatus=0 and parentguid = ?", [$rname]);                
                $st_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoid FROM fias_addressobjects where aolevel>6 and currstatus=0 and parentguid = ? order by formalname", [$rname]);                
            }
            else if ($adr_part === 'np') {
                $adr_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoguid FROM fias_addressobjects where aolevel=6 and currstatus=0 and parentguid = ?", [$rname]);                
                $city_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoguid FROM fias_addressobjects where aolevel=4 and currstatus=0 and parentguid = ? order by formalname", [$rname]);                
                //$st_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoid FROM fias_addressobjects where aolevel>6 and currstatus=0 and parentguid = ? order by formalname", [$rname]);                
            }
            else if ($adr_part === 'st') {
                $adr_arr = DB::connection('pgsql_adr')->select("SELECT formalname, shortname,aoid FROM fias_addressobjects where currstatus=0 and parentguid = ? order by formalname", [$rname]);                
                //$st_arr = DB::connection('pgsql_adr')->select("SELECT rtf_formalname, rtf_shortname,rtf_aoguid FROM fstf_AddressObjects_SearchByName('', '', ?) where rtf_currstatus=0", [$rname]); 
            }
            //return Response::json(array('atr' => 'Hello', 'status' => 1));
            return Response::json(['adr_arr' => $adr_arr, 'city_arr' => $city_arr, 'punkt_arr' => $punkt_arr, 'st_arr' => $st_arr, 'status' => 1]);
            //return Response::json(['rname' => $rname, 'adr_part' => $adr_part, 'status' => 1]);
        }
        return 'error';
    }

    public function do_chg_adr_components() {
        
        if (Request::ajax()) {
            $parent = Request::input('parent_aoguid');
            
            $raion_arr = '';
            $city_arr = '';
            $punkt_arr = '';
            
            $common = DB::connection('pgsql_adr')->select("SELECT formalname,shortname,aoguid,aoid,aolevel FROM fias_addressobjects where currstatus=0 and parentguid = ? order by formalname", [$parent]);

            //return Response::json(array('atr' => 'Hello', 'status' => 1));
            //return Response::json(['raion_arr' => $raion_arr, 'city_arr' => $city_arr, 'punkt_arr' => $punkt_arr, 'status' => 1]);
            return Response::json(['common' => $common, 'status' => 1]);
        }
        return 'error';
    }
    
    
//    public function list_adr_component() {
//        if (Request::ajax()) {
//            $rname=Request::input('region');
//            $adr_arr = DB::connection('pgsql_adr')->select("SELECT rtf_formalname, rtf_shortname,rtf_aoguid FROM fstf_AddressObjects_SearchByName('', '', ?) where rtf_aolevel=3", [$rname]);
//
//            //return Response::json(array('atr' => 'Hello', 'status' => 1));
//            return Response::json(['adr_arr' => [$adr_arr], 'status' => 1]);
//        }
//        return 'error';
//    }
    
}
