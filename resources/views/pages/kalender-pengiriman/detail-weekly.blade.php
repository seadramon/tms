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
                            {!! Form::select('pat', $pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'pat']) !!}
                        </div>
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">Unit Kerja</label>
                            {!! Form::select('pat', $pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'pat1']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">Periode Pengiriman</label>
                            {!! Form::select('periode', $periode, date('Y'), ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'periode']) !!}
                        </div>
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">Periode Minggu</label>
                            {!! Form::select('periode_minggu', $periode_minggu, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'periode1']) !!}
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
        loadData();
    });

    function loadData(){
        blockUI.block();
        $.ajax({
            type:"post",
            url: "{{ route('kalender-pengiriman.detail-weekly-data') }}",
            data: {_token: "{{ csrf_token() }}"},
            success: function(result){
                blockUI.release();
                $("#data-weekly").html(result)
            }
        });
    }
</script>
@endsection
