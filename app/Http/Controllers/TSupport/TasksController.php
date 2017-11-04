<?php

namespace App\Http\Controllers\TSupport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\Category;
use App\User;
use App\Chain;
use App\Client;
use App\Task;
use App\ChainItems;
use App\ChainCategory;
use App\ChainUser;

class TasksController extends Controller {
    
//    protected $user;
//    protected $signedIn;
//
//    
//    public function __construct() {
//
//        $this->middleware(function ($request, $next) {
//
//            $this->user = $this->signedIn = Auth::user();
//
//            return $next($request);
//        });
//    }
    
    public function tsk_new($id) {

        $chain_categories = Category::all();
        $users = User::select('id', 'name')->get();
        $chains_opened = Chain::select('id', 'last_comment', 'creation_time', 'user_id')->where([['status', '=', 'OPENED'],['client_id', '=', $id],['deleted', '=', 0]])->get();

        $tsk_status = array(
            'NEW' => 'Новый',
            'PROCESSED' => 'В работе',
            'SOLVED' => 'Выполнен',
            'CLOSED' => 'Закрыт',
        );
        $tsk_priority = array(
            'LOW' => 'Низкий',
            'MEDIUM' => 'Средний',
            'HIGH' => 'Высокий',
            'CRITICAL' => 'Неотложный',
        );
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
        //$addresses = DB::select("select get_address_by_code(address_id) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        $addresses = DB::select("select address_aoid,get_address_by_aoid(address_aoid) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        
        //$chains = DB::table('chains_view')->paginate(100);
        //$chains = DB::table('chains')->get();
        //var_dump(count($chains_opened));
        return view('tsupport.task_new', compact('chain_categories','chains_opened','tsk_status','tsk_priority','users','client','addresses'));
    }

    public function tsk_store(Request $request,$id) {
        
        $category = $request->input('v_task_new_category');
        $responsible_id = $request->input('v_task_new_otvetstv');
        $status = $request->input('v_task_new_tsk_status');
        $priority = $request->input('v_task_new_tsk_priority');
        $start_d = $request->input('v_task_new_start_d');
        $start_t = $request->input('v_task_new_start_t');
        $deadline_time = strtotime($request->input('v_task_new_srok_d'));
        $progress = $request->input('v_task_new_tsk_progress');
        $departure = $request->input('v_task_new_tsk_dep');
        $message = $request->input('v_task_new_msg');
        $open_chain_id = $request->input('v_task_new_open_chains');
        
        if (!$status) $status = 'NEW';
        if (!$priority) $priority = 'MEDIUM';
        if (!$departure) $departure = '0';
        else $departure = '1';
        
        $start_d = $start_d.' '.$start_t;
        $start_date = strtotime($start_d);
        
        $user_id =  $request->user()->id;
        $contract_id = Client::find($id)->contract_id;
        $client_id = $id;
        $chain_id = 0;
        //var_dump($contract_id);
        $new_tsk = Task::Create(compact('priority','status','user_id','responsible_id','client_id','chain_id','message','start_time','deadline_time','progress','departure','contract_id'));
        
        $last_comment = $message;
        $status = 'OPENED';
        $opening_time = $start_date;
        $has_request = 0;
        $new_chain = '';
        if ($open_chain_id == '0') {
            $new_chain = Chain::Create(compact('client_id', 'user_id', 'last_comment', 'status', 'opening_time', 'contract_id', 'has_request'));
        } else {
            $new_chain = Chain::find($open_chain_id);
        }

        $chain_id = $new_chain->id;
        $task_id = $new_tsk->id;
        $new_chain_items = ChainItems::Create(compact('chain_id','user_id','task_id','message'));
        
        $new_tsk->chain_id = $chain_id;
        $new_tsk->save();
        
        $new_chain->last_item_id = $new_chain_items->id;
        $new_chain->last_comment = $last_comment;
        $new_chain->save();
        
        if ($category!=null) {
            $category_id = $category;
            $new_ch_cat = ChainCategory::updateOrCreate(compact('category_id','chain_id'), compact('category_id','chain_id'));
        }
        
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        $user_id = $responsible_id;
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        //var_dump('cAT:'. $category.'; otv:'.$otvetstv.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_d.'; srok:'.$srok_d.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$msg.'; op_ch:'.$open_chains);
        return redirect()->route('clients.view', ['id' => $id]);
    }
    
    public function tsk_edit($id) {

        $task= Task::find($id);
        $client_id = $task->client_id;
        $chain_categories = Category::all();
        $users = User::select('id', 'name')->get();
        $chains_opened = Chain::select('id', 'last_comment', 'creation_time', 'user_id')->where([['status', '=', 'OPENED'],['client_id', '=', $client_id],['deleted', '=', 0]])->get();

        $tsk_status = array(
            'NEW' => 'Новый',
            'PROCESSED' => 'В работе',
            'SOLVED' => 'Выполнен',
            'CLOSED' => 'Закрыт',
        );
        $tsk_priority = array(
            'LOW' => 'Низкий',
            'MEDIUM' => 'Средний',
            'HIGH' => 'Высокий',
            'CRITICAL' => 'Неотложный',
        );
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
        //$addresses = DB::select("select get_address_by_code(address_id) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        $addresses = DB::select("select address_aoid,get_address_by_aoid(address_aoid) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$client_id]);
        
        //$chains = DB::table('chains_view')->paginate(100);
        //$chains = DB::table('chains')->get();
        //var_dump(count($chains_opened));
        return view('tsupport.task_edit', compact('chain_categories','chains_opened','tsk_status','tsk_priority','users','client','addresses','task'));
    }
    
    public function tsk_update(Request $request,$id) {
        
        $task= Task::find($id);
        $client_id = $task->client_id;
        
        $responsible_id = $request->input('v_task_edit_otvetstv');
        $status = $request->input('v_task_edit_status');
        $priority = $request->input('v_task_edit_priority');
        $start_d = $request->input('v_task_edit_start_d');
        $start_t = $request->input('v_task_edit_start_t');
        $deadline_time = strtotime($request->input('v_task_edit_srok_d'));
        $progress = $request->input('v_task_edit_tsk_progress');
        $departure = $request->input('v_task_edit_tsk_dep');
        $message = $request->input('v_task_edit_msg');
        
        if (!$status) $status = 'NEW';
        if (!$priority) $priority = 'MEDIUM';
        if ($departure==null) $departure = '0';
        else $departure = '1';
        
        $start_d = $start_d.' '.$start_t;
        $start_date = strtotime($start_d);
        
        $user_id =  $request->user()->id;
        $contract_id = Client::find($client_id)->contract_id;
        
        //var_dump(' otv:'.$responsible_id.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_date.'; srok:'.$deadline_time.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$message);
        //exit;
        
        $task->responsible_id = $responsible_id;
        $task->status = $status;
        $task->priority = $priority;
        $task->start_time = $start_date;
        $task->deadline_time = $deadline_time;
        $task->progress = $progress;
        $task->departure = $departure;
        $task->message = $message;
        $task->save();
        
        //$new_tsk = Task::Create(compact('priority','status','user_id','responsible_id','client_id','chain_id','message','start_time','deadline_time','progress','departure','contract_id'));
        
        $last_comment = $message;
        
        $chain = Chain::find($task->chain_id);
        $chain->last_comment = $last_comment;
        $chain_items_id = ChainItems::select('id')->where([['task_id', '=', $task->id],['chain_id', '=', $task->chain_id]])->get();
        $chain->last_item_id = $chain_items_id[0]->id;
        $chain->save();
        
        $chain_items = ChainItems::find($chain_items_id[0]->id);
        $chain_items->message = $message;
        $chain_items->save();

        $chain_id = $task->chain_id;
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        $user_id = $responsible_id;
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        //var_dump('cAT:'. $category.'; otv:'.$otvetstv.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_d.'; srok:'.$srok_d.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$msg.'; op_ch:'.$open_chains);
        return redirect()->route('chains.view', ['id' => $chain_id]);
    }    
    
}

