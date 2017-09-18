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
            ->select('chains.id',
                    'chains.update_time',
                    'clients.surname',
                    'clients.name as c_name',
                    'clients.patronymic',
                    'clients.address_id',
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
            ->select('chains.status',
                    'chains.creation_time',
                    'users.name as avtor',
                    'chains.client_id',
                    'clients.surname',
                    'clients.name as c_name',
                    'clients.patronymic',                    
                    'clients.address_id',
                    'categories.name as category'
                    )
            ->where('chains.id', '=', $id)->first();
        
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
    
        return view('tsupport.chain_view', compact('chain','chains_items','users','id'));
//        return view('tsupport.chain_view', [
//            'chains_items' => $chains_items,
//            'users' => $users,
//            'id' => $id,
//        ]);
    }

}
