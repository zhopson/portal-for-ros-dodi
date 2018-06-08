<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Client;
use App\User;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function personal_view(Request $request){
        
        $id = $request->user()->client_id;
        
        $client = '';
        $addresses = '';
        $chains_opened = '';
        $chains_closed = '';
        $tasks = '';
                
        if ($id !== null) {
            $client = DB::select("select
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
										clients.mother, clients.diagnose, clients.father, clients_type.name,
											contracts.title
             ", [$id]);
    

            $addresses = DB::select("select get_address_by_code(address_id) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);

            $chains_opened = DB::select("select  users.name as avtor ,
                     chains.id ,
                     chains.opening_time ,
                     chains.update_time ,
                     chains.last_comment ,
                     chains.last_item_id, 
                     max(tasks.progress) as progress
                    from chains
                    left Join users on chains.user_id   =   users.id 
                    left Join chain_items on  chains.id   =   chain_items.chain_id
                    left Join tasks on chain_items.task_id   =   tasks.id 
                    where (chains.client_id   =  ?  and  chains.status   =   'OPENED' and chains.deleted   =  0 ) 
                    group by users.name, chains.opening_time, chains.update_time, chains.last_comment, chains.last_item_id, chains.id
                    order by opening_time desc
                ", [$id]);

            $chains_closed = DB::select("select  users.name as avtor ,
                     chains.id,
                     chains.closing_time,
                     chains.last_comment,
                     chains.last_item_id, 
                     max(tasks.progress) as progress
                    from chains
                    left Join users on chains.user_id   =   users.id 
                    left Join chain_items on  chains.id   =   chain_items.chain_id
                    left Join tasks on chain_items.task_id   =   tasks.id 
                    where (chains.client_id   =  ?  and  chains.status   =   'CLOSED' and chains.deleted   =  0 ) 
                    group by users.name, chains.opening_time, chains.update_time, chains.last_comment, chains.last_item_id, chains.id
                    order by closing_time desc limit 5
                ", [$id]);
            $tasks = DB::table('tasks')
            ->leftJoin('users', 'tasks.responsible_id', '=', 'users.id')
            ->Join('chains', 'tasks.chain_id', '=', 'chains.id')
            ->select('tasks.creation_time','users.name as otvetstv','tasks.progress','tasks.priority','tasks.deadline_time','tasks.message','tasks.chain_id')
            ->where([['tasks.client_id', '=', $id],['tasks.status', '<>', 'SOLVED'],['tasks.status', '<>', 'CLOSED'],['chains.deleted', '=', 0]])->orderBy('tasks.creation_time', 'desc')->get();
        }
        
        //var_dump(count(Client::find($id)->groups)); exit;
        return view('lc.lc_clt_view', [
            'clt_id' => $id,
            'clients' => $client,
            'addresses' => $addresses,
            'chains_opened' => $chains_opened,
            'chains_closed' => $chains_closed,
            'tasks' => $tasks,
        ]);
        
    }
    
    public function personal_new() {

        $clt_types = DB::table('clients_type')
            ->select('clients_type.id','clients_type.name')
            ->get();     
        
        return view('lc.lc_clt_new',[
            'clt_types' => $clt_types,
        ]);
    }
    
    public function personal_store(Request $request) {

        $clients_type_id = $request->input('v_clt_type');
        
        //var_dump($clients_type_id); exit;

        if ($clients_type_id == 1 || $clients_type_id == 2 || $clients_type_id == 4) {
            $name = $request->input('v_clt_name');
            $surname = $request->input('v_clt_surname');
            $patronymic = $request->input('v_clt_otch');
            $sex = $request->input('v_clt_sex');
            $language = '';
            if ($request->input('v_clt_langs')) {
            foreach ($request->input('v_clt_langs') as $values) { $language=$language.$values.','; }
            $language = rtrim($language,',');
            }
            //$language = $request->input('v_clt_langs');
        }
        if ($clients_type_id == 1) {
            $mother = $request->input('v_clt_mother');
            $father = $request->input('v_clt_father');
        }
        if ($clients_type_id == 3) {
            $org = $request->input('v_clt_org');
        }

        $new_clt = '';
        if ($clients_type_id == 1) {
            $new_clt = Client::create(compact('clients_type_id', 'surname', 'name', 'patronymic', 'sex', 'mother', 'father', 'language'));
            $user = User::find($request->user()->id);
            $user->client_id = $new_clt->id;
            $user->save();
        } else if ($clients_type_id == 2 || $clients_type_id == 4) {
            $new_clt = Client::create(compact('clients_type_id', 'surname', 'name', 'patronymic', 'sex', 'language'));
            $user = User::find($request->user()->id);
            $user->client_id = $new_clt->id;
            $user->save();
        } else if ($clients_type_id == 3){
            $new_clt = Client::create([
                'clients_type_id' => $clients_type_id,
                'name' => $org,
            ]);
        }
        
        //return redirect()->route('clients.edit', [$new_clt])->with( 'new_clt', $new_clt );
        //return redirect()->route('clients.edit', compact('id','new_clt','providers','clt_groups','clt_contracts','regions'));
        
        if ( !$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') && !$request->user()->hasRole('Руководство ГБУ РЦИТ') ) { 
            // генерация нового VPN юзера для данного клиента
            $vpn_res = DB::select("select * from portal_add_user(?)",[$new_clt->id])[0]->portal_add_user;
            if ($vpn_res!=='OK')
                return redirect()->route('lc.personal')->with('status', 'Изменения сохранены!')->with('status_vpn', $vpn_res);
            else
                return redirect()->route('lc.personal')->with('status', 'Изменения сохранены!');
        }
        else 
            return redirect()->route('clients.edit', ['id' => $new_clt->id, 'src' => 'lc']);
        //return view('clients.clt_edit/{$id}',compact('new_clt','providers','clt_groups','clt_contracts','regions'));    
        //return redirect('clients.edit');  
    }
    
    
}
