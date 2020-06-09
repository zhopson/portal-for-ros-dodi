<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Contract;

class ContractsController extends Controller
{
    //
    public function index(Request $request) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') ) { 
            return redirect('forbidden');
        }  
        
        return view('contracts');
    }    
    
    public function Get_json_contracts(Request $request) {

        $contracts = Contract::all();

        $data = [];
        foreach ($contracts as $row=>$contract){
            
            $contract_edit_tag = '';
            
            $contract_edit_tag = '<a href="javascript:Edit_contract('.$contract->id.')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
                                
            $date1 = date_create($contract->date);
            
            array_push($data, 
              array(
                //$contact->id,
                $contract->partner_name,
                $contract->number,
//                date_format($date1, 'd-m-Y'),
                date_format($date1, 'Y-m-d'),
                $contract->description,
                $contract_edit_tag,
              )
            );
        }
        return ['data'=>$data];
        
//    return response()->json($chains->toJson());
    }       
    
 public function cntr_add(Request $request) {
    if ($request->ajax()) {
        $partner_name = $request->input('name');
        $number = $request->input('num');
        $date = $request->input('date');
        $description = $request->input('desc');
        
        $date1 = date_create($date);
        $title = $number.' от '.date_format($date1, 'd-m-Y').' с '.$partner_name;
        
        
        if (!$description) $description = '';


        $new_cntr = Contract::create(compact('partner_name', 'number', 'date', 'name', 'description', 'title'));

        return Response::json(['id' => $new_cntr->id, 'status' => 1]);
    }
    return 'error';   
 }    

  public function cntr_edit(Request $request) {
    if ($request->ajax()) {
        $id = $request->input('id');
        
//        $date1 = date_create($date);
//        $title = $number.' от '.date_format($date1, 'd-m-Y').' с '.$partner_name;

        $cntr = Contract::find($id);

        return Response::json(['id' => $cntr->id, 'name' => $cntr->partner_name, 'num' => $cntr->number, 'date' => $cntr->date, 'desc' => $cntr->description, 'status' => 1]);
    }
    return 'error';   
 } 
 
  public function cntr_save(Request $request) {
    if ($request->ajax()) {
        $id = $request->input('id');
        
        $partner_name = $request->input('name');
        $number = $request->input('num');
        $date = $request->input('date');
        $description = $request->input('desc');
        
        $date1 = date_create($date);
        $title = $number.' от '.date_format($date1, 'd-m-Y').' с '.$partner_name;
        
        if (!$description) $description = '';        
        
        $cntr = Contract::find($id);
        
        $cntr->partner_name = $partner_name;
        $cntr->number = $number;
        $cntr->date = $date;
        $cntr->description = $description;
        $cntr->title = $title;
        $cntr->save();

        return Response::json(['status' => 1]);
    }
    return 'error';   
 } 
 
}
