<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Contact;

class ContactsController extends Controller
{
    
    public function index(Request $request) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') ) { 
            return redirect('forbidden');
        }  

        $add2exist_chain_id = '';
        
        return view('contacts.contacts', [
            'add2exist_chain_id' => $add2exist_chain_id,
            //'chains_opened' => $chains_opened,
        ]);
    }    
    
    public function Get_json_contacts(Request $request) {

    $contacts = DB::table('contacts_view')->get();//->paginate(100); 
    //$chains = DB::table('chains_view')->select('id','c_name')->get();//->paginate(100); 

        $isTeacher = $request->user()->hasRole('Учителя');
        $data = [];
        foreach ($contacts as $row=>$contact){
            
            $adr = '';
            
            $nums_str = '';
            foreach (explode("\n",$contact->phones) as $number) {
                $num_arr = (explode(":",$number));
                $clt_name_clear =  str_replace('"', '', $contact->clt_name);
                if ($isTeacher) 
                    $nums_str = $nums_str.$num_arr[0];
                else
                    $nums_str = $nums_str."<a href=\"JavaScript:call_client('".$contact->id."','".$clt_name_clear."','".$num_arr[0]."');\">".$num_arr[0]."</a>";
                
                if ( isset($num_arr[1]) &&  trim($num_arr[1])!='' )
                    { $nums_str = $nums_str.'('.trim($num_arr[1]).'), '; }
                else 
                    { $nums_str = $nums_str.','; }
                                            
            }
            $nums_str = rtrim($nums_str, ", "); 

            $contact_edit_tag = '';
            
            if (!$isTeacher) 
                $contact_edit_tag = '<a href="'.route('contacts.edit', ['id' => $contact->id]).'"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
                                
            array_push($data, 
              array(
                //$contact->id,
                $contact->clt_name,
                $contact->address,
                $contact->job,
                $contact->post,
                $contact->email,
                $nums_str,
                $contact_edit_tag,
              )
            );
        }
        return ['data'=>$data];
        
//    return response()->json($chains->toJson());
    }        
    
 public function cnt_new() {
     
        $cnt_types  = array(
            'PERSON' => 'Сотрудник',
            'ORGANIZATION' => 'Организация',
        );     

        $regions = DB::connection('pgsql_adr')->table('fias_addressobjects')
                ->select('shortname','offname','aoguid')
                ->whereNull('parentguid')
                ->get();        
        
        return view('contacts.cont_new',compact('cnt_types','regions'));
 }       
  
 public function contacts_store(Request $request) {

        $type = $request->input('v_cnt_type');

        if ($type == 'PERSON') {
            $name = $request->input('v_cnt_name');
            $surname = $request->input('v_cnt_surname');
            $patronymic = $request->input('v_cnt_otch');
            $job = $request->input('v_cnt_placejob');
            $post = $request->input('v_cnt_postjob');
        }
        if ($type == 'ORGANIZATION') {
            $org = $request->input('v_cnt_org');
        }

        $address_postal = $request->input('v_cnt_new_adr_ind');
        $adr_region = $request->input('v_cnt_new_adr_region');
        $adr_raion = $request->input('v_cnt_new_adr_raion');
        $adr_city = $request->input('v_cnt_new_adr_city');
        $adr_np = $request->input('v_cnt_new_adr_np');
        $adr_st = $request->input('v_cnt_new_adr_st');
        $address_number = $request->input('v_cnt_new_adr_dom');
        $address_building = $request->input('v_cnt_new_adr_korp');
        $address_apartment = $request->input('v_cnt_new_adr_kv');
        $contacts_tel = $request->input('v_cnt_new_contacts_tel');
        $contacts_name = $request->input('v_cnt_new_contacts_name');
        $email = $request->input('v_cnt_new_contacts_mail');
        $skype = $request->input('v_cnt_new_contacts_skype');
        
        if (!$address_postal) $address_postal = '';
        if (!$address_number) $address_number = '';
        if (!$address_building) $address_building = '';
        if (!$address_apartment) $address_apartment = '';

        $address_aoid = '';
        $address_id = '';
        $adr_param = '';

        if (  $adr_region !=='0' ) {
            
            if (  $adr_st !== '0' ) $address_aoid = $adr_st;
            else {
                if (  $adr_np !== '0' ) $adr_param = $adr_np;
                else {
                    if (  $adr_city !== '0' ) $adr_param = $adr_city;
                    else {
                        if (  $adr_raion !== '0' ) $adr_param = $adr_raion;
                        else $adr_param = $adr_region;
                    }
                }
                $address_aoid = DB::connection('pgsql_adr')->select("SELECT aoid FROM fias_addressobjects where aoguid = ? limit 1", [$adr_param])[0]->aoid;
            }
            
            $address_id = DB::connection('pgsql_adr')->select("SELECT code FROM fias_addressobjects where aoid = ? limit 1", [$address_aoid])[0]->code;
        }
        
        $phones = '';
        $n = 1;
        while ( $n <= 15 ) {
            if ($request->input('v_cnt_new_contacts_tel' . $n)) {
                $phones = $phones . $request->input('v_cnt_new_contacts_tel' . $n) . ':';
                if ($request->input('v_cnt_new_contacts_name' . $n))
                    $phones = $phones . $request->input('v_cnt_new_contacts_name' . $n) . PHP_EOL;
                else
                    $phones = $phones . PHP_EOL;
            }
            $n++;
        }
        $phones = rtrim($phones,PHP_EOL);
        
        $new_cnt = '';
        if ($type == 'PERSON') {
            $new_cnt = Contact::create(compact('address_id', 'type', 'surname', 'name', 'patronymic', 'phones', 'email', 'job', 'post','address_postal','address_number','address_building','address_apartment','skype','address_aoid'));
        } else {
            $new_clt = Contact::create([
                'address_id' => $address_id,
                'name' => $org,
                'type' => $type,
                'phones' => $phones,
                'email' => $email,
                'address_postal' => $address_postal,
                'address_number' => $address_number,
                'address_building' => $address_building,
                'address_apartment' => $address_apartment,
                'skype' => $skype,
                'address_aoid' => $address_aoid,
            ]);            
        }

        
        $add2exist_chain_id = '';
        
//        return view('contacts.contacts', [
//            'add2exist_chain_id' => $add2exist_chain_id,
//            //'chains_opened' => $chains_opened,
//        ]);
        
        return redirect()->route('contacts');
    
 }


 public function contacts_edit(Request $request,$id) {
     
//        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') ) { 
////            $clt_id = $request->user()->client_id;
////            if ($clt_id != $id) { return redirect('forbidden');}
//            return redirect('forbidden');
//        }  

        $new_cnt = Contact::find($id);
        
        $address_components = '';
        $raions = '';
        
        $regions = DB::connection('pgsql_adr')->table('fias_addressobjects')
                ->select('shortname','offname','aoguid')
                ->whereNull('parentguid')
                ->get();        

        if ($new_cnt->address_aoid) {
            
            $address_components = DB::select("select * from public.get_address_components_by_guid(?) order by taolevel",[$new_cnt->address_aoid]);
        
            $city_guid = ''; $np_guid = ''; $st_guid = ''; $region_guid = '';
            
            foreach ($address_components as $component) { 
                if ($component->taolevel == 1) { $region_guid = $component->tparentguid; }
                //else if ($component->taolevel == 3) { $raion_guid = $component->tparentguid; }
                else if ($component->taolevel == 4) { $city_guid = $component->tparentguid; }
                else if ($component->taolevel == 6) { $np_guid = $component->tparentguid; }
                else { $st_guid = $component->tparentguid; }
            }            
            $raions = DB::connection('pgsql_adr')->table('fias_addressobjects')->select('shortname','offname','aoguid')->where([['parentguid','=',$regions[0]->aoguid],['currstatus','=','0'],['aolevel','=','3']])->get();        
            $cities = DB::connection('pgsql_adr')->table('fias_addressobjects')->select('shortname','offname','aoguid')->where([['parentguid','=',$city_guid],['currstatus','=','0'],['aolevel','=','4']])->get();        
            $nps = DB::connection('pgsql_adr')->table('fias_addressobjects')->select('shortname','offname','aoguid')->where([['parentguid','=',$np_guid],['currstatus','=','0'],['aolevel','=','6']])->get();        
            $sts = DB::connection('pgsql_adr')->table('fias_addressobjects')->select('shortname','offname','aoid')->where([['parentguid','=',$st_guid],['currstatus','=','0'],['aolevel','>','6']])->get();        
            
        }
        //$new_clt =  session('new_clt');
        //var_dump($address_components);exit;
        
        return view('contacts.cont_edit',compact('new_cnt','regions','raions','cities','nps','sts','address_components'));
 } 
 
    public function contacts_update(Request $request,$id ) {
        
        //var_dump($request->old('v_cnt_new_surname'));exit;
        $cnt = Contact::find($id);
        
        $surname = $request->input('v_cnt_edit_surname');
        $name = $request->input('v_cnt_edit_name');
        $otch = $request->input('v_cnt_edit_otch');
        $org = $request->input('v_cnt_edit_org');
        $adr_ind = $request->input('v_cnt_edit_adr_ind');
        $adr_region = $request->input('v_cnt_edit_adr_region');
        $adr_raion = $request->input('v_cnt_edit_adr_raion');
        $adr_city = $request->input('v_cnt_edit_adr_city');
        $adr_np = $request->input('v_cnt_edit_adr_np');
        $adr_st = $request->input('v_cnt_edit_adr_st');
        $adr_dom = $request->input('v_cnt_edit_adr_dom');
        $adr_korp = $request->input('v_cnt_edit_adr_korp');
        $adr_kv = $request->input('v_cnt_edit_adr_kv');
        $contacts_tel = $request->input('v_cnt_edit_contacts_tel');
        $contacts_name = $request->input('v_cnt_edit_contacts_name');
        $contacts_mail = $request->input('v_cnt_edit_contacts_mail');
        $contacts_skype = $request->input('v_cnt_edit_contacts_skype');
        $contacts_job = $request->input('v_cnt_edit_placejob');
        $contacts_post = $request->input('v_cnt_edit_postjob');
//        if ($default_ip_ind == 1)
//        var_dump($default_ip_ind);
//        exit;

        if (!$adr_ind) $adr_ind = '';
        if (!$adr_dom) $adr_dom = '';
        if (!$adr_korp) $adr_korp = '';
        if (!$adr_kv) $adr_kv = '';
        
        $cnt->surname = $surname;
        $cnt->name = $name;
        $cnt->patronymic = $otch;
        $cnt->email = $contacts_mail;
        $cnt->skype = $contacts_skype;
        $cnt->job = $contacts_job;
        $cnt->post = $contacts_post;
        if ($cnt->type !== 'PERSON') {$cnt->name = $org; $cnt->surname = ''; $cnt->patronymic = '';}
        $cnt->address_postal = $adr_ind;
        
        $address_aoid = ''; $adr_param = '';
        
        if (  $adr_region !=='0' ) {
            
            if (  $adr_st !== '0' ) $address_aoid = $adr_st;
            else {
                if (  $adr_np !== '0' ) $adr_param = $adr_np;
                else {
                    if (  $adr_city !== '0' ) $adr_param = $adr_city;
                    else {
                        if (  $adr_raion !== '0' ) $adr_param = $adr_raion;
                        else $adr_param = $adr_region;
                    }
                }
                $address_aoid = DB::connection('pgsql_adr')->select("SELECT aoid FROM fias_addressobjects where aoguid = ? limit 1", [$adr_param])[0]->aoid;
            }
        }
                
        if (  ( $cnt->address_aoid!=$address_aoid || $cnt->address_number!=$adr_dom || $cnt->address_building!=$adr_korp || $cnt->address_apartment!=$adr_kv ) && $adr_region !=='0'  ) {
            
                $address_id = DB::connection('pgsql_adr')->select("SELECT code FROM fias_addressobjects where aoid = ? limit 1", [$address_aoid])[0]->code;
                
                $cnt->address_number = $adr_dom;
                $cnt->address_building = $adr_korp;
                $cnt->address_apartment = $adr_kv;
                $cnt->address_aoid = $address_aoid;
                $cnt->address_id = $address_id;
//            else 
//                return \Redirect::back()->withInput()->withErrors($errors = ["Некоторые поле адреса не заполнены!"]);
        }
        
        $phone_book = '';
        $n = 1;
        while ( $n <= 15 ) {
            if ($request->input('v_cnt_edit_contacts_tel' . $n)) {
                $phone_book = $phone_book . $request->input('v_cnt_edit_contacts_tel' . $n) . ':';
                if ($request->input('v_cnt_edit_contacts_name' . $n))
                    $phone_book = $phone_book . $request->input('v_cnt_edit_contacts_name' . $n) . PHP_EOL;
                else
                    $phone_book = $phone_book . PHP_EOL;
            }
            $n++;
        }
        $phone_book = rtrim($phone_book,PHP_EOL);
        
        $cnt->phones = $phone_book;

        $cnt->save();
        
        return redirect()->route('contacts');
        //return redirect()->route('clients.view', ['id' => $id]);
        //return redirect()->route('clients.edit', ['id' => $id])->with('status', 'Изменения сохранены!');   
    }  
 
 
}
