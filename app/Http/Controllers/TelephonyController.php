<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Call;
//use lib\GlobalVars;

class TelephonyController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     //private $gcdr;

    public function __construct() {
        $this->middleware('auth');
        //$this->gcdr = new GlobalVars();
        //self::$gcdr = '';
        session(['gcdr' => '']);
    }

    /**
     * Show the index of the section.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('telephony.index_tel');
    }

    public function stat_index() {
        //$cdr = DB::connection('mysql')->select("SELECT calldate,src,dst,duration,disposition FROM cdr order by calldate desc limit 10");
        //var_dump($cdr);exit;
        $call_status = array(
            'ANSWERED' => 'Отвеченый',
            'BUSY' => 'Занят',
            'NO ANSWER' => 'Нет ответа',
            'FAILED' => 'Неудачный',
        );

        $period = array(
            'day' => 'День',
            'week' => 'Неделя',
            'month' => 'Месяц',
            'year' => 'Год',
        );

        $type_report = array(
            'common' => 'Общее количество вызовов',
            'no answer' => 'Количество неотвеченных вызовов',
            'answered' => 'Количество обработанных вызовов',
        );

        $report_detail_level = array(
            15 => '15 минут',
            30 => '30 минут',
            60 => '1 час',
        );

        return view('telephony.index_stat', compact('call_status', 'period', 'type_report', 'report_detail_level'));
    }

    public function common_call_list_index() {
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

        $users = User::select('id', 'name')->get();
        $calls_comment = Call::select('comment', 'cdr_uniqueid')->where('cdr_uniqueid', '<>', '')->get();

        $data = [];
        foreach ($cdr as $row => $item) {

//            if (!$request->user()->hasRole('Учителя')) 
//                $clt_edit_tag = '<a href="'.route('clients.edit', ['id' => $client->id]).'"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
            $usr_name = $users->find($item->userfield);
            if ($usr_name)
                $usr_name = $usr_name->name;

            $comment = $calls_comment->where('cdr_uniqueid', $item->uniqueid)->first();

            if ($comment)
                $comment = $comment->comment;
            else
                $comment = '';

            $path2wave_file = '';
            if ($item->recordingfile != '')
                $path2wave_file = '/calls_recs/' . $this->get_path_wav_file($item->calldate) . '/' . $item->recordingfile;
            //$path2wave_file = '/mnt/asterisk/' . $this->get_path_wav_file($item->calldate).'/'.$item->recordingfile;
            //$call_play_tag = '<a href="#" data-toggle="tooltip" title="Прослушать запись разговора"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span></a>';
//            "<a href=\"JavaScript:call_play_rec('".$path2wave_file."','".$clt_name_clear."','".$num_arr[0]."');\">".$num_arr[0]."</a>";
            $call_play_tag = "<a href=\"JavaScript:call_play_rec('" . $path2wave_file . "');\" data-toggle=\"tooltip\" title=\"Прослушать запись разговора\"><span class=\"glyphicon glyphicon-play\" aria-hidden=\"true\"></span></a>";
            $call_stop_tag = "<a href=\"JavaScript:stop_play_rec();\" data-toggle=\"tooltip\" title=\"Остановить воспроизведение\"><span class=\"glyphicon glyphicon-stop\" aria-hidden=\"true\"></span></a>";

            array_push($data, array(
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
        return ['data' => $data];

//    return response()->json($chains->toJson());
    }

//    public function rep_index()
//    {
//        //$cdr = DB::connection('mysql')->select("SELECT calldate,src,dst,duration,disposition FROM cdr order by calldate desc limit 10");
//        //var_dump($cdr);exit;
//        return view('telephony.index_rep');
//    }    

    public function call_list_index() {
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
            $sip_login = $request->input('sip_login');

            $start_date = '';
            $end_date = '';

            if ($period == 'day') {
                $start_date = $date . ' 00:00:00';
                $end_date = $date . ' 23:59:59';
            } else if ($period == 'week') {
                //$date1 = new DateTime($date);
                //$date1->add(new DateInterval('P7D'));            
                $date1 = date_create($date);
                date_add($date1, date_interval_create_from_date_string('7 days'));
                $start_date = $date . ' 00:00:00';
                //$end_date = $date1->format('Y-m-d') . ' 23:59:59';
                $end_date = date_format($date1, 'Y-m-d') . ' 23:59:59';
            } else if ($period == 'month') {
                $start_date = $date . '-01 00:00:00';
                $end_date = $date . '-31 23:59:59';
            } else {
                $start_date = $date . '-01-01 00:00:00';
                $end_date = $date . '-12-31 23:59:59';
            }

            $login_filter_part = '';
            if ($sip_login !== 'nologin') $login_filter_part = " and cnum = '".$sip_login."' ";
            
            $cdr = '';
            
            if ($rep == 'common') {
            
                $cdr = DB::connection('mysql')
                    ->select(""
                    . "select DATE_FORMAT(concat( date(calldate) , ' ', sec_to_time(time_to_sec(calldate)- time_to_sec(calldate)%(" . $detalization_minute . "*60) + (" . $detalization_minute . "*60))), '%d-%m %H:%i') as created_dt_new,"
                    . "COUNT(*) AS calls_count,ROUND(avg(duration), 1) as avg_duration,min(duration) as min_duration,max(duration) as max_duration, ROUND(avg(duration-billsec)) as avg_wait_duration from cdr "
                    . "where calldate between '" . $start_date . "' and '" . $end_date . "'".$login_filter_part." group by created_dt_new order by calldate");
            }
            else if ($rep == 'no answer') {
            
                $cdr = DB::connection('mysql')
                    ->select(""
                    . "select DATE_FORMAT(concat( date(calldate) , ' ', sec_to_time(time_to_sec(calldate)- time_to_sec(calldate)%(" . $detalization_minute . "*60) + (" . $detalization_minute . "*60))), '%d-%m %H:%i') as created_dt_new,"
                    . "COUNT(*) AS calls_count, 0 as avg_duration, 0 as min_duration, 0 as max_duration, ROUND(avg(duration-billsec)) as avg_wait_duration from cdr "
                    . "where calldate between '" . $start_date . "' and '" . $end_date . "' and disposition <> 'ANSWERED'".$login_filter_part." group by created_dt_new order by calldate");
            }
            else if ($rep == 'answered') {
            
                $cdr = DB::connection('mysql')
                    ->select(""
                    . "select DATE_FORMAT(concat( date(calldate) , ' ', sec_to_time(time_to_sec(calldate)- time_to_sec(calldate)%(" . $detalization_minute . "*60) + (" . $detalization_minute . "*60))), '%d-%m %H:%i') as created_dt_new,"
                    . "COUNT(*) AS calls_count,ROUND(avg(duration), 1) as avg_duration,min(duration) as min_duration,max(duration) as max_duration, ROUND(avg(duration-billsec)) as avg_wait_duration from cdr "
                    . "where calldate between '" . $start_date . "' and '" . $end_date . "' and disposition = 'ANSWERED'".$login_filter_part." group by created_dt_new order by calldate");
            }
            
            //return Response::json(array('ddt'=>$end_date, 'status' => 1));

//        $cdr = DB::connection('mysql')
//                ->select(""
//                    . "select concat( date(calldate) , ' ', sec_to_time(time_to_sec(calldate)- time_to_sec(calldate)%(15*60) + (15*60))) as created_dt_new,"
//                    . "COUNT(*) AS calls_count,ROUND(avg(duration), 1) as avg_duration,min(duration),max(duration) from cdr "
//                    . "where calldate between '2017-01-01 00:00:00' AND '2017-11-31 23:59:59' group by created_dt_new");

            //$this->gcdr->SetGCDR($cdr);
            //$this->gcdr = $cdr;
            session(['gcdr' => $cdr]);

            $data = [];
            $data_dates = [];
            $data_values_count = [];
            $data_values_duration = [];
            $data_values_wait_duration = [];
            $max_calls_count = 0;
            foreach ($cdr as $row => $item) {

                if ($item->calls_count > $max_calls_count)
                    $max_calls_count = $item->calls_count;

                array_push($data_dates, $item->created_dt_new
                );

                array_push($data_values_count, $item->calls_count
                );

                array_push($data_values_duration, $item->avg_duration
                );

                array_push($data_values_wait_duration, $item->avg_wait_duration
                );
            }
            //return ['data'=>$data];
            return Response::json(['data_dates' => $data_dates, 'data_values_count' => $data_values_count, 'data_values_duration' => $data_values_duration, 'data_values_wait_duration' => $data_values_wait_duration, 'max_calls_count' => $max_calls_count, 'cdr' => $cdr, 'status' => 1]);
        }
        return 'error';
//    return response()->json($chains->toJson());
    }

    public function Get_ajax_reports_table(Request $request) {

        //$cdr = $this->gcdr;
        $cdr = session('gcdr');
        
        if ($cdr !== '') {

            $data = [];
            $max_calls_count = 0;
            $max_dur_count = 0;
            $max_wait_count = 0;

            foreach ($cdr as $row => $item) {
                if ($item->calls_count > $max_calls_count)
                    $max_calls_count = $item->calls_count;
                if ($item->avg_duration > $max_dur_count)
                    $max_dur_count = $item->avg_duration;
                if ($item->avg_wait_duration > $max_wait_count)
                    $max_wait_count = $item->avg_wait_duration;
            }
            
            if ($max_calls_count === 0) $max_calls_count = 1;
            if ($max_dur_count === 0) $max_dur_count = 1;
            if ($max_wait_count === 0) $max_wait_count = 1;

            foreach ($cdr as $row => $item) {
                
                $calls_bar = '<div class="progress" style="height:8px; margin: -5px 0px -8px 0px"><div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="';
                $calls_bar = $calls_bar.round($item->calls_count * 100 / $max_calls_count);
                $calls_bar = $calls_bar.'" aria-valuemin="0" aria-valuemax="100" style="width: ';
                $calls_bar = $calls_bar.round($item->calls_count * 100 / $max_calls_count);
                $calls_bar = $calls_bar.'%"><span class="sr-only">';
                $calls_bar = $calls_bar.round($item->calls_count * 100 / $max_calls_count);
                $calls_bar = $calls_bar.'% Complete</span></div></div>';

                $avg_dur_bar = '<div class="progress" style="height:8px; margin: -5px 0px -8px 0px"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="';
                $avg_dur_bar = $avg_dur_bar.$item->avg_duration;
                $avg_dur_bar = $avg_dur_bar.'" aria-valuemin="0" aria-valuemax="100" style="width: ';
                $avg_dur_bar = $avg_dur_bar.round($item->avg_duration * 100 / $max_dur_count);
                $avg_dur_bar = $avg_dur_bar.'%"><span class="sr-only">';
                $avg_dur_bar = $avg_dur_bar.$item->avg_duration;
                $avg_dur_bar = $avg_dur_bar.'% Complete</span></div></div>';

                $avg_wait_bar = '<div class="progress" style="height:8px; margin: -5px 0px -8px 0px"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="';
                $avg_wait_bar = $avg_wait_bar.$item->avg_wait_duration;
                $avg_wait_bar = $avg_wait_bar.'" aria-valuemin="0" aria-valuemax="100" style="width: ';
                $avg_wait_bar = $avg_wait_bar.round($item->avg_wait_duration * 100 / $max_wait_count);
                $avg_wait_bar = $avg_wait_bar.'%"><span class="sr-only">';
                $avg_wait_bar = $avg_wait_bar.$item->avg_wait_duration;
                $avg_wait_bar = $avg_wait_bar.'% Complete</span></div></div>';
                
                array_push($data, array(
                    $item->created_dt_new,
                    $item->calls_count,
                    round($item->calls_count * 100 / $max_calls_count).'%',
                    $calls_bar,
                    $item->avg_duration,
                    $item->min_duration,
                    $item->max_duration,
                    $avg_dur_bar,
                    $item->avg_wait_duration,
                    $avg_wait_bar,
//                '<div><a href="'.route('clients.view', ['id' => $client->id]).'">'.$client->clt_name.'</a></div>'.$groups,
                        //'<div><a href="'.route('clients.view', ['id' => $client->id]).'">'.$client->clt_name.'</a></div>',
                ));
            }
            return ['data' => $data];
        }
        else return ['data' => []];
//    return response()->json($chains->toJson());
    }

}
