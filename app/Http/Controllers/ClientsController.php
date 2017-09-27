<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

//    $client = DB::table('clients')
//            ->leftJoin('providers', 'clients.provider_id', '=', 'providers.id')
//            ->leftJoin('contracts', 'clients.contract_id', '=', 'contracts.id')
//            ->leftJoin('ip_addresses', 'clients.id', '=', 'ip_addresses.clients_id')
//            ->leftJoin('clients_type', 'clients.clients_type_id', '=', 'clients_type.id')
//            ->select(DB::raw("clients.surname || ' ' || clients.name  || ' ' || clients.patronymic as clt_name,
//							clients_type.name as clt_type,
//							clients.id,
//							clients.problematic,
//							clients.creation_time,
//                                                        clients.active,
//							contracts.title as contract_name,
//							clients.mother,
//							clients.father, 
//							array_agg(host(int2inet(ip_addresses.address)) ||
//							CASE WHEN ip_addresses.netmask is not null THEN ' / '||host(int2inet(ip_addresses.netmask)) else '' END ||
//							CASE WHEN ip_addresses.gateway is not null THEN ' / '||host(int2inet(ip_addresses.gateway)) else '' END ) as ip_addresses,
//							clients.diagnose,
//							clients.comment,
//                                                        providers.name as prd_name,
//                                                        clients.numbers"))
//            ->where('clients.id', '=', $id)
//            ->groupBy('clients.id, clients.active, clients.surname, clients.name, clients.patronymic,
//                       clients.creation_time, clients.sex, clients.problematic, providers.name, clients.numbers, clients.comment,
//                       clients.mother, clients.diagnose, clients.father, clients_type.name,contracts.title')
//            ->get();        
        
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
    
             //var_dump($client);
    
//        $client = DB::table('clients')
//                ->orderBy('clients_view.clt_name', 'asc')
//                ->paginate(100);
//        
//  
//        $users = User::select('id','name')->get();//::all();
//
        return view('clients.clt_view', [
            'clients' => $client,
        ]);
    }

}
