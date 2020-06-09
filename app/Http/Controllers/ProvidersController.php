<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Provider;

class ProvidersController extends Controller
{
    
    public function index(Request $request) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') ) { 
            return redirect('forbidden');
        }  
        
        return view('providers');
    }    
    
    public function Get_json_providers(Request $request) {

        $providers = Provider::all();

        $data = [];
        foreach ($providers as $row=>$provider){
            
            $provider_edit_tag = '';
            $provider_edit_tag = '<a href="javascript:Edit_provider('.$provider->id.')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a>';
            
            $provider_watched = '';
            if ($provider->watch === 1) 
                $provider_watched = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                                
            array_push($data, 
              array(
                $provider->name,
                $provider_watched,
                $provider_edit_tag,
              )
            );
        }
        return ['data'=>$data];
        
    }
    
 public function prd_add(Request $request) {
    if ($request->ajax()) {
        $name = $request->input('name');
        $watched = $request->input('watch');
        
        $watch = 0;
        if ($watched==='on') $watch  = 1;

        $new_prd = Provider::create(compact('name', 'watch'));

        return Response::json(['id' => $new_prd->id, 'status' => 1]);
    }
    return 'error';   
 }    

  public function prd_edit(Request $request) {
    if ($request->ajax()) {
        $id = $request->input('id');
        
        $prd = Provider::find($id);

        return Response::json(['id' => $prd->id, 'name' => $prd->name, 'watch' => $prd->watch, 'status' => 1]);
    }
    return 'error';   
 } 
 
  public function prd_save(Request $request) {
    if ($request->ajax()) {
        $id = $request->input('id');
        
        $name = $request->input('name');
        $watched = $request->input('watch');
        
        $watch = 0;
        if ($watched==='on') $watch  = 1;
        
        $prd = Provider::find($id);
        
        $prd->name = $name;
        $prd->watch = $watch;
        $prd->save();

        return Response::json(['status' => 1]);
    }
    return 'error';   
 } 
    
    
}
