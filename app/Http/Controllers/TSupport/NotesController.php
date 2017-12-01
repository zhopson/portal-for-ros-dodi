<?php

namespace App\Http\Controllers\TSupport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Note;
use App\Chain;
use App\Client;
use App\ChainItems;
use App\ChainUser;
use App\User;

class NotesController extends Controller
{
    public function note_new($id, $ch_id = null) {

        //$users = User::select('id', 'name')->get();
        
        //$addresses = DB::select("select get_address_by_code(address_id) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        //$addresses = DB::select("select address_aoid,get_address_by_aoid(address_aoid) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        $client_id = $id;
        
        return view('tsupport.note_new', compact('client_id','ch_id'));
    }

    public function note_store(Request $request,$id) {
        
        $body = $request->input('v_note_new_body');
        $chain_id = $request->input('v_note_new_chain');
       
        $user_id =  $request->user()->id;
        $contract_id = Client::find($id)->contract_id;
        $client_id = $id;
        $stripped = $body;
        //var_dump($today); exit;
        $new_note = Note::Create(compact('chain_id','user_id','client_id','body','stripped','contract_id'));
            
        $chain = Chain::find($chain_id);
            
        $note_id = $new_note->id;
        $message = $body;
        
        $new_chain_items = ChainItems::Create(compact('chain_id','user_id','note_id','message'));
        
        $chain->last_item_id = $new_chain_items->id;
        $chain->last_comment = $message;
        $chain->save();
        
        $new_ch_usr = ChainUser::updateOrCreate(compact('chain_id','user_id'), compact('chain_id','user_id'));
        //var_dump('cAT:'. $category.'; otv:'.$otvetstv.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_d.'; srok:'.$srok_d.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$msg.'; op_ch:'.$open_chains);
        return redirect()->route('chains.view', ['id' => $chain_id]);
    }
    
    public function note_edit($id) {

        $note = Note::find($id);
        $client_id = $note->client_id;
        $ch_id = $note->chain_id;
        $body = $note->body;
        
        //$users = User::select('id', 'name')->get();
        
        //$addresses = DB::select("select get_address_by_code(address_id) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        //$addresses = DB::select("select address_aoid,get_address_by_aoid(address_aoid) as adr,address_number,address_building,address_apartment,'['||substring(creation_time::text from 0 for 11)||']' as date from postals where client_id=? order by creation_time desc",[$id]);
        
        return view('tsupport.note_edit', compact('client_id','ch_id','body','id'));
    }
    
    public function note_update(Request $request,$id) {
        
        $body = $request->input('v_note_edit_body');
        $chain_id = $request->input('v_note_edit_chain');
       
        $user_id =  $request->user()->id;

        $note = Note::find($id);

        $chain_items_id = ChainItems::select('id')->where('note_id', '=', $id)->first();
        //var_dump($chain_items_id->id); exit;
        $note_owner = User::find($note->user_id);
        
        if ($note->user_id !== $user_id) {
            $errors = ["Изменение заметки запрещено", "Только автор (".$note_owner->name.") может редактировать заметку!"];
            return \Redirect::back()->withInput()->withErrors($errors);
        }
        
        $stripped = $body;

        $note->body = $body;
        $note->stripped = $body;
        $note->save();
       
        $chain_items = ChainItems::find($chain_items_id->id);
        $chain_items->message = $body;
        $chain_items->save();
        
        $chain = Chain::find($chain_id);
        $chain->last_comment = $body;
        $chain->last_item_id = $chain_items_id->id;
        $chain->save();
        
        //var_dump('cAT:'. $category.'; otv:'.$otvetstv.'; st:'.$status.'; prior:'.$priority.'; start:'.$start_d.'; srok:'.$srok_d.'; progr'.$progress.'; dep:'.$departure.'; msg:'.$msg.'; op_ch:'.$open_chains);
        return redirect()->route('chains.view', ['id' => $chain_id]);
    }    
    
    
}
