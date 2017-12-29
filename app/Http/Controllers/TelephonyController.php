<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Call;

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
    public function stat_index()
    {
        //$cdr = DB::connection('mysql')->select("SELECT calldate,src,dst,duration,disposition FROM cdr order by calldate desc limit 10");
        //var_dump($cdr);exit;
        $call_status = array(
            'ANSWERED' => 'Отвеченый',
            'BUSY' => 'Занят',
            'NO ANSWER' => 'Нет ответа',
            'FAILED' => 'Неудачный',
        );
        
        $period  = array(
            'day' => 'День',
            'week' => 'Неделя',
            'month' => 'Месяц',
            'year' => 'Год',
        );        

        $type_report  = array(
            'common' => 'Общее количество вызовов',
            'no answer' => 'Количество неотвеченных вызовов',
            'answered' => 'Количество обработанных вызовов',
        );
        
        $report_detail_level  = array(
            15 => '15 минут',
            30 => '30 минут',
            60 => '1 час',
        );        

        return view('telephony.index_stat',compact('call_status','period','type_report','report_detail_level'));
    }
    
    public function common_call_list_index()
    {
        //$cdr = DB::connection('mysql')->select("SELECT calldate,src,dst,duration,disposition FROM cdr order by calldate desc limit 10");
        //var_dump($cdr);exit;
        return view('telephony.index_common_call_list');
    }

    
    private function get_path_wav_file($date) {
        $path = substr($date, 0, 10);
        $path = str_replace('-', '/', $path);
        return $path;
    }
    
    public function Get_json_calls_list(Request $request) {

        $cdr = DB::connection('mysql')
                ->select("SELECT uniqueid,calldate,src,dst,duration,disposition,userfield,recordingfile FROM cdr order by calldate desc");
        
        $users = User::select('id','name')->get();
        $calls_comment = Call::select('comment','cdr_uniqueid')->where('cdr_uniqueid', '<>', '')->get();
        
        $data = [];
        foreach ($cdr as $row=>$item){
            
//            if (!$request->user()->hasRole('Учителя')) 
//                $clt_edit_tag = '<a href="'.route('clients.edit', ['id' => $client->id]).'"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
            $usr_name = $users->find($item->userfield);
            if ($usr_name) $usr_name = $usr_name->name;
            
            $comment = $calls_comment->where('cdr_uniqueid', $item->uniqueid)->first();

            if ($comment) $comment = $comment->comment;
            else $comment = '';
            
            $path2wave_file = '';
            if ($item->recordingfile != '')
                $path2wave_file = '/calls_recs/' . $this->get_path_wav_file($item->calldate).'/'.$item->recordingfile;
                //$path2wave_file = '/mnt/asterisk/' . $this->get_path_wav_file($item->calldate).'/'.$item->recordingfile;

            //$call_play_tag = '<a href="#" data-toggle="tooltip" title="Прослушать запись разговора"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span></a>';
//            "<a href=\"JavaScript:call_play_rec('".$path2wave_file."','".$clt_name_clear."','".$num_arr[0]."');\">".$num_arr[0]."</a>";
            $call_play_tag = "<a href=\"JavaScript:call_play_rec('".$path2wave_file."');\" data-toggle=\"tooltip\" title=\"Прослушать запись разговора\"><span class=\"glyphicon glyphicon-play\" aria-hidden=\"true\"></span></a>";
            $call_stop_tag = "<a href=\"JavaScript:stop_play_rec();\" data-toggle=\"tooltip\" title=\"Остановить воспроизведение\"><span class=\"glyphicon glyphicon-stop\" aria-hidden=\"true\"></span></a>";
            
            array_push($data, 
              array(
                $item->calldate,
                $item->src,
                $item->dst,
                $item->duration,
                $item->disposition,
                $call_play_tag,
                $call_stop_tag,
                $usr_name,
                $comment,
//                '<div><a href="'.route('clients.view', ['id' => $client->id]).'">'.$client->clt_name.'</a></div>'.$groups,
                //'<div><a href="'.route('clients.view', ['id' => $client->id]).'">'.$client->clt_name.'</a></div>',
              )
            );
        }
        return ['data'=>$data];
        
//    return response()->json($chains->toJson());
    }    
    
//    public function rep_index()
//    {
//        //$cdr = DB::connection('mysql')->select("SELECT calldate,src,dst,duration,disposition FROM cdr order by calldate desc limit 10");
//        //var_dump($cdr);exit;
//        return view('telephony.index_rep');
//    }    

    public function call_list_index()
    {
        //$cdr = DB::connection('mysql')->select("SELECT calldate,src,dst,duration,disposition FROM cdr order by calldate desc limit 10");
        //var_dump($cdr);exit;
        return view('telephony.index_call_list');
    }    
    
    public function Get_ajax_reports(Request $request) {
    if ($request->ajax()) {
        
        $period = $request->input('period');
        $date = $request->input('date');
        $rep = $request->input('rep');
        $detalization_minute = $request->input('det');
        
        $start_date = ''; $end_date = '';
        
        if ($period == 'day' ) {
            $start_date = $date . ' 00:00:00';
            $end_date = $date . ' 23:59:59';
        }
        else if ($period == 'week' ) {
            //$date1 = new DateTime($date);
            //$date1->add(new DateInterval('P7D'));            
            $date1 = date_create($date);
            date_add($date1, date_interval_create_from_date_string('7 days'));
            $start_date = $date . ' 00:00:00';
            //$end_date = $date1->format('Y-m-d') . ' 23:59:59';
            $end_date = date_format($date1, 'Y-m-d') . ' 23:59:59';
        }
        else if ($period == 'month' ) {
            $start_date = $date . '-01 00:00:00';
            $end_date = $date . '-31 23:59:59';
        }
        else {
            $start_date = $date . '-01-01 00:00:00';
            $end_date = $date . '-12-31 23:59:59';
        }
        
//        if ($rep == 'common') {
//            
//        }
        //return Response::json(array('ddt'=>$end_date, 'status' => 1));
        
        
        $cdr = DB::connection('mysql')
                ->select(""
                    . "select concat( date(calldate) , ' ', sec_to_time(time_to_sec(calldate)- time_to_sec(calldate)%(".$detalization_minute."*60) + (".$detalization_minute."*60))) as created_dt_new,"
                    . "COUNT(*) AS calls_count,ROUND(avg(duration), 1) as avg_duration,min(duration),max(duration) from cdr "
                    . "where calldate between '".$start_date."' and '".$end_date."' group by created_dt_new");
        

//        $cdr = DB::connection('mysql')
//                ->select(""
//                    . "select concat( date(calldate) , ' ', sec_to_time(time_to_sec(calldate)- time_to_sec(calldate)%(15*60) + (15*60))) as created_dt_new,"
//                    . "COUNT(*) AS calls_count,ROUND(avg(duration), 1) as avg_duration,min(duration),max(duration) from cdr "
//                    . "where calldate between '2017-01-01 00:00:00' AND '2017-11-31 23:59:59' group by created_dt_new");

        
        $data_dates = []; $data_values_count = []; $data_values_duration = [];
        foreach ($cdr as $row=>$item){
            
            array_push($data_dates, 
                $item->created_dt_new
            );
            
            array_push($data_values_count, 
                $item->calls_count
            );

            array_push($data_values_duration, 
                $item->avg_duration
            );
            
        }
        //return ['data'=>$data];
        return Response::json(['data_dates' => $data_dates, 'data_values_count' => $data_values_count, 'data_values_duration' => $data_values_duration, 'status' => 1]);
    }
    return 'error';
//    return response()->json($chains->toJson());
    }    
    
    
}
