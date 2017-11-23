<?php

namespace App\Http\Controllers\TSupport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Chain;
use App\ChainItems;
use App\Category;
use App\ChainCategory;
use App\User;
use App\ChainUser;

class ChainsController extends Controller
{
    public function index() {

//    $chains = DB::table('chains_view')->get();//->paginate(100); 
    
//    $chains = DB::table('chains')
//            ->leftJoin('clients', 'chains.client_id', '=', 'clients.id')
//            ->leftJoin('users', 'chains.user_id', '=', 'users.id')
//            ->leftJoin('chains_categories', 'chains.id', '=', 'chains_categories.chain_id')
//            ->leftJoin('categories', 'categories.id', '=', 'chains_categories.category_id')
//            ->leftJoin('chain_items', 'chains.last_item_id', '=', 'chain_items.id')
//            ->leftJoin('clt_adr_area_view', 'clients.address_id', '=', 'clt_adr_area_view.address_id')
//            ->select('chains.id',
//                    'chains.update_time',
//                    'clients.surname',
//                    'clients.name as c_name',
//                    'clients.patronymic',
//                    'clt_adr_area_view.title as address',
////                    'clients.address_id',
////                    'get_uus_by_code(clients.address_id) as address',
//                    'users.name as u_name',
//                    'chain_items.user_id as operator_id',
//                    'chains.status',
//                    'chains.opening_time',
//                    'chains.last_comment',
//                    'categories.name as cat_name'
//                    )
//            ->orderBy('update_time', 'desc')
//            ->paginate(100);        

//        $ch_status = array(
//            'OPENED' => 'Открыт',
//            'CLOSED' => 'Закрыт',
//        );        
//
//  
//        $users = User::select('id','name')->get();//::all();

//        $chains = Chain::paginate(100);//all();
        //$chains = DB::table('chains')->get();
        
//        return view('tsupport.chains', [
//            'chains' => $chains,
//            'users' => $users,
//            'ch_status' =>  $ch_status,
//        ]);
        return view('tsupport.chains');
        
    }

    public function Get_json_chains() {

    $chains = DB::table('chains_view')->get();//->paginate(100); 
    //$chains = DB::table('chains_view')->select('id','c_name')->get();//->paginate(100); 
    

        $ch_status = array(
            'OPENED' => 'Открыт',
            'CLOSED' => 'Закрыт',
        );        
  
        $users = User::select('id','name')->get();//::all();

//        $chains = Chain::paginate(100);//all();
        //$chains = DB::table('chains')->get();
        $data = [];
        foreach ($chains as $row=>$chain){
            $categories = '';
            foreach (explode(",",$chain->cat_names) as $cat_name) {
                if($cat_name != 'NULL')
                    $categories = $categories.'<li>'.rtrim($cat_name, ", ").'</li>';
            }
            
            array_push($data, 
              array(
                $chain->id,
                date('d.m.y H:i',$chain->update_time),
                '<a href="'.route('clients.view', ['id' => $chain->client_id]).'">'.$chain->surname.' '.$chain->c_name.' '.$chain->patronymic.'</a>',
                $chain->address,
                $chain->u_name,
                $users->find($chain->operator_id)->name,
                $ch_status[$chain->status],
                date('d.m.y H:i',$chain->opening_time),
                '<a href="'.route('chains.view', ['id' => $chain->id]).'">'.$chain->last_comment.'</a>',
                $categories
              )
            );
        }
        return ['data'=>$data];
        
//    return response()->json($chains->toJson());
    }
    
    public function chain_view($id) {
        
        $ch_status = array(
            'OPENED' => 'Открыт',
            'CLOSED' => 'Закрыт',
        );        

    $chain = DB::table('chains')
            ->leftJoin('users', 'chains.user_id', '=', 'users.id')
            ->leftJoin('clients', 'chains.client_id', '=', 'clients.id')
            ->leftJoin('chains_categories', 'chains.id', '=', 'chains_categories.chain_id')
            ->leftJoin('categories', 'categories.id', '=', 'chains_categories.category_id')
            //->leftJoin('clt_adr_full_view', 'clients.address_id', '=', 'clt_adr_full_view.address_id')
//            ->leftJoin('postals', 'clients.address_id', '=', 'postals.address_id')
            ->select(DB::raw('chains.status,chains.creation_time,users.name as avtor,chains.client_id,clients.surname,clients.name as c_name,clients.patronymic, 
                    clients.address_number as dom,clients.address_building as korp,clients.address_apartment as kv,array_agg(categories.name) as categories'
                    ))
            ->groupBy('chains.status','chains.creation_time','users.name','chains.client_id','clients.surname','clients.name','clients.patronymic','clients.address_number','clients.address_building','clients.address_apartment')
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
    
        return view('tsupport.chain_view', compact('chain','chains_items','users','id','address','ch_status'));
//        return view('tsupport.chain_view', [
//            'chains_items' => $chains_items,
//            'users' => $users,
//            'id' => $id,
//        ]);
    }

    public function chain_edit($id) {
        $chain_categories = ChainCategory::select('category_id', 'chain_id')->where('chain_id', '=', $id)->get();
        $categories = Category::all();
        return view('tsupport.chain_edit', compact('id','chain_categories','categories'));
    }

    public function chain_update(Request $request, $id) {
        $n = 1;
        $cat_len = Category::all()->count();
        $chain_categories = ChainCategory::where('chain_id', '=', $id)->delete();
        while ( $n <= $cat_len ) {
            if ( $request->input('v_ch_edit_cat' . $n) ) {
                //var_dump($request->input('v_ch_edit_cat' . $n));
                $cat_id = $request->input('v_ch_edit_cat' . $n);
                
                //$grp_clt = GroupClient::where([['clients_id', '=', $id],['groups_id', '=', $gr_id]])->first();
                //$grp_clt = GroupClient::updateOrCreate(['clients_id' => $id], ['groups_id' => $gr_id]);
                ChainCategory::Create(['category_id' => $cat_id,'chain_id' => $id]);
                
//                $new_ch_cat = new ChainCategory;
//                $new_ch_cat->clients_id = $id;
//                $new_ch_cat->groups_id = $gr_id;
//                $new_ch_cat->save();
            }
            $n++; 
        }
        return redirect()->route('chains.view', ['id' => $id]);
    }
    
    public function chain_close($id) {
        $open_tasks = DB::select("select id from tasks where chain_id = ? and status != 'SOLVED' and status != 'CLOSED'", [$id]);
        
        if (count($open_tasks)>0) 
            $errors = ["Есть открытые задачи"];
        //$categories = Category::all();
        return view('tsupport.chain_close', compact('id','errors'));
    }    

    public function chain_closing(Request $request, $id) {
        
        $last_comment = $request->input('v_ch_close_msg');
        if (!$last_comment) $last_comment = '(закрыт)';

        //var_dump($last_comment); exit;
        
        $chain = Chain::find($id);
        
        $user_id =  $request->user()->id;
        $chain_id = $id;
        
        $message = $last_comment;
        $type = 'NOTICE';
        
        $new_chain_items = ChainItems::Create(compact('chain_id','user_id','type','message'));
        
        $chain->status = 'CLOSED';
        $today = strtotime(date("Y-m-d H:i"));
        $chain->who_closed = $user_id;
        $chain->closing_time = $today;
        $chain->last_comment = $last_comment;
        $chain->last_item_id = $new_chain_items->id;
        $chain->save();
        
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        
        return redirect()->route('chains.view', ['id' => $chain_id]);    
        
    }    

    public function chain_remove($id) {
        $chain = Chain::find($id);
        $client = $chain->client;
        $fio = '';
        
        if ($client->clients_type_id == 3) $fio = $client->name;
        else $fio = $client->surname.' '.$client->name.' '.$client->patronymic;
        
        //var_dump($fio); exit;
        return view('tsupport.chain_remove', compact('id','fio'));  
        
    }

    public function chain_removing(Request $request, $id) {
        
        $last_comment = $request->input('v_ch_remove_msg');
        
        $chain = Chain::find($id);
        
        $user_id =  $request->user()->id;
        $chain_id = $id;
        
        $message = $last_comment;
        $type = 'DELETED';
        
        $new_chain_items = ChainItems::Create(compact('chain_id','user_id','type','message'));
        
        $chain->last_comment = $last_comment;
        $chain->last_item_id = $new_chain_items->id;
        $chain->deleted = 1;
        $chain->save();
        
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        
        return redirect()->route('clients.view', ['id' => $chain->client_id]);
        
    }    
    
    
}

