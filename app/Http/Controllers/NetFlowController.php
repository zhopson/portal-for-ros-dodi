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
            $only_active = $request->input('only_active');
            
            //var_dump($only_active); exit;
            
            //if ($only_active == 'on')
            

            $start_date = $date1 . ' 00:00:00';
            $end_date = $date2 . ' 23:59:59';
            
            $traf = '';
            //$scale = 'КБ';
            
            if ($ip != 0)  //YYYY-MM-DD HH24:MI // встречается один ip несколько клиентов, поэтому добавилось distinct
                $traf = DB::connection('pgsql_netflow')->select("select distinct bytes_in/1024 as bytes_in,bytes_out/1024 as bytes_out,8*bytes_in/1024/900 as bytes_in_sp,8*bytes_out/1024/900 as bytes_out_sp,to_char(period,'DD-MM HH24:MI') as period from public.view_user_stat_graph where ip_addr='".$ip."' and period>'".$start_date."' and period<'".$end_date."'");
            else if ($id != 0) 
                $traf = DB::connection('pgsql_netflow')->select("select bytes_in/1024 as bytes_in,bytes_out/1024 as bytes_out,8*bytes_in/1024/900 as bytes_in_sp,8*bytes_out/1024/900 as bytes_out_sp,to_char(period,'DD-MM HH24:MI') as period from public.view_user_stat_graph where user_id=".$id." and period>'".$start_date."' and period<'".$end_date."'");
            else { //$scale = 'МБ';//traf for all clts 
                if ($only_active == 'on') {
                    $inactive = DB::select("select replace(replace(cast(array_agg(id) as varchar),'{','('),'}',')') from clients where active=0")[0]->replace;
                    $traf = DB::connection('pgsql_netflow')->select("select sum(bytes_in/1024) as bytes_in,sum(bytes_out/1024) as bytes_out,sum(8*bytes_in/1024/900) as bytes_in_sp,sum(8*bytes_out/1024/900) as bytes_out_sp,to_char(period,'DD-MM HH24:MI') as period from public.view_user_stat_graph where period>'".$start_date."' and period<'".$end_date."' and user_id not in ".$inactive." group by period");
                }
                else 
                    $traf = DB::connection('pgsql_netflow')->select("select sum(bytes_in/1024) as bytes_in,sum(bytes_out/1024) as bytes_out,sum(8*bytes_in/1024/900) as bytes_in_sp,sum(8*bytes_out/1024/900) as bytes_out_sp,to_char(period,'DD-MM HH24:MI') as period from public.view_user_stat_graph where period>'".$start_date."' and period<'".$end_date."' group by period");
            }
            //return Response::json(array('ddt'=>$end_date, 'status' => 1));

            $data_dates = [];
            $data_values_in = [];
            $data_values_out = [];
            $data_values_in_sp = [];
            $data_values_out_sp = [];
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
                
                array_push($data_values_in_sp, $item->bytes_in_sp
                );

                array_push($data_values_out_sp, $item->bytes_out_sp
                );
                
            }
            
            
            $a_in_sp = array_filter($data_values_in_sp, function($x) { return $x !== '0'; });
            $a_out_sp = array_filter($data_values_out_sp, function($x) { return $x !== '0'; });
            //$average = array_sum($a) / count($a);
            
//             if ($max_in > 1048576 || $max_out > 1048576) $scale = 'МБ';
//             else $scale = 'КБ';
            //return ['data'=>$data];
            return Response::json([
                    'data_dates' => $data_dates,
                    'data_values_in' => $data_values_in,
                    'data_values_out' => $data_values_out,
                    'data_values_in_sp' => $data_values_in_sp,
                    'data_values_out_sp' => $data_values_out_sp,
                    'sum_in' => round(array_sum($data_values_in) / 1024, 2),
                    'sum_out' => round(array_sum($data_values_out) / 1024, 2),
                    'max_sp_in' => round(max($data_values_in_sp), 2),
                    'avg_sp_in' => round(array_sum($a_in_sp) / count($a_in_sp), 2),
                    'max_sp_out' => round(max($data_values_out_sp), 2),
                    'avg_sp_out' => round(array_sum($a_out_sp) / count($a_out_sp), 2),
                    'status' => 1
                   ]);
        }
        return 'error';
//    return response()->json($chains->toJson());
    }
    
    public function cmn_traf_gr() {

        return view('tsupport.cmn_traf_gr');
    }
    
    
}
