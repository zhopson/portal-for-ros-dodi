@extends('layouts.app')
@section('head')
<link href="{{ asset('css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="modal fade" id="id_removeFileModal" tabindex="-1" role="dialog" aria-labelledby="CallModalLabel" data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<!--                <h4 class="modal-title" id="CallModalLabel">Позвонить</h4>-->
                <h3 ><mark class="class_clt_name">Действительно удалить файл?</mark></h3>
            </div>        
            <div class="modal-body"  align="right">
                <input type="hidden" class="form-control" id="id_removed_file" name="v_removed_file">
                <button type="button" id="id_remove_file_btn" onclick="Javascript:removeFile($('#id_removed_file').val())" class="btn btn-primary">Удалить</button>
                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" style="margin:0 30px 0 30px">
    <form method="POST" action="{{ route('documents.update', ['id' => $doc->id]) }}">
        {{ csrf_field() }}
<!--                <div class="pull-right">
                    <button type="submit" class="btn btn-primary" id="id_edit_doc_btn_save">Сохранить</button>
                    <a class="btn btn-default" href="{{ url('/documents') }}" id="id_edit_doc_btn_cancel">Отмена</a>
                </div>-->
        
        <div class="row">
            <div class="col-md-4"><h3 style="margin:50px 0 10px 0"><mark>Редактировать документ № {{ $doc->id }}</mark></h3></div>
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
                                        <input type="text" class="form-control" id="id_head_edit_doc" name="v_head_edit_doc" value="{{$doc->header}}" required >
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
                                        <textarea rows="6" cols="50" class="form-control" id="id_desc_edit_doc" name="v_desc_edit_doc" >{{$doc->description}}</textarea>
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
                                        <select class="form-control" id="id_cat_edit_doc" name="v_cat_edit_doc">
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
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary" id="id_edit_doc_btn_save">Сохранить</button>
                    <a class="btn btn-default" href="{{ url('/documents') }}" id="id_edit_doc_btn_cancel">Отмена</a>
                </div>

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
                            <input type="text" class="form-control" id="id_desc_file_edit_doc" name="v_desc_file_edit_doc">
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
                                    <input data-url="/upload" type="file" id="id_load_file_edit_doc" name="v_load_file_edit_doc">
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input readonly="readonly" placeholder="Загруженный файл" class="form-control" id="id_filename_edit_doc" name="v_filename_edit_doc" type="text">
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

                <table class="table table-hover" id="id_td_files_edit_doc" style="margin-top:15px">
                    <thead>
                        <tr class="active">
                            <th>Имя файла</th>
                            <th>Тип</th>
                            <th style="width: 180px">Дата загрузки</th>
                            <th style="width: 580px">Описание файла</th>
                            <th></th>
                        </tr>
                    </thead>                            
                    <tbody>
                        @foreach ($attaches as $attach)
                        <tr id="{{$attach->id}}">
                            <td class="table-text">
                                <div><a href="{{  asset($attach->path) }}">{{ $attach->visible_filename }}</a></div>
                            </td>                            
                            <td class="table-text">
                                @if ($attach->type===111111)
                                    <div>Другой</div>
                                @else
                                    @php
                                        $key = array_search($attach->type, $ftype);
                                        if ($key) echo e($fcat[$key]);
                                        else echo e('Другой');
                                    @endphp
                                @endif
                            </td>                            
                            <td class="table-text">
                                <div>{{ $attach->creation_time }}</div>
                            </td>                            
                            <td class="table-text">
                                <div>{{ $attach->description }}</div>
                            </td>                            
                            <td class="table-text">
                                <a href="Javascript:AskRemoveFile('{{$attach->id}}')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a>
                            </td>                            
                        </tr>
                        @endforeach
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
<script src="{{ asset('js/modal.js') }}"></script>
<script type="text/javascript">

function addfile2table(pid,p_vfile,pfile,ptype,pext,ploaddate,pdesc) {
    n = pid;
    home = '{{  asset('/') }}';
    $("#id_td_files_edit_doc").find('tbody')
        .append($('<tr>').attr('id', n)
            .append($('<td>').attr('class', 'table-text')
                .append($('<div>')
                .append($('<a href="'+home+pfile+'">'+p_vfile+'</a>'))
                //.append(p_vfile)
                )
                .append($('<input>')
                    .attr('type','hidden')
                    .attr('value',p_vfile)
                    .attr('name','v_tdvfile_edit_doc'+n)
                )
                .append($('<input>')
                    .attr('type','hidden')
                    .attr('value',pfile)
                    .attr('name','v_tdfile_edit_doc'+n)
                )
            )
            .append($('<td>').attr('class', 'table-text')
                .append($('<div>')
                .append(ptype)
                )
                .append($('<input>')
                    .attr('type','hidden')
                    .attr('value',pext)
                    .attr('name','v_tdext_edit_doc'+n)
                )
            )
            .append($('<td>').attr('class', 'table-text')
                .append($('<div>')
                .append(ploaddate)
                )
            )
            .append($('<td>').attr('class', 'table-text')
                .append($('<div>')
                .append(pdesc)
                )
                .append($('<input>')
                    .attr('type','hidden')
                    .attr('value',pdesc)
                    .attr('name','v_tddesc_edit_doc'+n)
                )
            )
            .append($('<td>').attr('class', 'table-text')
                .append($('<div>')
                    .append($('<a href="Javascript:AskRemoveFile(\''+n+'\')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a>'))
        
//                    .append($('<a>')
//                        .attr('href', "Javascript:AskRemoveFile('"+n+"')")
//                        .text(' Удалить')
//                        .append($('<span>')
//                            .attr('class', "glyphicon glyphicon-remove")
//                            .attr('aria-hidden', "true")
//                        )
//                    )
                )
            )
//            .append($('<td>')
//                .append($('<input>')
//                .attr('class', 'form-control')
//                .attr('type','text')
//                .attr('value',pdesc)
//                .attr('name','v_tddesc_edit_doc'+n)
//                )
//            )

//            .append($('<img>')
//                .attr('src', 'img.png')
//                .text('Image cell')
//            )

    );
}

function AskRemoveFile(id) {
    $('#id_removeFileModal').on('show.bs.modal', function () {
        $('#id_removed_file').val(id);
    });

    $('#id_removeFileModal').modal('show');
    //$('#id_td_files_edit_doc').find('tr#'+id).remove();
}


function removeFile(id) {

        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму файл
        data1.append('att_id', id);
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: '/documents/ajax_remove_file',
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
                    $('#id_td_files_edit_doc').find('tr#'+id).remove();
                    $('#id_removeFileModal').modal('hide');
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_file_panel').css('display', 'inline');
            }
        });
}

$(document).ready(function () {

    $('#id_load_file_edit_doc').change(function () {
        //alert('call');
        var uploadinput = $('#id_load_file_edit_doc');
        if (uploadinput.val()) {
        $('#id_filename_edit_doc').val(uploadinput[0].files[0].name);
        var filename = uploadinput[0].files[0].name;
        var extfile = filename.substr(filename.lastIndexOf(".")+1);
        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму файл
        data1.append('file_object', uploadinput[0].files[0]);
        data1.append('file_name', filename);
        data1.append('file_ext', extfile);
        data1.append('is_append_bd', 'true');
        data1.append('document_id', {{  $doc->id }});
        data1.append('description', $('#id_desc_file_edit_doc').val());
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
                    
                    var now = new Date();
                    now.setHours(now.getHours() + 9);
                    var formated_date = now.toISOString().substr(0, 19).replace("T"," ");
                    
                    var upfile = result.filepath;
                    var typefile = result.filetype;
                    var attach_id = result.att_id;
                    //alert('file path:'+upfile+', file name:' + filename + ', file ext:'+extfile);
                    $('#id_error_file_panel').css('display', 'none');
                    addfile2table(attach_id,filename,upfile,typefile,extfile,formated_date,$('#id_desc_file_edit_doc').val());
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_file_panel').css('display', 'inline');
            }
        });
        }
    });

});

window.onload = function () {
    $('#id_error_file_panel').css('display', 'none');
    
    var r = '{{$doc->category_id}}';
    if (r!=='0') 
        $('#id_cat_edit_doc').val('{{$doc->category_id}}').change();
    
};
</script>
@endsection
