<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\DocumentCategories;
use App\Document;
use App\DocumentAttach;

class WikiController extends Controller {
    
    
//    private $file_types = array(
//        'txt' => 1,
//        'word' => array(
//            'doc' => 2,
//            'docx' => 3
//        ),
//        'excel' => array(
//            'xls' => 4,
//            'xlsx' => 5
//        ),
//        'pdf' => 6,
//        'djvu' => 7,
//        'image' => array(
//            'jpg' => 8,
//            'jpeg' => 9,
//            'gif' => 10,
//            'bmp' => 11,
//            'pcx' => 12,
//            'tif' => 13,
//            'tiff' => 14,
//            'png' => 15
//        )
//    );
    
    private $file_types = array(
        'txt' => 1,
        'doc' => 2,
        'docx' => 3,
        'xls' => 4,
        'xlsx' => 5,
        'pdf' => 6,
        'djvu' => 7,
        'jpg' => 8,
        'jpeg' => 9,
        'gif' => 10,
        'bmp' => 11,
        'pcx' => 12,
        'tif' => 13,
        'tiff' => 14,
        'png' => 15,
        'htm' => 16,
        'html' => 17,
        'csv' => 18,
        'rar' => 19,
        'zip' => 20
    );

    private $file_categories = array(
        'txt' => 'Текстовый',
        'csv' => 'Текстовый',
        'doc' => 'Word',
        'docx' => 'Word',
        'xls' => 'Excel',
        'xlsx' => 'Excel',
        'pdf' => 'Acrobat',
        'djvu' => 'Djvu',
        'jpg' => 'Image',
        'jpeg' => 'Image',
        'gif' => 'Image',
        'bmp' => 'Image',
        'pcx' => 'Image',
        'tif' => 'Image',
        'tiff' => 'Image',
        'png' => 'Image',
        'htm' => 'Web',
        'html' => 'Web',
        'rar' => 'Архив',
        'zip' => 'Архив'
    );
    
//    private function translit($s) {
//        $s = (string) $s; // преобразуем в строковое значение
//        $s = strip_tags($s); // убираем HTML-теги
//        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
//        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
//        $s = trim($s); // убираем пробелы в начале и конце строки
//        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
//        $s = strtr($s, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''));
//        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
//        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
//        return $s; // возвращаем результат
//    }

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

    public function Get_json_docs_list(Request $request,$keywords='',$mode=0) {
        
        if ($keywords!=='') {
            $keywords = '%'.str_replace(' ', '%', $keywords).'%';
            if ($mode == 3)
                $documents = DB::select("select * from documents where lower(header) like ? or lower(description) like ?", [$keywords,$keywords]);            
            else if ($mode == 2)
                $documents = DB::select("select * from documents where lower(description) like ?", [$keywords]);            
            else if ($mode == 1)
                $documents = DB::select("select * from documents where lower(header) like ?", [$keywords]);            
        }
        else
            $documents = DB::table('documents')->get(); //->paginate(100); 
        //$chains = DB::table('chains_view')->select('id','c_name')->get();//->paginate(100); 
        $doc_categories = DocumentCategories::all();

        $data = [];
        foreach ($documents as $row => $document) {

            $cat_name = '';
            $cat = $doc_categories->find($document->category_id);
            if ($cat) $cat_name = $cat->name;
            
//            if (!$isTeacher) 
//                $contact_edit_tag = '<a href="'.route('contacts.edit', ['id' => $document->id]).'"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>';

            $document_view_tag = '<a href="' . route('documents.view', ['id' => $document->id]) . '">'.$document->header.'</a>';
            $document_edit_tag = '<a href="' . route('documents.edit', ['id' => $document->id]) . '"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a>';
            $document_del_tag = '<a href="Javascript:AskRemoveDoc(\'' . $document->id . '\')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a>';
            //$document_del_tag = '<a href="' . route('documents.remove', ['id' => $document->id]) . '"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a>';

            array_push($data, array(
                $document->id,
                $cat_name,
                $document_view_tag,
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

//            $file_name_ext_free_len = strlen(substr(strrchr($file_name, "."), 1));
//            if ($file_name_ext_free_len!==0)
//                $file_name_ext_free = substr($file_name,0,strlen($file_name)-strlen($file_name_ext_free_len)-3);
//            else $file_name_ext_free = $file_name;
            
            if (!$request->hasFile('file_object')) {
                return 'error';
            }

            $file_ext = $request->input('file_ext');
            $file_name = $request->input('file_name');
            
            $out_file_name = 'attach'.date('YmdHis').date('U');
            
            //return Response::json(['len' => $out_file_name, 'status' => 1]);
            
            $file = $request->file('file_object');
            //$file = $request->file_object;
            
            if ($file_ext===$file_name)
                $path = $file->storeAs('attachments',$out_file_name.'.att');
            else 
                $path = $file->storeAs('attachments',$out_file_name.'.'.$file_ext);
            
//            $stored_file_ext = substr(strrchr($path, "."), 1);
//            if ( $stored_file_ext !== $file_ext && $file_ext!==$file_name)
//                $file->move(public_path() . $dir, $filename);
            
            $file_type = 'Другой';
            if (isset($this->file_categories[$file_ext])) $file_type = $this->file_categories[$file_ext];
            
            $attach_id = '';
            $is_append_bd = $request->input('is_append_bd');
            if ($is_append_bd === 'true') {
                $document_id = $request->input('document_id');
                $description = $request->input('description');
                if ($description==null) $description = '';
                if (isset($this->file_types[$file_ext])) $type = $this->file_types[$file_ext];
                else $type = 111111;
                $visible_filename = $file_name;
                $new_attach = DocumentAttach::Create(compact('document_id','description','path','type','visible_filename')); 
                $attach_id = $new_attach->id;
            }
                
            return Response::json(['filepath' => $path, 'filetype' => $file_type, 'att_id' => $attach_id, 'status' => 1]);
        }
        return 'error';
    }
    
    public function do_ajax_remove_file(Request $request) {
        if ($request->ajax()) {
            
            $attach_id = $request->input('att_id');
            $attach = DocumentAttach::find($attach_id);
            $file_name = $attach->path;
            
            //return Response::json(['len' => $out_file_name, 'status' => 1]);
            
            if ( Storage::disk('local')->exists($file_name) )  Storage::disk('local')->delete($file_name);
            
            if ($attach != null) {
                $attach->delete();
                //DocumentAttach::destroy($attach_id);
            }
                
            return Response::json(['status' => 1]);
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
        else $category_id = 0;

        if ($description==null) $description = '';
            
        //var_dump($today); exit;
        $new_doc = Document::Create(compact('category_id','header','description'));
        
        $document_id  = $new_doc->id;
  
            $n = 1;
        while ( $n <= 15 ) {
            if ($request->input('v_tdfile_new_doc' . $n)) {
                $path = '';
                $visible_filename = '';
                $type = 111111;
                $ext = '';
                $description = '';
                $path = $request->input('v_tdfile_new_doc' . $n);
                $visible_filename = $request->input('v_tdvfile_new_doc' . $n);
                if ($request->input('v_tdext_new_doc' . $n))
                    $ext = $request->input('v_tdext_new_doc' . $n);
                if ($request->input('v_tddesc_new_doc' . $n))
                    $description = $request->input('v_tddesc_new_doc' . $n);
                if (isset($this->file_types[$ext])) $type = $this->file_types[$ext];
                $new_attach = DocumentAttach::Create(compact('document_id','description','path','type','visible_filename'));
            }
            $n++;
        }        
        
        return redirect()->route('documents');
    }
    
    public function doc_view(Request $request, $id) {

        if (!$request->user()->hasRole('Сотрудники ТП РОС')) {
            return redirect('forbidden');
        }
        
        $doc = Document::find($id);
        $attaches = DocumentAttach::where('document_id', '=', $doc->id)->get();

//        $add2exist_chain_id = '';
//        return view('contacts.contacts', [
//            'add2exist_chain_id' => $add2exist_chain_id,
//            //'chains_opened' => $chains_opened,
//        ]);
        $fcat = $this->file_categories;
        $ftype = $this->file_types;
        return view('wiki.doc_view',compact('doc','attaches','fcat','ftype'));
    }
    
    public function doc_edit(Request $request, $id) {

        if (!$request->user()->hasRole('Сотрудники ТП РОС')) {
            return redirect('forbidden');
        }
        
        $doc = Document::find($id);
        $attaches = DocumentAttach::where('document_id', '=', $doc->id)->get();
        $doc_categories = DocumentCategories::all();
//        $add2exist_chain_id = '';
//        return view('contacts.contacts', [
//            'add2exist_chain_id' => $add2exist_chain_id,
//            //'chains_opened' => $chains_opened,
//        ]);
        $fcat = $this->file_categories;
        $ftype = $this->file_types;
        return view('wiki.doc_edit',compact('doc','attaches','doc_categories','fcat','ftype'));
    }
    
    public function doc_update(Request $request,$id) {
        
        $category = $request->input('v_cat_edit_doc');
        $header = $request->input('v_head_edit_doc');
        $description = $request->input('v_desc_edit_doc');

        $category_id = '';
        
        if ($category!=null) {
            $category_id = $category;
        }
        else $category_id = 0;

        if ($description==null) $description = '';
        
        $doc = Document::find($id);
        
        $doc->category_id = $category_id;
        $doc->header = $header;
        $doc->description = $description;
        
        $doc->save();
        
        return redirect()->route('documents');
    }
    
    public function do_ajax_remove_doc(Request $request) {
        if ($request->ajax()) {
            
            $doc_id = $request->input('doc_id');
            
            $doc = Document::find($doc_id);
            $attaches = DocumentAttach::where('document_id', '=', $doc->id)->get();
            
            foreach ($attaches as $row => $attach) {
                $file_name = $attach->path;
                if ( Storage::disk('local')->exists($file_name) )  Storage::disk('local')->delete($file_name);
            }
            
            $deletedRows = DocumentAttach::where('document_id', '=', $doc->id)->delete();

            if ($doc != null) $doc->delete();
                
            return Response::json(['status' => 1]);
        }
        return 'error';
    }    
    
}
