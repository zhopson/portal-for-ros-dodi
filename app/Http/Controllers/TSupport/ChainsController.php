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

use DataTables;

class ChainsController extends Controller
{
    public function index(Request $request) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') && !$request->user()->hasRole('Учителя') ) { 
            return redirect('forbidden');
        }  
        

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
        
        $users = User::select('id','name')->get();
        
        $clt_id = '';
        $usr_id = '';
        return view('tsupport.chains',compact('clt_id','usr_id','users'));
        
    }

//    public function json_fix_cyr($json_str) {
//    $cyr_chars = array (
//        '\u0430' => 'а', '\u0410' => 'А',
//        '\u0431' => 'б', '\u0411' => 'Б',
//        '\u0432' => 'в', '\u0412' => 'В',
//        '\u0433' => 'г', '\u0413' => 'Г',
//        '\u0434' => 'д', '\u0414' => 'Д',
//        '\u0435' => 'е', '\u0415' => 'Е',
//        '\u0451' => 'ё', '\u0401' => 'Ё',
//        '\u0436' => 'ж', '\u0416' => 'Ж',
//        '\u0437' => 'з', '\u0417' => 'З',
//        '\u0438' => 'и', '\u0418' => 'И',
//        '\u0439' => 'й', '\u0419' => 'Й',
//        '\u043a' => 'к', '\u041a' => 'К',
//        '\u043b' => 'л', '\u041b' => 'Л',
//        '\u043c' => 'м', '\u041c' => 'М',
//        '\u043d' => 'н', '\u041d' => 'Н',
//        '\u043e' => 'о', '\u041e' => 'О',
//        '\u043f' => 'п', '\u041f' => 'П',
//        '\u0440' => 'р', '\u0420' => 'Р',
//        '\u0441' => 'с', '\u0421' => 'С',
//        '\u0442' => 'т', '\u0422' => 'Т',
//        '\u0443' => 'у', '\u0423' => 'У',
//        '\u0444' => 'ф', '\u0424' => 'Ф',
//        '\u0445' => 'х', '\u0425' => 'Х',
//        '\u0446' => 'ц', '\u0426' => 'Ц',
//        '\u0447' => 'ч', '\u0427' => 'Ч',
//        '\u0448' => 'ш', '\u0428' => 'Ш',
//        '\u0449' => 'щ', '\u0429' => 'Щ',
//        '\u044a' => 'ъ', '\u042a' => 'Ъ',
//        '\u044b' => 'ы', '\u042b' => 'Ы',
//        '\u044c' => 'ь', '\u042c' => 'Ь',
//        '\u044d' => 'э', '\u042d' => 'Э',
//        '\u044e' => 'ю', '\u042e' => 'Ю',
//        '\u044f' => 'я', '\u042f' => 'Я',
// 
//        '\r' => '',
//        '\n' => '<br />',
//        '\t' => ''
//    );
// 
//    foreach ($cyr_chars as $cyr_char_key => $cyr_char) {
//        $json_str = str_replace($cyr_char_key, $cyr_char, $json_str);
//    }
//    return $json_str;
//}
    public function Get_json_chains() {

    $chains = DB::select('select * from chains_aoid_view');
    //$chains = DB::table('chains_aoid_view')->get();//->paginate(100); 
    //$chains = DB::table('chains_view')->select('id','c_name')->get();//->paginate(100); 
    

//        $ch_status = array(
//            'OPENED' => 'Открыт',
//            'CLOSED' => 'Закрыт',
//        );        
  
        //$users = User::select('id','name')->get();//::all();

//        $chains = Chain::paginate(100);//all();
        //$chains = DB::table('chains')->get();
//        $data = [];
//        foreach ($chains as $row=>$chain){
//            $categories = '';
//            if ($chain->cat_names!='NULL') {
//            foreach (explode(",",$chain->cat_names) as $cat_name) {
//                if($cat_name != 'NULL')
//                    $categories = $categories.'<li>'.rtrim($cat_name, ", ").'</li>';
//            }
//            }
//            
//            $usr_name = '';
//            $usr = $users->find($chain->operator_id);
//            if ($usr) $usr_name = $usr->name;
//                
//            $clt_adr = '';
//            if ($chain->address) $clt_adr = $chain->address;
////            else { 
////                $clt_adr = DB::select("select title from public.clt_adr_area_view where clt_adr_area_view.address_id = ? limit 1",[$chain->address_id]);
////                if ($clt_adr) $clt_adr = $clt_adr[0]->title;
////            }
//            
//            
//            array_push($data, 
//              array(
//                $chain->id,
//                date('Y.m.d H:i',$chain->update_time),
////                '<a href="'.route('clients.view', ['id' => $chain->client_id]).'">'.$chain->surname.' '.$chain->c_name.' '.$chain->patronymic.'</a>',
//                "",
//                $clt_adr,
//                $chain->u_name,
//                $usr_name,
//                $ch_status[$chain->status],
//                date('Y.m.d H:i',$chain->opening_time),
//                '<a href="'.route('chains.view', ['id' => $chain->id]).'">'.$chain->last_comment.'</a>',
//                $categories
//              )
//            );
//        }
//        return ['data'=>$data];

        $chainsDetails = DataTables::collection($chains)
//            ->addColumn('action', function ($bookings) {
//                return '<a href="/bookings/'.$bookings->_id.'" class="btn btn-xs btn-danger deleteEvent" data-id="'.$bookings->_id.'"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
//            })
                ->addColumn('utime', function ($chains) {
                    return date('Y.m.d H:i', $chains->update_time);
                })
                ->addColumn('clt_adr', function ($chains) {
                    $clt_adr = '';
                    if ($chains->address)
                        $clt_adr = $chains->address;
                    return $clt_adr;
                })
                ->addColumn('operator', function ($chains) {
                    $usr_name = $chains->operator_id;
//                    foreach ($users as $user) {
//                        if ($chains->operator_id === $user->id) $usr_name = $user->name;
//                    }
                    return $usr_name;
                })
//                ->addColumn('ch_status', function ($chains) {
//                    return $ch_status[$chains->status];
//                })
                ->addColumn('otime', function ($chains) {
                    return date('Y.m.d H:i', $chains->opening_time);
                })
                ->addColumn('action_view', function ($chains) {
                    return '<a href="' . route('chains.view', ['id' => $chains->id]) . '">' . $chains->last_comment . '</a>';
                })
                ->addColumn('categories', function ($chains) {
                    $categories = '';
                    if ($chains->cat_names != 'NULL') {
                        foreach (explode(",", $chains->cat_names) as $cat_name) {
                            if ($cat_name != 'NULL')
                                $categories = $categories . '<li>' . rtrim($cat_name, ", ") . '</li>';
                        }
                    }
                    return ($categories);
                })
                ->rawColumns(['categories','action_view'])
                ->make(true);

        return $chainsDetails;    
    
//    return response()->json($chains->toJson());
    }
    
    public function index4clt(Request $request, $clt_id) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') && !$request->user()->hasRole('Учителя') ) { 
            return redirect('forbidden');
        }  
        
        
//        return view('tsupport.chains', [
//            'chains' => $chains,
//            'users' => $users,
//            'ch_status' =>  $ch_status,
//        ]);
        $usr_id = '';
        $users = User::select('id','name')->get();
        
        return view('tsupport.chains',compact('clt_id','usr_id','users'));
    }
    
    
    public function Get_json_chains4clt($clt_id) {

    $chains = DB::table('chains_aoid_view')->where('client_id', '=', $clt_id)->get();

//        $ch_status = array(
//            'OPENED' => 'Открыт',
//            'CLOSED' => 'Закрыт',
//        );        
  
        //$users = User::select('id','name')->get();//::all();

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
                date('Y.m.d H:i',$chain->update_time),
//                '<a href="'.route('clients.view', ['id' => $chain->client_id]).'">'.$chain->surname.' '.$chain->c_name.' '.$chain->patronymic.'</a>',
                $chain->address,
                $chain->u_name,
                //$users->find($chain->operator_id)->name,
                $chain->operator_id,
                //$ch_status[$chain->status],
                $chain->status,
                date('d.m.y H:i',$chain->opening_time),
                '<a href="'.route('chains.view', ['id' => $chain->id]).'">'.$chain->last_comment.'</a>',
                $categories
              )
            );
        }
        return ['data'=>$data];
    }    

    public function index4usr(Request $request, $usr_id) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') && !$request->user()->hasRole('Учителя') ) { 
            return redirect('forbidden');
        }  
        
        
//        return view('tsupport.chains', [
//            'chains' => $chains,
//            'users' => $users,
//            'ch_status' =>  $ch_status,
//        ]);
        $clt_id = '';
        $users = User::select('id','name')->get();
        
        return view('tsupport.chains',compact('clt_id','usr_id','users'));
    }
    
    
    public function Get_json_chains4usr($usr_id) {

    $chains = DB::table('chains_aoid_view')->where('user_id', '=', $usr_id)->get();

//        $ch_status = array(
//            'OPENED' => 'Открыт',
//            'CLOSED' => 'Закрыт',
//        );        
  
       // $users = User::select('id','name')->get();//::all();

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
                date('Y.m.d H:i',$chain->update_time),
//                '<a href="'.route('clients.view', ['id' => $chain->client_id]).'">'.$chain->surname.' '.$chain->c_name.' '.$chain->patronymic.'</a>',
                $chain->address,
                $chain->u_name,
                //$users->find($chain->operator_id)->name,
                  $chain->operator_id,
                //$ch_status[$chain->status],
                  $chain->status,
                date('d.m.y H:i',$chain->opening_time),
                '<a href="'.route('chains.view', ['id' => $chain->id]).'">'.$chain->last_comment.'</a>',
                $categories
              )
            );
        }
        return ['data'=>$data];
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

    //var_dump($chains_items); exit;
    
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

