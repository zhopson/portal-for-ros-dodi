<?php

namespace App\Http\Controllers\TSupport;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

//use App\Chain;
use Request;
use Response;

use App\Chain;
use App\Client;
use App\Call;
use App\ChainItems;
use App\ChainCategory;
use App\ChainUser;


class AjaxController extends Controller
{
    public function Get_json_chains_opened() {
        if (Request::ajax()) {
            $client_id = Request::input('client_id');
            
            //$chains_opened = Chain::select('id', 'last_comment', 'creation_time', 'user_id')->where([['status', '=', 'OPENED'],['client_id', '=', $client_id],['deleted', '=', 0]])->get();

        $chains_opened = DB::select("select avtor, id, create_dt, last_comment
                    from open_chains_view
                    where (client_id   =  ?) 
                ", [$client_id]);            
            
            //return Response::json(array('atr' => 'Hello', 'status' => 1));
            return Response::json(['chains_opened' => $chains_opened, 'status' => 1]);
            
        }
        return 'error';
    }
   
    public function do_create_chain_by_call() {
        
    if (Request::ajax()){

        $id = Request::input('client_id');
        $category = Request::input('category');
        $status = 'UNSET';
        $interlocutor = Request::input('interlocutor');
        $comment = Request::input('comment');
        $open_chain_id = Request::input('open_chain_id');
        $chain_id_exist = Request::input('chain_id_exist');

        //return Response::json(array('res' => $user_id, 'status' => 1));        
        
        $user_id =  Request::user()->id;
        $contract_id = Client::find($id)->contract_id;
        $client_id = $id;
        $chain_id = 0;
        
        $new_call = Call::Create(compact('chain_id','user_id','client_id','comment','interlocutor','contract_id','status'));
        
        $last_comment = $comment;
        $status = 'OPENED';
        $today = strtotime(date("Y-m-d H:i"));
        $opening_time = $today;
        $has_request = 0;
        $new_chain = '';
        $last_call_id = $new_call->id;
        
        if (($open_chain_id === '0' || $open_chain_id == null) && $chain_id_exist === 'null')
            //return Response::json(array('res' => 'new protocol ----  add to selected open chain:'.$open_chain_id.' ;  add to const one chain '.$chain_id_exist, 'status' => 1));
            $new_chain = Chain::Create(compact('client_id', 'user_id', 'last_comment', 'status', 'opening_time','last_call_id','has_request', 'contract_id'));
        else if ($open_chain_id != null || $chain_id_exist !== 'null') {
            $ch_id = '';
            if ($open_chain_id != null) $ch_id = $open_chain_id;
            else if ($chain_id_exist != null) $ch_id = $chain_id_exist;
            
            $new_chain = Chain::find($ch_id);
            //return Response::json(array('res' => 'existing protocol --- add 2 selected open chain:'.$open_chain_id.' ;  add 2 const one chain '.$chain_id_exist, 'status' => 1));
        }
            
        $chain_id = $new_chain->id;
        $call_id = $new_call->id;
        $message = $comment;
        
        $new_chain_items = ChainItems::Create(compact('chain_id','user_id','call_id','message'));
        
        $new_call->chain_id = $chain_id;
        $new_call->save();
        
        $new_chain->last_item_id = $new_chain_items->id;
        $new_chain->last_comment = $last_comment;
        $new_chain->last_call_id = $last_call_id;
        $new_chain->save();
        
        if ($category!='') {
            $category_id = $category;
            $new_ch_cat = ChainCategory::updateOrCreate(compact('category_id','chain_id'), compact('category_id','chain_id'));
        }
        
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        //var_dump('cAT:'. $category.'; otv:'.$otvetstv.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_d.'; srok:'.$srok_d.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$msg.'; op_ch:'.$open_chains);

        return Response::json(array('new_call_id' => $new_call->id, 'status' => 1));
    }
        return 'error';        
    }    

    public function do_update_call_by_tel() {
        
    if (Request::ajax()){

        $call_id = Request::input('call_id'); 
        $status = 'UNSET'; 
        $interlocutor = Request::input('interlocutor');

        $cdr = DB::connection('mysql')->select("SELECT uniqueid,disposition FROM cdr where src = '14112408081' and dst = ? and userfield = '' order by calldate desc limit 1", [$interlocutor]);
        //return Response::json(array('res' => $new_status[0]->disposition, 'status' => 1));        
        
        //select status from cdr where dest = $interlocutor
        //return Response::json(array('res' => $user_id, 'status' => 1));        
        
        $call = Call::find($call_id);
        
        //$today = strtotime(date("Y-m-d H:i"));
        
        $call->status = $cdr[0]->disposition;
        $call->cdr_uniqueid = $cdr[0]->uniqueid;
        $call->save();
        
        $user_id =  Request::user()->id;
        
        $affected = DB::connection('mysql')->update('update cdr set userfield = ? where uniqueid = ?', [$user_id,$cdr[0]->uniqueid]);

        return Response::json(array('call_id' => $call_id, 'status' => 1));
    }
        return 'error';        
    }      
   
    public function do_update_cdr_user() {
        
    if (Request::ajax()){

        $interlocutor = Request::input('interlocutor');

        $cdr = DB::connection('mysql')->select("SELECT uniqueid FROM cdr where src = '14112408081' and dst = ? and userfield = '' order by calldate desc limit 1", [$interlocutor]);
        //return Response::json(array('res' => $new_status[0]->disposition, 'status' => 1));        
        
        //return Response::json(array('res' => $user_id, 'status' => 1));        
        
        $user_id =  Request::user()->id;
        
        $affected = DB::connection('mysql')->update('update cdr set userfield = ? where uniqueid = ?', [$user_id,$cdr[0]->uniqueid]);

        return Response::json(array('user_id' => $user_id, 'status' => 1));
    }
        return 'error';        
    } 
    
}
