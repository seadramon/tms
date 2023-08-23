@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Kalender Pengiriman</h1>
</div>
<!--end::Page title-->
@endsection
@section('content')
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12 mb-md-5 mb-xl-10">
            <div class="card shadow-sm" id="div-card">
                <div class="card-header">
                    <h3 class="card-title">Kalender Pengiriman Weekly</h3>
                    {{-- <div class="card-toolbar">
                        <a href="{{route('sp3.create')}}" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah Data</a>
                    </div> --}}
                </div>

                <div class="card-body">
                    <div class="form-group row mb-2">
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">Unit Kerja</label>
                            {!! Form::select('unitkerja', $pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'unitkerja']) !!}
                        </div>
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">PPB Muat</label>
                            {!! Form::select('ppbmuat', $ppb_muat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'ppbmuat']) !!}
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">Periode Pengiriman</label>
                            {!! Form::select('tahun', $periode, date('Y'), ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'tahun']) !!}
                        </div>
                        <div class="col-lg-6 custom-form" id="periode-minggu">
                            <label class="form-label col-sm-3 custom-label">Periode Minggu</label>
                            <select class="form-control form-select-solid col-sm-3" name="minggu" id="minggu">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">&nbsp;</label>
                            <button class="btn btn-primary" id="filter">Filter</button>
                        </div>
                    </div>
                </div>

                <div class="card-body py-5" id="data-weekly">

                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
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
</style>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script type="text/javascript">
	"use strict";

    var blockUI = new KTBlockUI(document.querySelector("#div-card"));

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

    function loadPeriodeMinggu(){
        $("#minggu").empty();
        $.ajax({
            type:"get",
            url: "{{ route('kalender-pengiriman.periode-minggu') }}?" + getParam(),
            data: {_token: "{{ csrf_token() }}"},
            success: function(result){
                $.each(result.periode_minggu, function(k, v){
                    var selected = '';
                    if(k == result.active_week){
                        selected = 'selected';
                    }
                    $("#minggu").append('<option ' + selected + ' value="' + k + '">' + v + '</option>')
                })
                $("#minggu").select2("destroy").select2();
            }
        });
    }

    function loadData(){
        blockUI.block();
        $.ajax({
            type:"post",
            url: "{{ route('kalender-pengiriman.detail-weekly-data') }}?" + getParam(),
            data: {_token: "{{ csrf_token() }}"},
            success: function(result){
                blockUI.release();
                $("#data-weekly").html(result)
            }
        });
    }

    function getParam(){
        var unitkerja = $("#unitkerja").val();
        var ppbmuat = $("#ppbmuat").val();
        var tahun = $("#tahun").val();
        var minggu = $("#minggu").val();
        return $.param({unitkerja, ppbmuat, tahun, minggu});
    }
</script>
@endsection
