<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Response;

class NetFlowController extends Controller
{
    public function clt_traf_gr($id = 0, $ip = 0) {

        $date1 = date_create(date('Y-m-d'));
        date_sub($date1, date_interval_create_from_date_string('1 days'));
        $start_date = date_format($date1, 'Y-m-d');
        $end_date = date('Y-m-d');
        
        return view('tsupport.clt_traf_gr',compact('id','ip','start_date','end_date'));
    }
    
    public function do_ajax_get_traf(Request $request) {
        if ($request->ajax()) {

            $id = $request->input('id');
            $ip = $request->input('ip');
            $date1 = $request->input('date1');
            $date2 = $request->input('date2');

            $start_date = $date1 . ' 00:00:00';
            $end_date = $date2 . ' 23:59:59';
            
            $traf = '';
            //$scale = 'КБ';
            
            if ($ip != 0)  //YYYY-MM-DD HH24:MI
                $traf = DB::connection('pgsql_netflow')->select("select bytes_in/1024 as bytes_in,bytes_out/1024 as bytes_out,to_char(period,'DD-MM HH24:MI') as period from public.view_user_stat_graph where ip_addr='".$ip."' and period>'".$start_date."' and period<'".$end_date."'");
            else if ($id != 0) 
                $traf = DB::connection('pgsql_netflow')->select("select bytes_in/1024 as bytes_in,bytes_out/1024 as bytes_out,to_char(period,'DD-MM HH24:MI') as period from public.view_user_stat_graph where user_id=".$id." and period>'".$start_date."' and period<'".$end_date."'");
            else //{ $scale = 'МБ';//traf for all clts 
                $traf = DB::connection('pgsql_netflow')->select("select sum(bytes_in/1024) as bytes_in,sum(bytes_out/1024) as bytes_out,to_char(period,'DD-MM HH24:MI') as period from public.view_user_stat_graph where period>'".$start_date."' and period<'".$end_date."' group by period");
            //return Response::json(array('ddt'=>$end_date, 'status' => 1));

            $data_dates = [];
            $data_values_in = [];
            $data_values_out = [];
//            $max_in = 0;
//            $max_out = 0;
            
            foreach ($traf as $row => $item) {

//                if ($item->bytes_in > $max_in)
//                    $max_in = $item->bytes_in;
//
//                if ($item->bytes_out > $max_out)
//                    $max_out = $item->bytes_out;

                array_push($data_dates, $item->period
                );

                array_push($data_values_in, $item->bytes_in
                );

                array_push($data_values_out, $item->bytes_out
                );
            }
            
//             if ($max_in > 1048576 || $max_out > 1048576) $scale = 'МБ';
//             else $scale = 'КБ';
            //return ['data'=>$data];
            return Response::json(['data_dates' => $data_dates,'data_values_in' => $data_values_in,'data_values_out' => $data_values_out,'status' => 1]);
        }
        return 'error';
//    return response()->json($chains->toJson());
    }
    
}
