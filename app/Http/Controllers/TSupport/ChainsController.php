<?php

namespace App\Http\Controllers\TSupport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Chain;
use App\User;
use Illuminate\Support\Facades\DB;

class ChainsController extends Controller
{
    public function index() {
        
    $chains = DB::table('chains')
            ->leftJoin('clients', 'chains.client_id', '=', 'clients.id')
            ->leftJoin('users', 'chains.user_id', '=', 'users.id')
            ->leftJoin('chains_categories', 'chains.id', '=', 'chains_categories.chain_id')
            ->leftJoin('categories', 'categories.id', '=', 'chains_categories.category_id')
            ->leftJoin('chain_items', 'chains.last_item_id', '=', 'chain_items.id')
            ->leftJoin('clt_adr_area_view', 'clients.address_id', '=', 'clt_adr_area_view.address_id')
            ->select('chains.id',
                    'chains.update_time',
                    'clients.surname',
                    'clients.name as c_name',
                    'clients.patronymic',
                    'clt_adr_area_view.title as address',
//                    'clients.address_id',
//                    'get_uus_by_code(clients.address_id) as address',
                    'users.name as u_name',
                    'chain_items.user_id as operator_id',
                    'chains.status',
                    'chains.opening_time',
                    'chains.last_comment',
                    'categories.name as cat_name'
                    )
            ->orderBy('update_time', 'desc')
            ->paginate(100);        
        
  
        $users = User::select('id','name')->get();//::all();

//        $chains = Chain::paginate(100);//all();
        //$chains = DB::table('chains')->get();
        
        return view('tsupport.chains', [
            'chains' => $chains,
            'users' => $users,
        ]);
    }
    
    public function chain_view($id) {

    $chain = DB::table('chains')
            ->leftJoin('users', 'chains.user_id', '=', 'users.id')
            ->leftJoin('clients', 'chains.client_id', '=', 'clients.id')
            ->leftJoin('chains_categories', 'chains.id', '=', 'chains_categories.chain_id')
            ->leftJoin('categories', 'categories.id', '=', 'chains_categories.category_id')
            //->leftJoin('clt_adr_full_view', 'clients.address_id', '=', 'clt_adr_full_view.address_id')
//            ->leftJoin('postals', 'clients.address_id', '=', 'postals.address_id')
            ->select('chains.status',
                    'chains.creation_time',
                    'users.name as avtor',
                    'chains.client_id',
                    'clients.surname',
                    'clients.name as c_name',
                    'clients.patronymic', 
                    //'clt_adr_full_view.title as address',
                    'clients.address_number as dom',
                    'clients.address_building as korp',
                    'clients.address_apartment as kv',
                    'categories.name as category'
                    )
            ->where('chains.id', '=', $id)->first();
    
    $adr_code = DB::table('chains')
            ->Join('clients', 'chains.client_id', '=', 'clients.id')
            ->select('clients.address_id as adr_id')->where('chains.id', '=', $id)->get();
    
    //$address = $adr_code[0]->adr_id;
    
    $address_arr = DB::select('select * from get_address_by_code(?)', [$adr_code[0]->adr_id]);
    
//    $address = $address_arr[0]->streetname_;
    $address = '';
    if ($address_arr[0]->areaname_) {$address = $address.$address_arr[0]->areaname_.',';}
    if ($address_arr[0]->cityname_) {$address = $address.$address_arr[0]->cityname_.',';}
    if ($address_arr[0]->placename_) {$address = $address.$address_arr[0]->placename_.',';}
    if ($address_arr[0]->streetname_) {$address = $address.$address_arr[0]->streetname_;}
    
    //$address = str_replace("%body%", "black", $address_arr);
        
    $chains_items = DB::table('chain_items')
            ->leftJoin('tasks', 'chain_items.task_id', '=', 'tasks.id')
            ->leftJoin('calls', 'chain_items.call_id', '=', 'calls.id')
            ->leftJoin('requests', 'chain_items.request_id', '=', 'requests.id')
            ->leftJoin('users', 'chain_items.user_id', '=', 'users.id')
            ->select('chain_items.id',
                    'chain_items.creation_time',
                    'users.name as avtor',
                    'chain_items.update_time',
                    'chain_items.call_id',
                    'chain_items.task_id',
                    'chain_items.request_id',
                    'chain_items.note_id',
                    'tasks.responsible_id',
                    'tasks.progress',
                    'calls.interlocutor',
                    'calls.status as call_status',
                    'tasks.status as task_status',
                    'requests.provider_id',
                    'tasks.start_time',
                    'tasks.deadline_time',
                    'chain_items.message'
                    )
            ->where('chain_items.chain_id', '=', $id)
            ->orderBy('creation_time', 'desc')->get();
    
    $users = User::select('id','name')->get();
    
        return view('tsupport.chain_view', compact('chain','chains_items','users','id','address'));
//        return view('tsupport.chain_view', [
//            'chains_items' => $chains_items,
//            'users' => $users,
//            'id' => $id,
//        ]);
    }

}
