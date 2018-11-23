@extends('layouts.app')

@section('head')

@endsection

@section('content')

@php
    $fio = '';
    if ($id != 0) {
        $clt = App\Client::find($id);
        $fio = $clt->surname.' '.$clt->name.' '.$clt->patronymic;
    }

@endphp

@if (session('status'))
  <div class="alert alert-success">
        {{ session('status') }}
  </div>
@endif

<div class="container-fluid" style="margin:0 30px 0 30px">
    <div class="row">
        <h3 style="margin-top:25px"><div class="header-text">Статистика по трафику клиентов</div></h3>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading" style="margin-top:10px">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group input-group-sm">
                        @if ($id != 0) 
                            <input type="text" class="form-control" id="id_clt_traf_user" value="ID {{ $id }}" readonly>
                            {{-- <input type="text" class="form-control" id="id_clt_traf_user" value="{{ $fio }}" readonly> --}}
                        @elseif ($ip != 0)
                            <input type="text" class="form-control" id="id_clt_traf_user" placeholder="Пользователь" readonly>
                        @else 
                            <input type="text" class="form-control" id="id_clt_traf_user" value="Все пользователи" readonly>
                        @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></span>
                            @if ($ip != 0)
                                <input type="text" class="form-control" id="id_clt_traf_ip" value="{{ $ip }}" placeholder="0.0.0.0" aria-describedby="basic-addon1">
                            @else     
                                <input type="text" class="form-control" id="id_clt_traf_ip" placeholder="0.0.0.0" aria-describedby="basic-addon1">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" id="basic-addon2"><span class="glyphicon glyphicon-time" aria-hidden="true"></span></span>
                            <input type="date" class="form-control" id="id_clt_traf_date1" placeholder="" value="{{$start_date}}" aria-describedby="basic-addon2">                                        
                        </div>                                    
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" id="basic-addon3"><span class="glyphicon glyphicon-time" aria-hidden="true"></span></span>
                            <input type="date" class="form-control" id="id_clt_traf_date2" placeholder="" value="{{$end_date}}" aria-describedby="basic-addon3">                                        
                        </div>                                    
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <button type="button" id="id_clt_traf_execute" class="btn btn-info btn-sm">Выполнить</button>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="id_clt_traf_active" name="v_clt_traf_active" checked>
                                    Только активные клиенты
                                </label>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="panel panel-default" style="margin: 15px 15px 55px 15px">
                        <!-- Default panel contents -->
                        <div class="panel-heading">Статистика</div>
                        <!-- Table -->
                        <table class="table table-hover">
                            <tr class="active">
                                <th>Всего, входящий МБ</th>
                                <th>Всего, исходящий МБ</th>
                                <th>Макс скорость входящая Кбит/с</th>
                                <th>Ср. скорость входящая Кбит/с</th>
                                <th>Макс скорость исходящая Кбит/с</th>
                                <th>Ср. скорость исходящая Кбит/с</th>
                            </tr>
                            <tr>
                                <td class="table-text" id="id_sum_in"></td>
                                <td class="table-text" id="id_sum_out"></td>
                                <td class="table-text" id="id_max_sp_in"></td>
                                <td class="table-text" id="id_avg_sp_in"></td>
                                <td class="table-text" id="id_max_sp_out"></td>
                                <td class="table-text" id="id_avg_sp_out"></td>
                            </tr>
                        </table>
<!--                        <input type="text" class="form-control" id="id_1">-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"  style="height:400px; margin: 5px 5px 0 5px">
                        <div id="container_traf" style="height: 100%"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"  style="height:400px; margin: 15px 5px 0 5px">
                        <div id="container_band" style="height: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('footer')
<!--<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>-->
        <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
        <script src="{{ asset('js/modal.js') }}"></script>        
        <script src="{{ asset('js/echarts/echarts.common.min.js') }}"></script>
        <script src="{{ asset('js/echarts/ecStat.min.js') }}"></script>
        <script src="{{ asset('js/echarts/dataTool.min.js') }}"></script>
        <script src="{{ asset('js/echarts/world.js') }}"></script>
        <script src="{{ asset('js/echarts/bmap.min.js') }}"></script>

<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
<script type="text/javascript">

$(document).ready(function () {
        
//    $.ajaxSetup({
//        headers: {
//            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//        }
//    }); 

        $('#id_clt_traf_execute').click(function () {
            
            var ip, id, active;
            
            id = '{{ $id }}';
            //if (id!=='0') 
                
            if ($('#id_clt_traf_ip').val() === '') ip = '0';
            else { 
                ip = $('#id_clt_traf_ip').val();
                $('#id_clt_traf_user').val('Пользователь');
                id = '0';
            }            
            if (ip === '0' && id === '0') { $('#id_clt_traf_user').val('Все пользователи');}
            
//            $('#id_clt_traf_user').val('Пользователь');
//            
//            if ($('#id_clt_traf_ip').val() === '') ip = '0';
//            else { 
//                ip = $('#id_clt_traf_ip').val();
//                $('#id_clt_traf_user').val('Пользователь');
//            }
//            
//            id = '0';
//            
//            if (ip === '0') { $('#id_clt_traf_user').val('Все пользователи');}
            
            actived = $('#id_clt_traf_active').prop('checked');
            if (actived) active = 'on';
            else active = 'off';            
            
            //alert('ip:'+ip);
            $('#id_DBReqModal').modal('show');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/netflow/clients/ajax_get_traf',
                type: 'POST',
                data: {'id': id, 'ip': ip, 'date1': $('#id_clt_traf_date1').val(),'date2': $('#id_clt_traf_date2').val(),'only_active': active},
                dataType: 'json',
                success: function (result) {
                    if (result.status === 1) {
                        //$('#id_1').val(result.only_active);
//                        $('#id_stat_error_panel').css('display', 'none');
                        $('#id_DBReqModal').modal('hide');
                        
                        $('#id_sum_in').html(result.sum_in);
                        $('#id_sum_out').html(result.sum_out);
                        $('#id_max_sp_in').html(result.max_sp_in);
                        $('#id_avg_sp_in').html(result.avg_sp_in);
                        $('#id_max_sp_out').html(result.max_sp_out);
                        $('#id_avg_sp_out').html(result.avg_sp_out);                        
                        
                        GraphReport('container_traf','Трафик входящий/исходящий',result.data_dates,result.data_values_in,result.data_values_out);
                        GraphReport('container_band','Скорость',result.data_dates,result.data_values_in_sp,result.data_values_out_sp);
                    }
                },
                // Что-то пошло не так
                error: function (result) {
                    $('#id_DBReqModal').modal('hide');
                    //$('#id_call_error').css('display', 'inline');
                    
                        $('#id_sum_in').html('0');
                        $('#id_sum_out').html('0');
                        $('#id_max_sp_in').html('0');
                        $('#id_avg_sp_in').html('0');
                        $('#id_max_sp_out').html('0');
                        $('#id_avg_sp_out').html('0');                      
                    
                        GraphReport('container_traf','Трафик входящий/исходящий',[],[],[]);
                        GraphReport('container_band','Скорость',[],[],[]);
                    
                }
            }); 


        });

});

function HideMsg() {
  $(".alert").css('display', 'none');  
}

        window.onload = function () {
            active = 'on';
            $('#id_DBReqModal').modal('show');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/netflow/clients/ajax_get_traf',
                type: 'POST',
                data: {'id': '{{ $id }}', 'ip': '{{ $ip }}', 'date1': $('#id_clt_traf_date1').val(),'date2': $('#id_clt_traf_date2').val(),'only_active': active},
                dataType: 'json',
                success: function (result) {
                    if (result.status === 1) {
                        //alert(result.ddt);
                        //call_id = result.new_call_id;
                        
//                        $('#id_stat_error_panel').css('display', 'none');
                        
                        $('#id_DBReqModal').modal('hide');
                        
                        $('#id_sum_in').html(result.sum_in);
                        $('#id_sum_out').html(result.sum_out);
                        $('#id_max_sp_in').html(result.max_sp_in);
                        $('#id_avg_sp_in').html(result.avg_sp_in);
                        $('#id_max_sp_out').html(result.max_sp_out);
                        $('#id_avg_sp_out').html(result.avg_sp_out);                       
                        
                        GraphReport('container_traf','Трафик входящий/исходящий',result.data_dates,result.data_values_in,result.data_values_out);
                        GraphReport('container_band','Скорость',result.data_dates,result.data_values_in_sp,result.data_values_out_sp);
                    }
                },
                // Что-то пошло не так
                error: function (result) {
                        $('#id_DBReqModal').modal('hide');
                    //$('#id_call_error').css('display', 'inline');
                }
            }); 
            
            @if (session('status'))
                setTimeout(HideMsg,5000);
            @endif              
            
        };

function GraphReport(pcontainer,ptitle,pdata_dates,pdata_values_in,pdata_values_out) {

var dom = document.getElementById(pcontainer);
var myChart = echarts.init(dom);
var app = {};
option = null;
app.title = ptitle;

//pdata_dates = pdata_dates.map(function (str) {
//    return str.replace(str.substring(0, 5), '');
//});

//timeData = timeData.map(function (str) {
//    return str.replace('2009/', '');
//});


//var colors = ['#5793f3', '#d14a61', '#a14bbb', '#675bba'];
var colors = ['#5793f3', '#d14a61', '#12ea56'];

option = {
    color: colors,
    title: {
        text: ptitle,
        //subtext: 'Данные от Xian Lantau Hydropower Measurement and Control Technology Co., Ltd.',
        x: 'center'
    },
    tooltip: {
        trigger: 'axis',
        //formatter: '{a0}: {b0}<br />{a1}: {b1}',
        axisPointer: {
            animation: false
        }
    },
    legend: {
        data:['Входящий','Исходящий'],
        x: 'left'
    },
    toolbox: {
        feature: {
            dataZoom: {
                yAxisIndex: 'none',
                title: { zoom:'Масштаб', back: 'Восстановить'}
            },
            restore: {title: 'Сброс'},
            saveAsImage: {title: 'Сохранить как...'}
        }
    },
    axisPointer: {
        link: {xAxisIndex: 'all'}
    },
    dataZoom: [
        {
            show: true,
            realtime: true,
            start: 0,
            end: 100,
            xAxisIndex: [0, 1]
        },
        {
            type: 'inside',
            realtime: true,
            start: 0,
            end: 100,
            xAxisIndex: [0, 1]
        }
    ],
    grid: {
        left: 90,
        right: 50,
        top: '15%',
        height: '70%'
    }, 
    xAxis : [
        {
            type : 'category',
//            axisLabel: {
//                formatter: function (value) {
//                    var str = String(value);
//                    var dt = str.substring(0,5);
//                    var tmp_dt = dt.split('-');
//                    var new_dt = tmp_dt[1] + '-' + tmp_dt[0];
//                    return str.replace(dt, new_dt);
//                }
//            },
//            axisTick: {
//                alignWithLabel: true
//            },            
            boundaryGap : false,
            axisLine: {
                onZero: false,
                lineStyle: {
                    color: colors[0]
                }            
            },
            data: pdata_dates
        },
        {
            type : 'category',
//            axisLabel: {
//                formatter: function (value) {
//                    var str = String(value);
//                    var dt = str.substring(0,5);
//                    var tmp_dt = dt.split('-');
//                    var new_dt = tmp_dt[1] + '-' + tmp_dt[0];
//                    return str.replace(dt, new_dt);
//                }
//            },
//            axisTick: {
//                alignWithLabel: true
//            },            
            boundaryGap : false,
            axisLine: {
                onZero: false,
                lineStyle: {
                    color: colors[1]
                }            
            },
            data: pdata_dates
            //position: 'top'
        }
    ],
    yAxis : [
        {
            //name : 'Поток(m^3/s)',
            type : 'log', //type : 'value',
            axisLabel: {
                formatter: function (value) {
                    str = String(value);
                    val = str.replace(",", "");
                    val = Number(val);
                    
                    if (pcontainer === 'container_traf') {
                        LabelKB = " КБ";
                        LabelMB = " МБ";
                        LabelGB = " ГБ";
                        LabelTB = " ТБ";
                    }
                    else {
                        LabelKB = " Кб/C";
                        LabelMB = " Мб/C";
                        LabelGB = " Гб/C";
                        LabelTB = " Тб/C";
                    }
                    
                    if ( val <= 1024 )  return val + LabelKB;
                    else if ( val > 1024 && val <= 1048576 )  return Math.round(val/1024) + LabelMB;
                    else if ( val > 1048576 && val <= 1073741824 ) return Math.round(val/1024/1024) + LabelGB;
                    else return Math.round(val/1024/1024/1024) + LabelTB;
                    //return str.replace(",", "");
                }
            }
            
            //max : 500
        }
    ],
    series : [
        {
            name:'Входящий',
            type:'line',
            xAxisIndex: 1,
            symbolSize: 7,
            hoverAnimation: false,
            data: pdata_values_in
        },
        {
            name:'Исходящий',
            type:'line',
            //xAxisIndex: 1,
            symbolSize: 7,
            hoverAnimation: false,
            data: pdata_values_out
        },
    ]
};

//var colors = ['#5793f3', '#d14a61', '#675bba'];
//
//option = {
//    color: colors,
//
//    tooltip: {
//        trigger: 'none',
//        axisPointer: {
//            type: 'cross'
//        }
//    },
//    legend: {
//        data:['2015 осадки', '2016 осадки']
//    },
//    grid: {
//        top: 70,
//        bottom: 50
//    },
//    xAxis: [
//        {
//            type: 'category',
//            axisTick: {
//                alignWithLabel: true
//            },
//            axisLine: {
//                onZero: false,
//                lineStyle: {
//                    color: colors[1]
//                }
//            },
//            axisPointer: {
//                label: {
//                    formatter: function (params) {
//                        return 'осадки  ' + params.value
//                            + (params.seriesData.length ? '：' + params.seriesData[0].data : '');
//                    }
//                }
//            },
//            data: ["2016-1", "2016-2", "2016-3", "2016-4", "2016-5", "2016-6", "2016-7", "2016-8", "2016-9", "2016-10", "2016-11", "2016-12"]
//        },
//        {
//            type: 'category',
//            axisTick: {
//                alignWithLabel: true
//            },
//            axisLine: {
//                onZero: false,
//                lineStyle: {
//                    color: colors[0]
//                }
//            },
//            axisPointer: {
//                label: {
//                    formatter: function (params) {
//                        return 'осадки  ' + params.value
//                            + (params.seriesData.length ? '：' + params.seriesData[0].data : '');
//                    }
//                }
//            },
//            data: ["2015-1", "2015-2", "2015-3", "2015-4", "2015-5", "2015-6", "2015-7", "2015-8", "2015-9", "2015-10", "2015-11", "2015-12"]
//        }
//    ],
//    yAxis: [
//        {
//            type: 'value'
//        }
//    ],
//    series: [
//        {
//            name:'2015 осадки',
//            type:'line',
//            xAxisIndex: 1,
//            smooth: true,
//            data: [2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3]
//        },
//        {
//            name:'2016 осадки',
//            type:'line',
//            smooth: true,
//            data: [3.9, 5.9, 11.1, 18.7, 48.3, 69.2, 231.6, 46.6, 55.4, 18.4, 10.3, 0.7]
//        }
//    ]
//};


if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
}

</script>
@endsection