<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

use App\Category;

class CategoriesController extends Controller
{
    public function index(Request $request) {
        
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') && !$request->user()->hasRole('Сотрудники ТП РОС') && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ') ) { 
            return redirect('forbidden');
        }  
        
        return view('categories');
    }    
    
    public function Get_json_categories() {

        $categories = Category::all();

        $data = [];
        foreach ($categories as $row=>$category){
            
            $category_edit_tag = '';
            $category_edit_tag = '<a href="javascript:Edit_category('.$category->id.')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a>';
            
                                
            array_push($data, 
              array(
                ' ',
                $category->name,
                $category_edit_tag,
              )
            );
        }
        return ['data'=>$data];
        
    }
    
 public function cat_add(Request $request) {
    if ($request->ajax()) {
        $name = $request->input('name');
        
        $new_cat = Category::create(compact('name'));

        return Response::json(['id' => $new_cat->id, 'status' => 1]);
    }
    return 'error';   
 }    

  public function cat_edit(Request $request) {
    if ($request->ajax()) {
        $id = $request->input('id');
        
        $cat = Category::find($id);

        return Response::json(['id' => $cat->id, 'name' => $cat->name, 'status' => 1]);
    }
    return 'error';   
 } 
 
  public function cat_save(Request $request) {
    if ($request->ajax()) {
        $id = $request->input('id');
        
        $name = $request->input('name');
        
        $cat = Category::find($id);
        
        $cat->name = $name;
        $cat->save();

        return Response::json(['status' => 1]);
    }
    return 'error';   
 } 
    
}
