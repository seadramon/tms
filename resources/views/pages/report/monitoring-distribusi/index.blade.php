@extends('layout.layout2')

@section('page-title')
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Monitoring Distribusi</h1>
</div>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="row g-5 g-xl-8">
        <div class="col-12 mb-md-5 mb-xl-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Monitoring Distribusi</h3>
                </div>
            
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-9 custom-form">
                            <label class="form-label col-sm-3 custom-label">Tahun</label>
                            {!! Form::select('tahun', $tahun, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'tahun']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-9 custom-form">
                            <label class="form-label col-sm-3 custom-label">Minggu ke-</label>
                            <div class="col-lg-4">
                                {!! Form::select('minggu1', $periode_minggu, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'minggu1']) !!}
                            </div>
                            <label class="form-label col-sm-1 custom-label">s/d</label>
                            <div class="col-lg-4">
                                {!! Form::select('minggu2', $periode_minggu, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'minggu2']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-9 custom-form">
                            <label class="form-label col-sm-3 custom-label">PAT/PPB</label>
                            {!! Form::select('kd_pat', $kd_pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'kd_pat']) !!}
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="dropdown col-lg-9 custom-form">
                            <label class="form-label col-sm-3 custom-label">&nbsp;</label>
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Generate
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item generate" href="#">PDF</a>
                                <a class="dropdown-item generate" href="#">Excel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Content container-->
@endsection

@section('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style>
    .custom-form {
        display: flex;
    }
    
    .custom-label {
        display: flex; 
        align-items: center;
        margin-bottom: 0px;
    }

    .form-group {
        margin-bottom: 5px;
    }

    .dt-buttons {
        float: right;
        display: block;
    }

    p {
        display: inline;
        font-weight: bold;
    }

    .box2-style1 {
        font-size: 60px;
    }

    .box2-style2 {
        font-size: 30px;
    }

    .box2-style3 {
        font-size: 15px;
    }

    .box2-style4 {
        font-size: 15px;
        font-weight: normal;
    }
</style>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/fusion/js/fusioncharts.js') }}"></script>
<script src="{{ asset('assets/fusion/js/themes/fusioncharts.theme.fusion.js') }}"></script>
<script src="{{ asset('assets/fusion/js/jquery-fusioncharts.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#minggu").select2();
        loadPeriodeMinggu();
        // loadData();
        $("#filter").click(function(){
            loadData();
        });
        $("#tahun").change(function(){
            loadPeriodeMinggu();
        });
    });
    $(document).on('click', '.generate', function(){
        tahun = $("#tahun").val();
        minggu1 = $("#minggu1").val();
        minggu2 = $("#minggu2").val();
        kd_pat = $("#kd_pat").val();
        format = $(this).text().toLowerCase();

        exportExcelUrl = "{{ URL::to('report-monitoring-distribusi/export-excel') }}/" + minggu1 + '/' + minggu2 + '/' + kd_pat;
        
        if(format == 'pdf'){
            window.open('http://10.3.1.80/genreport/genreport.asp?RptName=monitoring_distribusi.rpt&fparam='+kd_pat+';'+minggu1+';'+minggu2+'&ftype=5&keyId=OS', '_blank'); 
        }else{
            window.open(exportExcelUrl, '_blank');
        }
        
    });

    function loadPeriodeMinggu(){
        $("#minggu1").empty();
        $("#minggu2").empty();
        $.ajax({
            type:"get",
            url: "{{ route('kalender-pengiriman.periode-minggu') }}?tahun=" + $("#tahun").val(),
            data: {_token: "{{ csrf_token() }}"},
            success: function(result){
                $.each(result.periode_minggu, function(k, v){
                    $("#minggu1").append('<option value="' + k + '">' + v + '</option>')
                    $("#minggu2").append('<option value="' + k + '">' + v + '</option>')
                })
                $("#minggu1").select2("destroy").select2();
                $("#minggu2").select2("destroy").select2();
            }
        });
    }
</script>
@endsection