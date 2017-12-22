<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('telephony.index_stat');
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
}
