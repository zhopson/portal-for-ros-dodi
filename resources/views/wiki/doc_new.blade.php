@extends('layouts.app')
@section('head')
<link href="{{ asset('css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid" style="margin:0 30px 0 30px">
    <form method="POST" action="{{ route('documents.store') }}">
        {{ csrf_field() }}
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary" id="id_new_doc_btn_save">Сохранить</button>
                    <a class="btn btn-default" href="{{ url('/documents') }}" id="id_new_doc_btn_cancel">Отмена</a>
                </div>
        
        <div class="row">
            <div class="col-md-4"><h3 style="margin:50px 0 10px 0"><mark>Новый документ </mark></h3></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group"></div>
                <table class="table table-hover">
                    <tr>
                        <td class="table-text">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="pull-right">Заголовок</div>
                                    </div>
                                    <div class="col-md-9"> 
                                        <input type="text" class="form-control" id="id_head_new_doc" name="v_head_new_doc" required >
<!--                                            <textarea rows="6" cols="50" class="form-control" id="id_task_new_msg" name="v_task_new_msg" required ></textarea>-->
                                    </div>
                                </div>
                            </div>                                
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="pull-right">Описание</div>
                                    </div>
                                    <div class="col-md-9"> 
                                        <textarea rows="6" cols="50" class="form-control" id="id_desc_new_doc" name="v_desc_new_doc"></textarea>
                                    </div>
                                </div>
                            </div>                                
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="pull-right">Категория</div>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="id_cat_new_doc" name="v_cat_new_doc">
                                            <option ></option>
                                            @foreach ($doc_categories as $category) 
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>                             
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

<!--                    <input type="hidden" class="form-control" id="id_note_new_chain" name="v_note_new_chain" value="">-->
                </table>
<!--                <div class="pull-right">
                    <button type="submit" class="btn btn-primary" id="id_new_doc_btn_save">Сохранить</button>
                    <a class="btn btn-default" href="{{ url('/documents') }}" id="id_new_doc_btn_cancel">Отмена</a>
                </div>-->

            </div>
        </div>
        <div class="row">
            <div class="col-md-4"><h4 style="margin-top:-5px"><mark>Прикрепленные файлы </mark></h4></div>
        </div>
        <div class="panel panel-default">
            <!--            <div class="panel-heading">Panel heading without title</div>-->
            <div class="panel-body">
                <div class="form-group"></div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="pull-right">Описание файла</div>
                        </div>
                        <div class="col-md-4"> 
                            <input type="text" class="form-control" id="id_desc_file_new_doc" name="v_desc_file_new_doc">
                        </div>

                        <!--<div class="input-group">
                          <span class="input-group-btn">
                            <span class="btn btn-default btn-file">
                              Browse... 
                              <input data-url="/upload" accept="image/jpeg" name="image" type="file" id="image">
                            </span>
                          </span>
                          <input readonly="readonly" placeholder="Picture file" class="form-control" name="filename" type="text">
                        </div>-->

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="pull-right">
                                <span class="btn btn-default btn-file">
                                    Загрузить... 
                                    <input data-url="/upload" type="file" id="id_load_file_new_doc" name="v_load_file_new_doc">
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input readonly="readonly" placeholder="Загруженный файл" class="form-control" id="id_filename_new_doc" name="v_filename_new_doc" type="text">
                        </div>
                    </div>
                </div>
                <div id="id_error_file_panel">
                    <div class="col-md-7 col-md-offset-1">
                        <div class="alert alert-danger" style="margin-top: 15px">
                            <ul>
                                Ошибка при загрузке файла
                            </ul>
                        </div> 
                    </div>
                </div>                

                <table class="table table-hover" style="margin-top:30px" id="id_td_files_new_doc">
                    <thead>
                        <tr class="active">
                            <th>Имя файла</th>
                            <th>Тип</th>
                            <th style="width: 480px">Описание файла</th>
                        </tr>
                    </thead>                            
                    <tbody>
                        <tr>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--        <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2 col-md-offset-8">
                            <button type="submit" class="btn btn-primary" id="id_cat_new_btn_save">Сохранить</button>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-default" href="{{ url()->previous() }}" role="button" id="id_cat_new_btn_cancel">Отмена</a>
                        </div>
                    </div>
                </div>-->
    </form>
</div>

@endsection

@section('footer')
<script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<script type="text/javascript">
var n = 0;

function HideMsg() {
  $(".alert").css('display', 'none');  
}

function addfile2table(p_vfile,pfile,ptype,pext,pdesc) {
    n = n + 1;
    $("#id_td_files_new_doc").find('tbody')
        .append($('<tr>')
            .append($('<td>').attr('class', 'table-text')
                .append($('<div>')
                .append(p_vfile)
                )
                .append($('<input>')
                    .attr('type','hidden')
                    .attr('value',p_vfile)
                    .attr('name','v_tdvfile_new_doc'+n)
                )
                .append($('<input>')
                    .attr('type','hidden')
                    .attr('value',pfile)
                    .attr('name','v_tdfile_new_doc'+n)
                )
            )
            .append($('<td>').attr('class', 'table-text')
                .append($('<div>')
                .append(ptype)
                )
                .append($('<input>')
                    .attr('type','hidden')
                    .attr('value',pext)
                    .attr('name','v_tdext_new_doc'+n)
                )
            )
            .append($('<td>').attr('class', 'table-text')
                .append($('<div>')
                .append(pdesc)
                )
                .append($('<input>')
                    .attr('type','hidden')
                    .attr('value',pdesc)
                    .attr('name','v_tddesc_new_doc'+n)
                )
            )
//            .append($('<td>')
//                .append($('<input>')
//                .attr('class', 'form-control')
//                .attr('type','text')
//                .attr('value',pdesc)
//                .attr('name','v_tddesc_new_doc'+n)
//                )
//            )

//            .append($('<img>')
//                .attr('src', 'img.png')
//                .text('Image cell')
//            )

    );
}

$(document).ready(function () {

    $('#id_load_file_new_doc').change(function () {
        //alert('call');
        var uploadinput = $('#id_load_file_new_doc');
        if (uploadinput.val()) {
        $('#id_filename_new_doc').val(uploadinput[0].files[0].name);
        var filename = uploadinput[0].files[0].name;
        var extfile = filename.substr(filename.lastIndexOf(".")+1);
        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму файл
        data1.append('file_object', uploadinput[0].files[0]);
        data1.append('file_name', filename);
        data1.append('file_ext', extfile);
        data1.append('is_append_bd', 'false');
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: '/documents/ajax_upload_file',
            type: 'POST',
            data: data1,
			// Эта опция не разрешает jQuery изменять данные
			processData: false,		
			// Эта опция не разрешает jQuery изменять типы данных
			contentType: false,	            
            dataType: 'json',
            success: function (result) {
//                console.log('8 ajax_start np_chg ' + (new Date().toISOString().slice(11, -1)));
                if (result.status === 1) {
                    var upfile = result.filepath;
                    var typefile = result.filetype;
                    //alert('file path:'+upfile+', file name:' + filename + ', file ext:'+extfile);
                    $('#id_error_file_panel').css('display', 'none');
                    addfile2table(filename,upfile,typefile,extfile,$('#id_desc_file_new_doc').val());
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_file_panel').css('display', 'inline');
                setTimeout(HideMsg,5000);
            }
        });
        }
    });

});

window.onload = function () {
    $('#id_error_file_panel').css('display', 'none');
};
</script>
@endsection
