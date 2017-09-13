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

}
