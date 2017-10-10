<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Client;
use App\Provider;
use App\Group;
use App\Contract;

class ClientsController extends Controller
{
    public function index() {
        
    $clients = DB::table('clients_view')
            ->orderBy('clients_view.clt_name', 'asc')
            ->paginate(100);        
//        
//  
//        $users = User::select('id','name')->get();//::all();
//
        return view('clients.clients', [
            'clients' => $clients,
        ]);
    }

    public function clt_view($id) {

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
            ->select('tasks.creation_time','users.name as otvetstv','tasks.progress','tasks.priority','tasks.deadline_time','tasks.message','tasks.chain_id')
            ->where([['tasks.client_id', '=', $id],['status', '<>', 'SOLVED'],['status', '<>', 'CLOSED']])->get();
        
        return view('clients.clt_view', [
            'clients' => $client,
            'addresses' => $addresses,
            'chains_opened' => $chains_opened,
            'chains_closed' => $chains_closed,
            'tasks' => $tasks,
        ]);
    }
//var_dump($client);

 public function clt_new() {

        $clt_types = DB::table('clients_type')
            ->select('clients_type.id','clients_type.name')
            ->get();     
        
        return view('clients.clt_new',[
            'clt_types' => $clt_types,
        ]);
 }    

 public function clt_store(Request $request) {

        $clients_type_id = $request->input('v_clt_type');

        if ($clients_type_id == 1 || $clients_type_id == 2) {
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
        } elseif ($clients_type_id == 2) {
            $new_clt = Client::create(compact('clients_type_id', 'surname', 'name', 'patronymic', 'sex', 'language'));
        } else {
            $new_clt = Client::create([
                'clients_type_id' => $clients_type_id,
                'name' => $org,
            ]);
        }
        $id = $new_clt->id;
        //var_dump(explode(",",$new_clt->language));
//            [
//        'type' => $type,
//        'surname' => $request->input('name'),
//        'name' => $request->input('name'),
//        'patronymic' => $request->input('name'),
//        'sex' => $request->input('name'),
//        'mother' => $request->input('name'),
//        'father' => $request->input('name'),
//        'language' => $request->input('name'),
//    ]);
        //return redirect()->route('clients.edit', [$new_clt])->with( 'new_clt', $new_clt );
        
        //return redirect()->route('clients.edit', compact('id','new_clt','providers','clt_groups','clt_contracts','regions'));
        return redirect()->route('clients.edit', ['id' => $new_clt->id]);
        //return view('clients.clt_edit/{$id}',compact('new_clt','providers','clt_groups','clt_contracts','regions'));    
        //return redirect('clients.edit');  
 }
 
 public function clt_edit($id) {

        $providers = Provider::all();
        $clt_groups = Group::all();
        $clt_contracts = Contract::all();
//        $regions = DB::connection('pgsql_adr')->table('fias_addressobjects')
//                ->select('shortname','offname')
//                ->where([['areacode', '=', '000'],['citycode', '=', '000'],['placecode', '=', '000'],['streetcode', '=', '0000']])
//                ->get();
        $regions = DB::connection('pgsql_adr')->table('fias_addressobjects')
                ->select('shortname','offname','aoguid')
                ->whereNull('parentguid')
                ->get();        
        //$new_clt =  session('new_clt');
//        var_dump($new_clt);
        $new_clt = Client::find($id);
        return view('clients.clt_edit',compact('new_clt','providers','clt_groups','clt_contracts','regions'));
 }
 
}
