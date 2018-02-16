<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;
use App\DocumentCategories;

class WikiController extends Controller {

    public function index(Request $request) {

        if (!$request->user()->hasRole('Сотрудники ТП РОС')) {
            return redirect('forbidden');
        }

//        $add2exist_chain_id = '';
//        return view('contacts.contacts', [
//            'add2exist_chain_id' => $add2exist_chain_id,
//            //'chains_opened' => $chains_opened,
//        ]);

        return view('wiki.index_wiki');
    }

    public function Get_json_docs_list(Request $request) {

        $documents = DB::table('documents')->get(); //->paginate(100); 
        //$chains = DB::table('chains_view')->select('id','c_name')->get();//->paginate(100); 

        $data = [];
        foreach ($documents as $row => $document) {

            $document_edit_tag = '';
            $document_del_tag = '';

//            if (!$isTeacher) 
//                $contact_edit_tag = '<a href="'.route('contacts.edit', ['id' => $document->id]).'"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';

            $document_edit_tag = '<a href="' . route('documents.edit', ['id' => $document->id]) . '"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';
            $document_del_tag = '<a href="' . route('documents.remove', ['id' => $document->id]) . '"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a>';

            array_push($data, array(
                $document->id,
                $document->category_id,
                $document->header,
                $document->update_time,
                $document_edit_tag,
                $document_del_tag,
                    )
            );
        }
        return ['data' => $data];

//    return response()->json($chains->toJson());
    }

    public function doc_new(Request $request) {

        if (!$request->user()->hasRole('Сотрудники ТП РОС')) {
            return redirect('forbidden');
        }

        $doc_categories = DocumentCategories::all();
//        $add2exist_chain_id = '';

        return view('wiki.doc_new', [
            'doc_categories' => $doc_categories,
                //'chains_opened' => $chains_opened,
        ]);
    }

    public function do_ajax_upload_file(Request $request) {
        if ($request->ajax()) {

            //$file_obj = $request->input('file_object');

            //var_dump($file_obj); exit;
            
//            $dir = '/attachments' . date('/Y/m/d/');

//            do {
//                $filename = str_random(30) . '.txt';
//            } while (File::exists(public_path() . $dir . $filename));

//            $request->file('file_object')->move(public_path() . $dir, $filename);

            if (!$request->hasFile('file_object')) {
//                return Response::json(['status' => 0]);
                return 'error';
            }
            
            $file = $request->file('file_object');
            //$file = $request->file_object;
            
            $path = $file->store('attachments');
            
            //return Response::json(array('filelink' => $dir.$filename));
            return Response::json(['filepath' => $path, 'status' => 1]);
//            return Response::json(['adr_arr' => $adr_arr, 'city_arr' => $city_arr, 'punkt_arr' => $punkt_arr, 'st_arr' => $st_arr, 'status' => 1]);
            //return Response::json(['rname' => $rname, 'adr_part' => $adr_part, 'status' => 1]);
        }
        return 'error';
    }

    public function doc_store(Request $request) {
        
        $category = $request->input('v_cat_new_doc');
        $header = $request->input('v_head_new_doc');
        $description = $request->input('v_desc_new_doc');

        $category_id = '';
        
        if ($category!=null) {
            $category_id = $category;
        }

        //var_dump($today); exit;
        $new_doc = Document::Create(compact('category_id','header','description'));
        
        $document_id  = $new_doc.id;
  
            $n = 1;
        while ( $n <= 15 ) {
            if ($request->input('v_tdfile_new_doc' . $n)) {
                $path = '';
                $visible_filename = '';
                $type = '';
                $description = '';
                $path = $request->input('v_tdfile_new_doc' . $n);
                $visible_filename = $request->input('v_tdvfile_new_doc' . $n);
                if ($request->input('v_tdtype_new_doc' . $n))
                    $type = $request->input('v_tdtype_new_doc' . $n);
                if ($request->input('v_tddesc_new_doc' . $n))
                    $description = $request->input('v_tddesc_new_doc' . $n);
                
                $new_attach = DocumentAttach::Create(compact('document_id','description','path','type','visible_filename'));
            }
            $n++;
        }        
        
        return redirect()->route('documents');
    }    
    
}
