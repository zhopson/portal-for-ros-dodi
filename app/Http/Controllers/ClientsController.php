<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Client;
//use App\Chain;
use App\User;
use App\Provider;
use App\Group;
use App\Contract;
use App\Postal;
use App\IPAddress;
use App\GroupClient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;

class ClientsController extends Controller {

    public function index(Request $request) {

        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') && !$request->user()->hasRole('Учителя')) {
            return redirect('forbidden');
        }


//    $clients = DB::table('clients_view')
//            ->orderBy('clients_view.clt_name', 'asc')
//            ->paginate(100);        
//        
//  
//        $users = User::select('id','name')->get();//::all();
//
        $add2exist_chain_id = '';
        //Chain::select('id', 'last_comment', 'creation_time', 'user_id')->where([['status', '=', 'OPENED'],['client_id', '=', $id],['deleted', '=', 0]])->get();
        //$chains_opened = [];
        return view('clients.clients', [
            'add2exist_chain_id' => $add2exist_chain_id,
                //'chains_opened' => $chains_opened,
        ]);

//        return view('clients.clients');
    }

    public function Get_json_clients(Request $request) {

        $clients = DB::table('clients_view')->orderBy('clients_view.clt_name', 'asc')->get(); //->paginate(100); 
        //$chains = DB::table('chains_view')->select('id','c_name')->get();//->paginate(100); 

        $isTeacher = $request->user()->hasRole('Учителя');
        $data = [];
        foreach ($clients as $row => $client) {

            $categories = '';
            $active = '';
            $groups = '';
            $pol = '';
            $adr = '';

            if ($client->gr_name) {
                foreach (explode(",", $client->gr_name) as $group)
                    $groups = $groups . '<li>' . $group . '</li>';
            }

            if ($client->active == 1)
                $active = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
            else
                $active = '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';

            if ($client->sex == '1')
                $pol = 'М';
            else if ($client->sex == '0')
                $pol = 'Ж';

            if ($client->address_number)
                $adr = $adr . ", д. " . $client->address_number;
            if ($client->address_building)
                $adr = $adr . "/" . $client->address_building;
            if ($client->address_apartment)
                $adr = $adr . ", кв. " . $client->address_apartment;

            $nums_str = '';
            foreach (explode("\n", $client->numbers) as $number) {
                $num_arr = (explode(":", $number));
                $clt_name_clear = str_replace('"', '', $client->clt_name);
                if ($isTeacher)
                    $nums_str = $nums_str . $num_arr[0];
                else
                    $nums_str = $nums_str . "<a href=\"JavaScript:call_client('" . $client->id . "','" . $clt_name_clear . "','" . $num_arr[0] . "');\">" . $num_arr[0] . "</a>";

                if (isset($num_arr[1]) && trim($num_arr[1]) != '') {
                    $nums_str = $nums_str . '(' . trim($num_arr[1]) . '), ';
                } else {
                    $nums_str = $nums_str . ',';
                }
            }
            $nums_str = rtrim($nums_str, ", ");

            $clt_edit_tag = '';

            if (!$isTeacher)
                $clt_edit_tag = '<a href="' . route('clients.edit', ['id' => $client->id]) . '"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';

            array_push($data, array(
                '<div><a href="' . route('clients.view', ['id' => $client->id]) . '" data-toggle="tooltip" title="'.str_replace('"', '', $client->clt_name).'">' . $client->id . '</a></div>',
                //$client->id,
                $active,
//                '<div><a href="'.route('clients.view', ['id' => $client->id]).'">'.$client->clt_name.'</a></div>'.$groups,
                $client->clt_name,
                $client->type_name,
                $pol,
                trim($client->address . $adr, ","),
                $client->prd_name,
                $nums_str,
                $client->comment,
                $clt_edit_tag,
                    //'<div><a href="'.route('clients.view', ['id' => $client->id]).'">'.$client->clt_name.'</a></div>',
                    )
            );
        }
        return ['data' => $data];

//    return response()->json($chains->toJson());
    }

    public function clt_view(Request $request, $id) {

        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') && !$request->user()->hasRole('Учителя')) {
            $clt_id = $request->user()->client_id;
            if ($clt_id != $id) {
                return redirect('forbidden');
            }
        }

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


//    $addresses = DB::select("select get_address_by_code(address_id) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        $addresses = DB::select("select get_address_by_aoid(address_aoid) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc", [$id]);

        $chains_opened = DB::select("select  users.name as avtor ,
                     chains.id ,
                     chains.creation_time ,
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
                        ->select('tasks.creation_time', 'users.name as otvetstv', 'tasks.progress', 'tasks.priority', 'tasks.deadline_time', 'tasks.message', 'tasks.chain_id')
                        ->where([['tasks.client_id', '=', $id], ['tasks.status', '<>', 'SOLVED'], ['tasks.status', '<>', 'CLOSED'], ['chains.deleted', '=', 0]])->orderBy('tasks.creation_time', 'desc')->get();

        $add2exist_chain_id = '';
//        if (strpos(url(), 'clients/view') || strpos(url()->previous(), 'lc/personal')) {
//            //var_dump('Вызывается из clients/view - любой протокол');
//            $add2exist_chain_id = '';
//        }
//        else {
//            $chains_opened = null;
//            $add2exist_chain_id = $ch_id;
//            //var_dump($ch_id);
//        }

        $ip_last_active = [];
//        if ($client[0]->ip_addresses != '{NULL}') {
//            foreach (explode(",", $client[0]->ip_addresses) as $ip_address) {
//                if (strpos(trim($ip_address, '/{}"'), '/'))
//                    $ip = trim(substr(trim($ip_address,'/{}"'),0,strpos(trim($ip_address,'/{}"'), '/')));
//                else
//                    $ip = trim($ip_address,'{}"\n');
//                $dates = DB::connection('pgsql_netflow')->select("select date_ from public.fn_activeipaddress(?)",[$ip]);
//                //var_dump($dates[0]->date_);
//                if ($dates) 
//                    $ip_last_active[$ip] = substr($dates[0]->date_,0,16);
//            }
//        }
        //var_dump($ip_last_active);exit;

        $vpn_status = DB::select("select * from vpn_userlist where id=?", [$id]);

        //$vpn_status[0]->username
        //$vpn_status[0]->pass
        //$vpn_status[0]->status
        //if ($vpn_status) ...


        return view('clients.clt_view', [
            'clients' => $client,
            'addresses' => $addresses,
            'chains_opened' => $chains_opened,
            'chains_closed' => $chains_closed,
            'tasks' => $tasks,
            'add2exist_chain_id' => $add2exist_chain_id,
            'ip_last_active' => $ip_last_active,
            'vpn_status' => $vpn_status,
        ]);
    }

//var_dump($client);

    public function vpn_error($usr_id, $msg) {

        return view('clients.vpn_error', [
            'msg' => $msg,
            'id' => $usr_id,
        ]);
    }

    public function clt_new($usr_id = null) {

        $clt_types = DB::table('clients_type')
                ->select('clients_type.id', 'clients_type.name')
                ->get();

        return view('clients.clt_new', [
            'clt_types' => $clt_types,
            'usr_id' => $usr_id,
        ]);
    }

    public function clt_store(Request $request) {

        $clients_type_id = $request->input('v_clt_type');
        $clients_usr_id = $request->input('v_clt_new_usr_id');

        if ($clients_type_id == 1 || $clients_type_id == 2 || $clients_type_id == 4) {
            $name = $request->input('v_clt_name');
            $surname = $request->input('v_clt_surname');
            $patronymic = $request->input('v_clt_otch');
            $sex = $request->input('v_clt_sex');
            $language = '';
            if ($request->input('v_clt_langs')) {
                foreach ($request->input('v_clt_langs') as $values) {
                    $language = $language . $values . ',';
                }
                $language = rtrim($language, ',');
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
            if ($clients_usr_id) {
                $user = User::find($clients_usr_id);
                $user->client_id = $new_clt->id;
                $user->save();
            }
        } elseif ($clients_type_id == 2 || $clients_type_id == 4) {
            $new_clt = Client::create(compact('clients_type_id', 'surname', 'name', 'patronymic', 'sex', 'language'));
            if ($clients_usr_id) {
                $user = User::find($clients_usr_id);
                $user->client_id = $new_clt->id;
                $user->save();
            }
        } else {
            $new_clt = Client::create([
                        'clients_type_id' => $clients_type_id,
                        'name' => $org,
            ]);
        }
        // генерация нового VPN юзера для данного клиента
        $vpn_res = DB::select("select * from portal_add_user(?)", [$new_clt->id])[0]->portal_add_user;


        //return redirect()->route('clients.edit', [$new_clt])->with( 'new_clt', $new_clt );
        //return redirect()->route('clients.edit', compact('id','new_clt','providers','clt_groups','clt_contracts','regions'));
        if ($vpn_res !== 'OK')
            return redirect()->route('clients.vpn_error', ['id' => $new_clt->id, 'msg' => $vpn_res]);
        else
            return redirect()->route('clients.edit', ['id' => $new_clt->id]);
        //return view('clients.clt_edit/{$id}',compact('new_clt','providers','clt_groups','clt_contracts','regions'));    
        //return redirect('clients.edit');  
    }

    public function clt_edit(Request $request, $id, $src = null) {

//        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') ) { 
////            $clt_id = $request->user()->client_id;
////            if ($clt_id != $id) { return redirect('forbidden');}
//            return redirect('forbidden');
//        }  


        $new_clt = Client::find($id);

        $address_components = '';
        $addresses = '';
        $raions = '';

        $regions = DB::connection('pgsql_adr')->table('fias_addressobjects')
                ->select('shortname', 'offname', 'aoguid')
                ->whereNull('parentguid')
                ->get();

        if ($new_clt->address_aoid) {

            $address_components = DB::select("select * from public.get_address_components_by_guid(?) order by taolevel", [$new_clt->address_aoid]);
            $addresses = DB::select("select address_aoid,get_address_by_aoid(address_aoid) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc", [$id]);
            //$raion_guid = ''; $city_guid = ''; $np_guid = ''; $st_guid = '';
//            foreach ($address_components as $component) { 
//                if ($component->taolevel == 3) { $raion_guid = $component->tparentguid; }
//                else if ($component->taolevel == 4) { $city_guid = $component->tparentguid; }
//                else if ($component->taolevel == 6) { $np_guid = $component->tparentguid; }
//                else { $st_guid = $component->tparentguid; }
//                
//            }            
//            $raions = DB::connection('pgsql_adr')->table('fias_addressobjects')->select('shortname','offname','aoguid')->where('parentguid','=',$regions[0]->aoguid)->get();        
//            $cities = DB::connection('pgsql_adr')->table('fias_addressobjects')->select('shortname','offname','aoguid')->where('parentguid','=',$city_guid)->get();        
//            $nps = DB::connection('pgsql_adr')->table('fias_addressobjects')->select('shortname','offname','aoguid')->where('parentguid','=',$np_guid)->get();        
//            $sts = DB::connection('pgsql_adr')->table('fias_addressobjects')->select('shortname','offname','aoguid')->where('parentguid','=',$st_guid)->get();        
        }
        $providers = Provider::all();
        $clt_groups = Group::all();
        $clt_contracts = Contract::all();
//        $regions = DB::connection('pgsql_adr')->table('fias_addressobjects')
//                ->select('shortname','offname')
//                ->where([['areacode', '=', '000'],['citycode', '=', '000'],['placecode', '=', '000'],['streetcode', '=', '0000']])
//                ->get();
        //$new_clt =  session('new_clt');
//        var_dump($new_clt);
        $align_user = User::where('client_id', $new_clt->id)->first();

        return view('clients.clt_edit', compact('new_clt', 'providers', 'clt_groups', 'clt_contracts', 'regions', 'address_components', 'addresses', 'align_user', 'src'));
    }

    public function clt_update(Request $request, $id) {

        if ($request->input('v_clt_edit_inet_ip1')) {
            $n = 1;
            while ($request->input('v_clt_edit_inet_ip' . $n)) {

                $rules = array(
                    'v_clt_edit_inet_ip' . $n => array('ip'),
                        //'v_clt_edit_inet_ip*' 	=> 'required|email|unique:users,email',
                );
                $messages = array(
                    'v_clt_edit_inet_ip' . $n . '.ip' => 'Неверный формат ip',
                );

                $validator = Validator::make(Input::all(), $rules, $messages);

                if ($validator->fails()) {
                    //var_dump($validator->messages());
                    return \Redirect::back()->withInput()->withErrors($validator);
                }

                $n++;
                //$default_ip_ind
            }
        }
        //var_dump($request->old('v_clt_edit_surname'));exit;
        $clt = Client::find($id);

        $surname = $request->input('v_clt_edit_surname');
        $name = $request->input('v_clt_edit_name');
        $otch = $request->input('v_clt_edit_otch');
        $sex = $request->input('inlineRadio_clt_sex');
        $language = '';
        if ($request->input('v_clt_edit_langs')) {
            foreach ($request->input('v_clt_edit_langs') as $values) {
                $language = $language . $values . ',';
            }
            $language = rtrim($language, ',');
        }
        $father = $request->input('v_clt_edit_father');
        $mother = $request->input('v_clt_edit_mother');
        $org = $request->input('v_clt_edit_org');
        $diag = $request->input('v_clt_edit_diag');
        $adr_prev = $request->input('v_clt_edit_adr_prev');
        $adr_ind = $request->input('v_clt_edit_adr_ind');
        $adr_region = $request->input('v_clt_edit_adr_region');
        $adr_raion = $request->input('v_clt_edit_adr_raion');
        $adr_city = $request->input('v_clt_edit_adr_city');
        $adr_np = $request->input('v_clt_edit_adr_np');
        $adr_st = $request->input('v_clt_edit_adr_st');
        $adr_dom = $request->input('v_clt_edit_adr_dom');
        $adr_korp = $request->input('v_clt_edit_adr_korp');
        $adr_kv = $request->input('v_clt_edit_adr_kv');
        $contacts_tel = $request->input('v_clt_edit_contacts_tel');
        $contacts_name = $request->input('v_clt_edit_contacts_name');
        $contacts_mail = $request->input('v_clt_edit_contacts_mail');
        $contacts_skype = $request->input('v_clt_edit_contacts_skype');
        $inet_prd = $request->input('v_clt_edit_inet_prd');
        $inet_ip = $request->input('v_clt_edit_inet_ip');
        $mask_ip = $request->input('v_clt_edit_mask_ip');
        $gate_ip = $request->input('v_clt_edit_gate_ip');
        $default_ip_ind = $request->input('clt_edit_inet_table_optionsRadios');
        $dop_active = $request->input('v_clt_edit_dop_active');
        $dop_problem = $request->input('v_clt_edit_dop_problem');
        $dop_contract = $request->input('v_clt_edit_dop_contract');
        $dop_prim = $request->input('v_clt_edit_dop_prim');
        //$surname = Request::input('id_clt_edit_surname');
        //$surname = Input::post('id_clt_edit_surname');
//        if ($default_ip_ind == 1)
//        var_dump($default_ip_ind);
//        exit;
        //$old_adr_st = $request->old('v_clt_edit_adr_st');
        $clt->surname = $surname;
        $clt->name = $name;
        $clt->patronymic = $otch;
        $clt->sex = $sex;
        $clt->language = $language;
        $clt->mother = $mother;
        $clt->father = $father;
        if ($clt->clients_type_id == 3) {
            $clt->name = $org;
            $clt->surname = '';
            $clt->patronymic = '';
        }
        $clt->diagnose = $diag;

        if (!$adr_ind)
            $adr_ind = '';
        if (!$adr_dom)
            $adr_dom = '';
        if (!$adr_korp)
            $adr_korp = '';
        if (!$adr_kv)
            $adr_kv = '';

        if (( $clt->address_aoid != $adr_st || $clt->address_number != $adr_dom || $clt->address_building != $adr_korp || $clt->address_apartment != $adr_kv ) && $adr_region !== '0' && $adr_st != null) {

            if ($adr_st !== '0' && ($adr_city !== '0' || $adr_np !== '0')) {

                $address_id = DB::connection('pgsql_adr')->select("SELECT code FROM fias_addressobjects where aoid = ? limit 1", [$adr_st])[0]->code;

                $clt->address_postal = $adr_ind;
                $clt->address_number = $adr_dom;
                $clt->address_building = $adr_korp;
                $clt->address_apartment = $adr_kv;
                $clt->address_aoid = $adr_st;
                $clt->address_id = $address_id;

                $new_postal = Postal::create(['client_id' => $id, 'address_id' => $address_id, 'address_postal' => $adr_ind, 'address_number' => $adr_dom, 'address_building' => $adr_korp, 'address_apartment' => $adr_kv, 'address_aoid' => $adr_st]);

                $clt->postal_id = $new_postal->id;
            } else
                return \Redirect::back()->withInput()->withErrors($errors = ["Некоторые поле адреса не заполнены!"]);
        }

        $phone_book = '';
        $n = 1;
        while ($n <= 25) {
            if ($request->input('v_clt_edit_contacts_tel' . $n)) {
                $phone_book = $phone_book . $request->input('v_clt_edit_contacts_tel' . $n) . ':';
                if ($request->input('v_clt_edit_contacts_name' . $n))
                    $phone_book = $phone_book . $request->input('v_clt_edit_contacts_name' . $n) . PHP_EOL;
                else
                    $phone_book = $phone_book . PHP_EOL;
            }
            $n++;
        }
        $phone_book = rtrim($phone_book, PHP_EOL);

        $clt->address_postal = $adr_ind;
        $clt_postal = Postal::find($clt->postal_id);
        if ($clt_postal) {
            $clt_postal->address_postal = $adr_ind;
            $clt_postal->save();
        }

        $clt->numbers = $phone_book;
        $clt->email = $contacts_mail;
        $clt->skype = $contacts_skype;
        $clt->provider_id = $inet_prd;
        if ($dop_active == 'on')
            $clt->active = 1;
        else
            $clt->active = 0;
        if ($dop_problem == 'on')
            $clt->problematic = 1;
        else
            $clt->problematic = 0;
        if ($dop_contract != '0')
            $clt->contract_id = $dop_contract;
        else
            $clt->contract_id = null;
        if ($dop_prim != null)
            $clt->comment = $dop_prim;
        else
            $clt->comment = '';

        $ips_clt = IPAddress::where('clients_id', '=', $id)->delete();
        $n = 1;
        while ($n <= 20) {
            if ($request->input('v_clt_edit_inet_ip' . $n)) {
                $ip = '';
                $mask = '';
                $gate = '';
                $act = 0;
                $ip = $request->input('v_clt_edit_inet_ip' . $n);
                if ($request->input('v_clt_edit_mask_ip' . $n))
                    $mask = $request->input('v_clt_edit_mask_ip' . $n);
                if ($request->input('v_clt_edit_gate_ip' . $n))
                    $gate = $request->input('v_clt_edit_gate_ip' . $n);
                $id_ip = $request->input('v_clt_edit_id_ip' . $n);
                if ($default_ip_ind == $n)
                    $act = 1;
                //$new_ip = IPAddress::updateOrCreate(['id' => $id_ip], ['address' => ip2long($ip), 'clients_id' => $id, 'netmask' => ip2long($mask), 'gateway' => ip2long($gate), 'default' => $act]);
                $new_ip = IPAddress::create(['address' => ip2long($ip), 'clients_id' => $id, 'netmask' => ip2long($mask), 'gateway' => ip2long($gate), 'default' => $act]);
            }
            $n++;
        }

        $n = 1;
        $groups_len = Group::all()->count();
        $grps_clt = GroupClient::where('clients_id', '=', $id)->delete();
        while ($n <= $groups_len) {
            if ($request->input('v_clt_edit_dop_grs' . $n)) {
                //var_dump($request->input('v_clt_edit_dop_grs' . $n));
                $gr_id = $request->input('v_clt_edit_dop_grs' . $n);
                //$grp_clt = GroupClient::where([['clients_id', '=', $id],['groups_id', '=', $gr_id]])->first();
                //$grp_clt = GroupClient::updateOrCreate(['clients_id' => $id], ['groups_id' => $gr_id]);
                //GroupClient::Create(['clients_id' => $id], ['groups_id' => $gr_id]);
                $new_grp_clt = new GroupClient;
                $new_grp_clt->clients_id = $id;
                $new_grp_clt->groups_id = $gr_id;
                $new_grp_clt->save();
            }
            $n++;
        }

        $is_clt_on = $clt->active;
        //var_dump($sex);
        //
        //
        //
        //exit;
        $clt->save();

        $align_user = User::where('client_id', $clt->id)->first();
        if (!$align_user) {
            $usr_id = $request->input('v_clt_edit_user_align');
            if ($usr_id != '0') {
                $usr_clt = User::find($usr_id);
                $usr_clt->client_id = $clt->id;
                $usr_clt->save();
            }
        }
        ///////////////// обновление статуса VPN по статусу клиента ///////////////////////////////////////////
        $is_vpn_on = 1;
        $vpn_status = DB::select("select * from vpn_userlist where id=?", [$clt->id]);
        if ($vpn_status) {
            if ($vpn_status[0]->status == 'on')
                $is_vpn_on = 1;
            else
                $is_vpn_on = 0;

            if ($is_vpn_on !== $is_clt_on) {
                $vpn_res = DB::select("select * from portal_change_user_status(?,?)", [$clt->id, $is_clt_on])[0]->portal_change_user_status;
            }
        }
        ///////////////// обновление статуса VPN по статусу клиента ///////////////////////////////////////////
        $who_call = $request->input('v_clt_edit_src');

        if ($who_call === 'lc') {
            //var_dump('Вызывается из clients/view - любой протокол');
            return redirect()->route('lc.personal')->with('status', 'Изменения сохранены!');
        } else
            return redirect()->route('clients.view', ['id' => $id])->with('status', 'Изменения сохранены!');
        //return redirect()->route('clients.edit', ['id' => $id])->with('status', 'Изменения сохранены!');   
    }

    public function clt_advsearch(Request $request) {

        $clt_find_fio = $request->input('v_clt_find_fio');
        $clt_find_adr = $request->input('v_clt_find_adr');
        $clt_find_prd = $request->input('v_clt_find_prd');
        $clt_find_cnt = $request->input('v_clt_find_cnt');

        $providers = Provider::all();
        $clt_groups = Group::all();
        $clt_contracts = Contract::all();

        $date1 = date_create(date('Y-m-d'));
        date_sub($date1, date_interval_create_from_date_string('1 days'));
        $start_date = date_format($date1, 'Y-m-d');
        $end_date = date('Y-m-d');        

        return view('clients.clt_advsearch', compact('clt_find_fio', 'clt_find_adr', 'clt_find_prd', 'clt_find_cnt', 'providers', 'clt_groups', 'clt_contracts', 'start_date', 'end_date'));
    }

    public function Get_json_clt_advsearch(Request $request) {

        //$clients = DB::table('clients_view')->orderBy('clients_view.clt_name', 'asc')->get();//->paginate(100); 
        //$chains = DB::table('chains_view')->select('id','c_name')->get();//->paginate(100); 

        $clients = DB::select("select
				clients_view.id,
				clients_view.clt_name,
				clients_view.type_name,
				clients_view.address,
				clients_view.address_number,
				clients_view.address_building,
				clients_view.address_apartment,
                                clients_view.active,
				contracts.title as contract_name,
                                clients_view.gr_name,
				array_agg(host(public.int2inet(ip_addresses.address)) ||
				CASE WHEN ip_addresses.netmask is not null THEN ' / '||host(public.int2inet(ip_addresses.netmask)) else '' END ||
				CASE WHEN ip_addresses.gateway is not null THEN ' / '||host(public.int2inet(ip_addresses.gateway)) else '' END ) as ip_addresses,
                                clients_view.prd_name,
                                clients_view.numbers 
            from clients_view
            left Join contracts on  clients_view.contract_id   =   contracts.id 
            left Join ip_addresses on clients_view.id   =   ip_addresses.clients_id 
		group by clients_view.id , clients_view.active , clients_view.clt_name, clients_view.active,contracts.title,
                     clients_view.address , clients_view.type_name, clients_view.gr_name,clients_view.prd_name,                  
                     clients_view.numbers,  clients_view.address_number, clients_view.address_building, clients_view.address_apartment");

        //$isTeacher = $request->user()->hasRole('Учителя');
        $data = [];
        foreach ($clients as $row => $client) {

            $active = '';
            $groups = '';
            $adr = '';
            $ips = '';

            if ($client->gr_name) {
                foreach (explode(",", $client->gr_name) as $group)
                    $groups = $groups . '<li>' . $group . '</li>';
            }

            if ($client->active == 1)
                $active = 'Активный';
//                $active = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
            else
                $active = 'Неактивный';
//                $active = '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';

            if ($client->address_number)
                $adr = $adr . ", д. " . $client->address_number;
            if ($client->address_building)
                $adr = $adr . "/" . $client->address_building;
            if ($client->address_apartment)
                $adr = $adr . ", кв. " . $client->address_apartment;

            $nums_str = '';
            foreach (explode("\n", $client->numbers) as $number) {
                $num_arr = (explode(":", $number));
                //$clt_name_clear =  str_replace('"', '', $client->clt_name);
//                if ($isTeacher) 
                $nums_str = $nums_str . $num_arr[0];
//                else
//                    $nums_str = $nums_str."<a href=\"JavaScript:call_client('".$client->id."','".$clt_name_clear."','".$num_arr[0]."');\">".$num_arr[0]."</a>";

                if (isset($num_arr[1]) && trim($num_arr[1]) != '') {
                    $nums_str = $nums_str . '(' . trim($num_arr[1]) . '), ';
                } else {
                    $nums_str = $nums_str . ',';
                }
            }
            $nums_str = rtrim($nums_str, ", ");

            if ($client->ip_addresses != '{NULL}') {
                $ips = str_replace(",", ";\r", trim($client->ip_addresses, '{}"'));
                $ips = str_replace('"', '', $ips);
            }
            //$ips = str_replace(array("\n", "\r"), " ", $s);
//            $clt_edit_tag = '';
//            
//            if (!$isTeacher) 
//                $clt_edit_tag = '<a href="'.route('clients.edit', ['id' => $client->id]).'"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';

            array_push($data, array(
                //$client->id,
                '<div><a href="' . route('clients.view', ['id' => $client->id]) . '" data-toggle="tooltip" title="'.str_replace('"', '', $client->clt_name).'">' . $client->id . '</a></div>',
                $active,
                '<div><a href="' . route('clients.view', ['id' => $client->id]) . '">' . $client->clt_name . '</a></div>',
                $client->type_name,
                trim($client->address . $adr, ","),
                $client->contract_name,
                $client->prd_name,
                $groups,
                $ips,
                $nums_str,
                $client->clt_name,
                $client->id,
                    )
            );
        }
        return ['data' => $data];

//    return response()->json($chains->toJson());
    }

    public function do_ajax_get_clt_traf(Request $request) {
        if ($request->ajax()) {

            $ids = $request->input('ids');
            //$is_fake = $request->input('is_fake');
            $date1 = $request->input('date1');
            $date2 = $request->input('date2');

            $start_date = $date1;// . ' 00:00:00';
            $end_date = $date2;// . ' 23:59:59';

            $traf = DB::connection('pgsql_netflow')->select("select user_id, sum(bytes_in/1024) as kbytes_in,sum(bytes_out/1024) as kbytes_out from public.view_user_stat where user_id in " . $ids . " and period>'" . $start_date . "' and period<'" . $end_date . "' group by user_id order by user_id");

//            if ($is_fake == 'on') {
//                $avg = DB::connection('pgsql_netflow')->select("select avg(bytes_in/1024) as kbytes_in, avg(bytes_out/1024) as kbytes_out from public.view_user_stat_graph where user_id in " . $ids . " and period>'" . $start_date . "' and period<'" . $end_date . "'");
//            }
            //$data_traf = [];
            $data_values_in = [];
            $data_values_out = [];

            foreach ($traf as $row => $item) {

                $data_values_in[$item->user_id] = $item->kbytes_in;
                $data_values_out[$item->user_id] = $item->kbytes_out;
                //array_push($data_values_in, $item->kbytes_in);
                //array_push($data_values_out, $item->kbytes_out);
                //array_push($data_traf, $item);
            }

            $a_in_sp = array_filter($data_values_in, function($x) {
                return $x !== '0';
            });
            $a_out_sp = array_filter($data_values_out, function($x) {
                return $x !== '0';
            });
            
            $avg_traf_in = 0;
            $avg_traf_out = 0;
            
            if (count($a_in_sp) > 0) $avg_traf_in = round(array_sum($a_in_sp) / count($a_in_sp), 2);
            if (count($a_out_sp) > 0) $avg_traf_out = round(array_sum($a_out_sp) / count($a_out_sp), 2);

            return Response::json([
                        //'data_traf' => $data_traf,
                        'data_traf_in' => $data_values_in,
                        'data_traf_out' => $data_values_out,
                        'avg_traf_in' => $avg_traf_in,
                        'avg_traf_out' => $avg_traf_out,
                        'status' => 1
            ]);
        }
        return 'error';
    }

}
