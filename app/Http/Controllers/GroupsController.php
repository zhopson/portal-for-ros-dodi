<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Group;

class GroupsController extends Controller
{
    public function index(Request $request) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') ) { 
            return redirect('forbidden');
        }  
        
        return view('groups');
    }    
    
    public function Get_json_groups() {

        $groups = Group::all();

        $data = [];
        foreach ($groups as $row=>$group){
            
            $group_edit_tag = '';
            $group_edit_tag = '<a href="javascript:Edit_group('.$group->id.')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a>';
            
                                
            array_push($data, 
              array(
                  '',
                $group->name,
                $group_edit_tag,
              )
            );
        }
        return ['data'=>$data];
        
    }
    
 public function grp_add(Request $request) {
    if ($request->ajax()) {
        $name = $request->input('name');
        
        $new_grp = Group::create(compact('name'));

        return Response::json(['id' => $new_grp->id, 'status' => 1]);
    }
    return 'error';   
 }    

  public function grp_edit(Request $request) {
    if ($request->ajax()) {
        $id = $request->input('id');
        
        $grp = Group::find($id);

        return Response::json(['id' => $grp->id, 'name' => $grp->name, 'status' => 1]);
    }
    return 'error';   
 } 
 
  public function grp_save(Request $request) {
    if ($request->ajax()) {
        $id = $request->input('id');
        
        $name = $request->input('name');
        
        $grp = Group::find($id);
        
        $grp->name = $name;
        $grp->save();

        return Response::json(['status' => 1]);
    }
    return 'error';   
 } 
    
}
