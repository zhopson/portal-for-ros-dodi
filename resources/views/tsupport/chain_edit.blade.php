@extends('layouts.app')
@section('head')

@endsection
@section('content')

<div class="container-fluid" style="margin:0 60px 0 60px">
<form class="form-horizontal" name="frm_ch_edit" method="POST" action="{{ route('chains.update', ['id' => $id]) }}">
{{ csrf_field() }}

    <div class="row">
        <h3 style="margin-top:25px"><div class="header-text">Параметры протокола #{{$id}}</div></h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" style="margin-bottom:55px">
                <div class="panel-body">
                            <div class="form-group" id="id_ch_category_container">
                                <label for="id_ch_edit_category" class="col-sm-3 control-label">Категории</label>
                                <div class="MBlock" style="margin-bottom: 5px">
                                    <div class="row">
                                    <div class="col-sm-7">
                                        <select class="form-control" id="id_ch_edit_category" name="v_ch_edit_category">
                                            <option value="0"></option>
                                            @foreach ($categories as $category) 
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="id_ch_edit_category_btnadd"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                        </span>                                    
                                    </div>
                                    </div>
                                </div>
                            </div>                            
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>                    
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" id="id_ch_edit_nav_buttons">
                <li role="presentation" id="id_ch_edit_nav_save"><a href="#" onclick="document.frm_ch_edit.submit();">Сохранить</a></li>
                <li role="presentation" id="id_ch_edit_nav_cancel"><a href="{{ url()->previous() }}">Отмена</a></li>   
            </ul>
        </div>
        
    </div>
</form>                    
                
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
    
    function addgroups(id=null) {
        //var n = $("#id_clt_dop_grps_container").children('.DopBlock').length;
        var block = $('<div/>', {
            'class': 'DopBlock',
            style: 'margin-top:5px'
        }).appendTo($('#id_ch_category_container'));
                                        var raw = $('<div/>', {
                                                'class' : 'row',
                                                //style : 'margin:5px 1px 0 0;'
                                        }).appendTo(block);
        var div_sm3e = $('<div/>', {
            'class': 'col-sm-3'
        }).appendTo(raw);
        var div_sm6 = $('<div/>', {
            'class': 'col-sm-6'
        }).appendTo(raw);
        var pull = $('<div/>', {
            'class': 'pull-left'
        }).appendTo(div_sm6);
        var label = $('<label/>', {
            text: $('#id_ch_edit_category option:selected').text(),
            //value: '1',
            //type: 'text',
            'class': 'control-label'
        }).appendTo(pull);
        var gr_id = $('<input/>', {
            value: id,
            name: 'v_ch_edit_cat'+id,
            type: 'hidden',
            'class': 'form-control'
        }).appendTo(div_sm6);        
        var div_sm1 = $('<div/>', {
            'class': 'col-sm-1'
        }).appendTo(raw);
        var div_span_sm1 = $('<span/>', {
            'class': 'input-group-btn'
        }).appendTo(div_sm1);
        var btn_del = $('<button/>', {
            type: 'button',
            'class': 'btn btn-default',
            style: 'margin-left:-6px'
        }).appendTo(div_span_sm1);
        var span_img = $('<span/>', {
            'class': 'glyphicon glyphicon-minus',
            'aria-hidden': 'true'
        }).appendTo(btn_del);
        btn_del.click(function () {
            $(this).parent().parent().parent().remove();
        });
    }    
    
$( document ).ready(function() {    

    $('#id_ch_edit_category_btnadd').click(function () {
        if ($('#id_ch_edit_category').val() !== "0") {
            var flag = 0;
            $(".DopBlock").each(function (i) {
                var ltext = $(this).find('.col-sm-6').find('label').text();
                if (ltext === $('#id_ch_edit_category option:selected').text()) {
                    //alert(ltext);
                    flag = 1;
                    return false;
                }
            });
            if (flag === 0) {
                addgroups($('#id_ch_edit_category option:selected').val());
            }
        }
        return false;
    });        
    

});

window.onload = function () {
    @foreach ($chain_categories as $cat)
        $('#id_ch_edit_category').val('{{$cat->category_id}}').change();
        $('#id_ch_edit_category_btnadd').click();
        $('#id_ch_edit_category').val('0').change();
    @endforeach    
}

</script>
@endsection
