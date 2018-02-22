@extends('layouts.app')
@section('head')
<link href="{{ asset('css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid" style="margin:0 280px 40px 30px">
    <form method="POST" action="{{ route('documents.store') }}">
        {{ csrf_field() }}
<!--                <div class="pull-right">
                    <button type="submit" class="btn btn-primary" id="id_new_doc_btn_save">Сохранить</button>
                    <a class="btn btn-default" href="{{ url('/documents') }}" id="id_new_doc_btn_cancel">Отмена</a>
                </div>-->
        
        <div class="row">
            <div class="col-md-4"><h3 ><mark>Просмотр документа № {{ $doc->id }}</mark></h3></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                    <a href="{{ route('documents.edit', ['id' => $doc->id]) }}" style="margin-left:20px"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a>    
                <table class="table table-hover" style="margin-top:20px">
                    <tr>
                        <td style="width: 180px"class="table-text">
                            <div class="pull-left">Заголовок</div>
                        </td>
                        <td class="table-text">
                            {{$doc->header}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 180px"class="table-text">
                            <div class="pull-left">Категория</div>
                        </td>
                        <td class="table-text">
                            @if ($doc->category_id)
                                {{ App\DocumentCategories::find($doc->category_id)->name }}
                            @else
                            Отсутствует
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div class="pull-left">Добавлен</div>
                        </td>
                        <td class="table-text">
                            {{$doc->creation_time}}
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div class="pull-left">Изменен</div>
                        </td>
                        <td class="table-text">
                            {{$doc->update_time}}
                        </td>
                    </tr>
                    <tr>
                        <td class="table-text">
                            <div class="pull-left">Описание</div>
                        </td>
                        <td class="table-text">
                            <div class="form-group">
                                <textarea rows="6" cols="50" class="form-control" readonly="true">{{$doc->description}}</textarea>
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
                <table class="table table-hover" id="id_td_files_view_doc" style="margin-top:15px">
                    <thead>
                        <tr class="active">
                            <th>Имя файла</th>
                            <th>Тип</th>
                            <th style="width: 180px">Дата загрузки</th>
                            <th style="width: 580px">Описание файла</th>
                        </tr>
                    </thead>                            
                    <tbody>
                        @foreach ($attaches as $attach)
                        <tr>
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
<script type="text/javascript">
var n = 0;

function addfile2table(p_vfile,pfile,ptype,pext,pdesc) {
    n = n + 1;
    $("#id_td_files_view_doc").find('tbody')
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

});

window.onload = function () {
   
};
</script>
@endsection
