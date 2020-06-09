<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Input;
use Hash;

use App\User;
use App\Role;
use App\UserRole;

class UsersGroupsController extends Controller
{

    public function index() {

        //$users = User::select('id','name','email','is_admin','client_id','sip_number','sip_secret')->get();//::all();
        $roles = Role::all();

//        Role::create(['role' => 'Ученики']);        
//        Role::create(['role' => 'Учителя']);        
//        Role::create(['role' => 'Сотрудники ТП ИНТ']);        
//        Role::create(['role' => 'Сотрудники ТП РОС']);        
//        Role::create(['role' => 'Сотрудники ТП ГБУ РЦИТ']);        
//        Role::create(['role' => 'Руководство ГБУ РЦИТ']);        

        return view('admin.users',compact('roles'));
        
    }
    
    public function Get_json_users($gr_id = null) {
        $users ='';
        if ($gr_id === '1' || $gr_id === null)  
            $users = User::select('id','name','email','created_at','is_admin','client_id','sip_number','sip_secret')->get();
        else $users = Role::find($gr_id)->users;
        
    //$chains = DB::table('chains_view')->select('id','c_name')->get();//->paginate(100); 

        $data = [];
        foreach ($users as $row=>$user){
            $isadmin = '';
            if ($user->is_admin) $isadmin = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
            
            $dt = '';
            foreach ((object)($user->created_at) as $key=>$value) { $dt = $value; break; }
            
            array_push($data, 
              array(
                $user->id,
                '<a data-toggle="tooltip" title="Редактировать данные пользователя" href="'.route('admin.users.edit', ['id' => $user->id]).'">'.$user->name.'</a>',
                $user->email,
                substr($dt, 0, 16),
                $isadmin,
                $user->sip_number
              )
            );
        }
        return ['data'=>$data];
        
//    return response()->json($chains->toJson());
    }
    
    public function new_usr() {
        $roles = Role::all();
        return view('admin.user_new',compact('roles'));
        
    }

    public function store_usr(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
//            'sip_name' => 'string|max:255',
//            'sip_password' => 'string|min:6',
        ]);
        
	if ($validator->fails()) {
            //var_dump($validator->messages());
            return \Redirect::back()->withInput()->withErrors($validator);
        }
        
        $is_adm = false;
        if ($request->input('admin')==='on') $is_adm  = true; 
        
        if ($is_adm === true) {
            $flag = false;
            foreach ($request->input('v_usr_roles') as $role_id) { 
                if ($role_id == 4 or $role_id == 5 or $role_id == 6) { $flag = true; break;}
            }
            if ($flag === false) {
                $errors = ["Ошибка", "Администраторами могут быть только сотрудники Техподдержки!"];
                return \Redirect::back()->withInput()->withErrors($errors);                
            }
        }

        $sip_name = '0';
        if ($request->input('sip_name')) $sip_name = $request->input('sip_name');        
        
        //var_dump($is_adm); exit;
        $new_user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'is_admin' => $is_adm,
            'sip_number' => $sip_name,
            'sip_secret' => $request->input('sip_password'),
        ]);
        
        //$gr = '';
        $user_id = $new_user->id;
        if ($request->input('v_usr_roles')) {
            foreach ($request->input('v_usr_roles') as $role_id) { 
                //$gr=$gr.$values.','; 
                UserRole::updateOrCreate(compact('user_id','role_id'), compact('user_id','role_id'));
            }
            //$gr = rtrim($gr,',');
        }        
        
        $roles = Role::all();
        //return view('admin.user_new',compact('roles'));
       return view('admin.users',compact('roles')); 
    }

    public function edit_usr($id) {
        $roles = Role::all();
        $user = User::find($id);
        return view('admin.user_edit',compact('roles','user'));
        
    }

    public function update_usr(Request $request,$id) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
//            'sip_name' => 'string|max:255',
//            'sip_password' => 'string|min:6',
        ]);
        
	if ($validator->fails()) {
            //var_dump($validator->messages());
            return \Redirect::back()->withInput()->withErrors($validator);
        }
        
        $is_adm = false;
        if ($request->input('admin')==='on') $is_adm  = true; 

        if ($is_adm === true) {
            $flag = false;
            foreach ($request->input('v_usr_roles') as $role_id) { 
                if ($role_id == 4 or $role_id == 5 or $role_id == 6) { $flag = true; break;}
            }
            if ($flag === false) {
                $errors = ["Ошибка", "Администраторами могут быть только сотрудники Техподдержки!"];
                return \Redirect::back()->withInput()->withErrors($errors);                
            }
        }
        
        $sip_name = '0';
        if ($request->input('sip_name')) $sip_name = $request->input('sip_name');
        
        $user = User::find($id);
        $user->name = $request->input('name');

        //if (!Hash::check($request->input('password'), $user->password, [])) {
        if ( $request->input('password') !== $request->input('password_old') ) {
            $user->password = bcrypt($request->input('password'));
            $user->remember_token = null;
        }

        $user->is_admin = $is_adm;
        $user->sip_number = $sip_name;
        $user->sip_secret = $request->input('sip_password');
        $user->save();
        
        //var_dump($is_adm); exit;
        
        //$gr = ''; [['clients_id', '=', $id],['groups_id', '=', $gr_id]]
        $grps = UserRole::where('user_id', '=', $id)->delete();
        
        $user_id = $id;
        if ($request->input('v_usr_roles')) {
            foreach ($request->input('v_usr_roles') as $role_id) { 
                //$gr=$gr.$values.','; 
                UserRole::updateOrCreate(compact('user_id','role_id'), compact('user_id','role_id'));
            }
            //$gr = rtrim($gr,',');
        }        
        
        $roles = Role::all();
        //return view('admin.user_new',compact('roles'));
       return view('admin.users',compact('roles')); 
    }    
    
}
