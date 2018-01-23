<?php

namespace App\Http\Controllers\TSupport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\Category;
use App\User;
use App\Chain;
use App\Client;
use App\Call;
use App\ChainItems;
use App\ChainCategory;
use App\ChainUser;


class CallsController extends Controller
{
    public function call_new($id, $ch_id = null) {

        $chain_categories = Category::all();
        //$users = User::select('id', 'name')->get();
        $chains_opened = Chain::select('id', 'last_comment', 'creation_time', 'user_id')->where([['status', '=', 'OPENED'],['client_id', '=', $id],['deleted', '=', 0]])->get();

//        $req_sources  = array(
//            1 => 'Клиент',
//            2 => 'ЦДО',
//            3 => 'РЦИТ',
//            4 => 'Обзвон',
//        );
        
        $clients = DB::select("select
				CASE WHEN clients.surname = '' THEN  clients.name	else clients.surname || ' ' || clients.name  || ' ' || clients.patronymic END AS clt_name,
				clients_type.name as clt_type,
				clients.id,
				clients.problematic,
				clients.creation_time,
                                clients.active,
				contracts.title as contract_name,
				clients.mother,
				clients.father,
				array_agg(host(public.int2inet(ip_addresses.address)) ||
				CASE WHEN ip_addresses.netmask is not null THEN ' / '||host(public.int2inet(ip_addresses.netmask)) else '' END ||
				CASE WHEN ip_addresses.gateway is not null THEN ' / '||host(public.int2inet(ip_addresses.gateway)) else '' END ) as ip_addresses,
				clients.diagnose,
				clients.comment,
                                providers.name as prd_name ,
                                clients.numbers 
            from clients
            left Join providers on  clients.provider_id   =   providers.id 
            left Join contracts on  clients.contract_id   =   contracts.id 
            left Join ip_addresses on clients.id   =   ip_addresses.clients_id 
            left Join clients_type on  clients.clients_type_id   =   clients_type.id 
            where clients.id = ?
		group by clients.id , clients.active , clients.surname , clients.name , clients.patronymic ,
                     clients.creation_time, clients.sex , clients.problematic,                    
                     providers.name , clients.numbers , clients.comment,
			clients.mother, clients.diagnose, clients.father, clients_type.name,contracts.title
             ", [$id]);
    
        $client = $clients[0];
        $addresses = DB::select("select get_address_by_code(address_id) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        //$addresses = DB::select("select address_aoid,get_address_by_aoid(address_aoid) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        
        $add2exist_chain_id = '';
        if (strpos(url()->previous(), 'clients/view') || strpos(url()->previous(), 'lc/personal')) {
            //var_dump('Вызывается из clients/view - любой протокол');
            $add2exist_chain_id = '';
        }
        else {
            $chains_opened = null;
            $add2exist_chain_id = $ch_id;
            //var_dump($ch_id);
        }
        
        return view('tsupport.call_new', compact('chain_categories','chains_opened','client','addresses','add2exist_chain_id'));
    }

    public function call_store(Request $request,$id) {
        
        $category = $request->input('v_call_new_category');
        $call_status = $request->input('v_call_new_status');
        $interlocutor = $request->input('v_call_new_abon');
        $comment = $request->input('v_call_new_comment');
        $open_chain_id = $request->input('v_call_new_open_chains');
        $chain_id_exist = $request->input('v_call_new_exist_chain');
       
        $user_id =  $request->user()->id;
        $contract_id = Client::find($id)->contract_id;
        $client_id = $id;
        $chain_id = 0;
        if ($call_status == 'on') $status = 'SUCCESS';
        else $status = 'FAIL';
        
        //var_dump($today); exit;
        $new_call = Call::Create(compact('chain_id','user_id','client_id','comment','interlocutor','contract_id','status'));
        
        $last_comment = $comment;
        $status = 'OPENED';
        $today = strtotime(date("Y-m-d H:i"));
        $opening_time = $today;
        $has_request = 0;
        //$on_request = $new_req->id;
        $new_chain = '';
        $last_call_id = $new_call->id;
        
        if (($open_chain_id === '0' || $open_chain_id == null) && $chain_id_exist == null)
            $new_chain = Chain::Create(compact('client_id', 'user_id', 'last_comment', 'status', 'opening_time','last_call_id','has_request', 'contract_id'));
            //var_dump('new protocol ----  add to selected open chain:'.$open_chain_id.' ;  add to const one chain '.$chain_id_exist);
        else if ($open_chain_id != null || $chain_id_exist != null) {
            $ch_id = '';
            if ($open_chain_id != null) $ch_id = $open_chain_id;
            else if ($chain_id_exist != null) $ch_id = $chain_id_exist;
            
            $new_chain = Chain::find($ch_id);
            
            //var_dump('existing protocol --- add 2 selected open chain:'.$open_chain_id.' ;  add 2 const one chain '.$chain_id_exist);
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
        
        if ($category!=null) {
            $category_id = $category;
            $new_ch_cat = ChainCategory::updateOrCreate(compact('category_id','chain_id'), compact('category_id','chain_id'));
        }
        
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        //var_dump('cAT:'. $category.'; otv:'.$otvetstv.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_d.'; srok:'.$srok_d.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$msg.'; op_ch:'.$open_chains);
        if ($chain_id_exist == null) {
//            if (  ( $request->user()->hasRole('Учителя') && $request->user()->client_id==$client_id  ) || $request->user()->hasRole('Ученики')  )
//                return redirect()->route('lc.personal');
//            else 
                return redirect()->route('clients.view', ['id' => $id]);
        }
        else 
            return redirect()->route('chains.view', ['id' => $chain_id]);
    }
    
    public function call_edit($id) {

        $cal = Call::find($id);
        $client_id = $cal->client_id;
        //$chains_opened = Chain::select('id', 'last_comment', 'creation_time', 'user_id')->where([['status', '=', 'OPENED'],['client_id', '=', $client_id],['deleted', '=', 0]])->get();

//        $req_sources  = array(
//            1 => 'Клиент',
//            2 => 'ЦДО',
//            3 => 'РЦИТ',
//            4 => 'Обзвон',
//        );
        
        $clients = DB::select("select
				CASE WHEN clients.surname = '' THEN  clients.name	else clients.surname || ' ' || clients.name  || ' ' || clients.patronymic END AS clt_name,
				clients_type.name as clt_type,
				clients.id,
				clients.problematic,
				clients.creation_time,
                                clients.active,
				contracts.title as contract_name,
				clients.mother,
				clients.father,
				array_agg(host(public.int2inet(ip_addresses.address)) ||
				CASE WHEN ip_addresses.netmask is not null THEN ' / '||host(public.int2inet(ip_addresses.netmask)) else '' END ||
				CASE WHEN ip_addresses.gateway is not null THEN ' / '||host(public.int2inet(ip_addresses.gateway)) else '' END ) as ip_addresses,
				clients.diagnose,
				clients.comment,
                                providers.name as prd_name ,
                                clients.numbers 
            from clients
            left Join providers on  clients.provider_id   =   providers.id 
            left Join contracts on  clients.contract_id   =   contracts.id 
            left Join ip_addresses on clients.id   =   ip_addresses.clients_id 
            left Join clients_type on  clients.clients_type_id   =   clients_type.id 
            where clients.id = ?
		group by clients.id , clients.active , clients.surname , clients.name , clients.patronymic ,
                     clients.creation_time, clients.sex , clients.problematic,                    
                     providers.name , clients.numbers , clients.comment,
			clients.mother, clients.diagnose, clients.father, clients_type.name,contracts.title
             ", [$client_id]);
    
        $client = $clients[0];
        $addresses = DB::select("select get_address_by_code(address_id) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        //$addresses = DB::select("select address_aoid,get_address_by_aoid(address_aoid) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$client_id]);
        
        //$chains = DB::table('chains_view')->paginate(100);
        //$chains = DB::table('chains')->get();
        //var_dump(count($chains_opened));
        return view('tsupport.call_edit', compact('client','addresses','cal'));
    }
    
    public function call_update(Request $request,$id) {
        
        $cal = Call::find($id);
        //$client_id = $req->client_id;
        
        $call_status = $request->input('v_call_edit_status');
        $comment = $request->input('v_call_edit_comment');
        $interlocutor = $request->input('v_call_edit_abon');
        
//        $start_d = $start_d.' '.$start_t;
//        $start_date = strtotime($start_d);
        
        $user_id =  $request->user()->id;
        //$contract_id = Client::find($client_id)->contract_id;
        
        //var_dump(' otv:'.$responsible_id.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_date.'; srok:'.$deadline_time.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$message);
        //exit;

        if ($call_status == 'on') $status = 'SUCCESS';
        else $status = 'FAIL';
        
        //$req->contract_id = $contract_id;
        //$cal->provider_id = $provider_id;
        $cal->comment = $comment;
        $cal->status = $status;
        $cal->interlocutor = $interlocutor;
        $cal->save();
        
        $last_call_id = $cal->id;
        
        //$new_tsk = Task::Create(compact('priority','status','user_id','responsible_id','client_id','chain_id','message','start_time','deadline_time','progress','departure','contract_id'));
        
        $last_comment = $comment;
        
        $chain = Chain::find($cal->chain_id);
        $chain->last_comment = $last_comment;
        $chain_items_id = ChainItems::select('id')->where([['call_id', '=', $cal->id],['chain_id', '=', $cal->chain_id]])->get();
        $chain->last_item_id = $chain_items_id[0]->id;
        $chain->last_call_id = $last_call_id;
        $chain->save();
        
        $chain_items = ChainItems::find($chain_items_id[0]->id);
        $chain_items->message = $comment;
        $chain_items->save();

        $chain_id = $cal->chain_id;
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        //var_dump('cAT:'. $category.'; otv:'.$otvetstv.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_d.'; srok:'.$srok_d.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$msg.'; op_ch:'.$open_chains);
        return redirect()->route('chains.view', ['id' => $chain_id]);
    }    

    public function index(Request $request) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') && !$request->user()->hasRole('Учителя') ) { 
            return redirect('forbidden');
        }  
        


//        $ch_status = array(
//            'OPENED' => 'Открыт',
//            'CLOSED' => 'Закрыт',
//        );        
//
//  
        //$clt_id = '';
        return view('tsupport.calls');
    }

    public function Get_json_calls() {

    //$calls = Call::all();//->paginate(100); 
    
        $calls = DB::select("select
				calls.id,
				calls.creation_time,
				CASE WHEN clients.surname = '' THEN  clients.name	else clients.surname || ' ' || clients.name  || ' ' || clients.patronymic END AS clt_name,
                                calls.interlocutor,
                                users.name,
                                calls.comment,
                                calls.status,
                                calls.client_id,
                                calls.chain_id
            from calls 
            left Join users on users.id   =   calls.user_id 
            left Join clients on  clients.id = calls.client_id 
		group by calls.id,calls.creation_time,clients.surname,clients.name,clients.patronymic,
                     calls.interlocutor,users.name,  calls.comment,  calls.status, calls.chain_id              
             ");
    
        $call_status = array(
            'SUCCESS' => 'Успешный',
            'ANSWERED' => 'Успешный',
            'NO ANSWER' => 'Нет ответа',
            'FAILED' => 'Недоступен',
            'FAIL' => 'Недоступен',
            'UNSET' => 'Неустановлен',
        );        
  
        //$users = User::select('id','name')->get();
        //$clients = Client::select('id','surname','name','patronymic')->get();

        $data = [];
        foreach ($calls as $row=>$call) {
            
            array_push($data, 
              array(
                date('d.m.y H:i',$call->creation_time),
                '<a href="'.route('clients.view', ['id' => $call->client_id]).'">'.$call->clt_name.'</a>',
                $call->interlocutor,
                $call->name,
                $call->comment,
                $call_status[$call->status],
                '<a href="'.route('chains.view', ['id' => $call->chain_id]).'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>',
                '<a href="'.route('calls.edit', ['id' => $call->id]).'"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>',
              )
            );
        }
        
        return ['data'=>$data];
        
//    return response()->json($chains->toJson());
    }    
    
}
