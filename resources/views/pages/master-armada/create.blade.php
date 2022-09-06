@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Armada</h1>
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
            @if (isset($data))
                {!! Form::model($data, ['route' => ['master-armada.update', $data->id], 'class' => 'form', 'method' => 'put', 'enctype' => 'multipart/form-data']) !!}
            @else
                {!! Form::open(['url' => route('master-armada.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Tambah Baru Armada</h3>
                </div>
            
                <div class="card-body">
                    @if (count($errors) > 0)
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="form-label">Jenis Armada</label>
                            {!! Form::select('jenis', $jenis, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'jenis']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Detail</label>
                            {!! Form::select('detail', $detail, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'detail']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tahun Pembuatan</label>
                            {!! Form::select('tahun', $tahun, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'tahun']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Nopol</label>
                            {!! Form::text('nopol', null, ['class'=>'form-control nopol', 'id'=>'nopol', 'autocomplete'=>'off']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Status</label>
                            {!! Form::select('status', $status, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'status']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Driver</label>
                            {!! Form::select('driver_id', $driver, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'driver_id']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tanggal Berlaku STNK</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_stnk', isset($data) ? Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->tgl_stnk)->format('d-m-Y') : null, ['class'=>'form-control datepicker', 'id'=>'tgl_stnk']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tanggal Perpanjang STNK</label>
                            {!! Form::text('tgl_stnk_expired', null, ['class'=>'form-control', 'id'=>'tgl_stnk_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Foto STNK</label>
                            {!! Form::file('foto_stnk', ['class'=>'form-control', 'id'=>'foto_stnk']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">&nbsp;</label>
                            {!! Form::text('stnk_not_verified', 'NOT VERIFIED YET', ['class'=>'form-control', 'id'=>'stnk_not_verified', 'disabled']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tanggal Berlaku KIR Head</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_kir_head', isset($data) ? Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->tgl_kir_head)->format('d-m-Y') : null, ['class'=>'form-control datepicker', 'id'=>'tgl_kir_head']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tanggal Perpanjang KIR Head</label>
                            {!! Form::text('tgl_kir_head_expired', null, ['class'=>'form-control', 'id'=>'tgl_kir_head_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Foto KIR Head</label>
                            {!! Form::file('foto_kir_head', ['class'=>'form-control', 'id'=>'foto_kir_head']) !!}
                        </div>
                        
                        <div class="form-group col-lg-3">
                            <label class="form-label">&nbsp;</label>
                            {!! Form::text('kir_head_not_verified', 'NOT VERIFIED YET', ['class'=>'form-control', 'id'=>'kir_head_not_verified', 'disabled']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tanggal Berlaku KIR Trailer</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_kir_trailer', isset($data) ? Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->tgl_kir_trailer)->format('d-m-Y') : null, ['class'=>'form-control datepicker', 'id'=>'tgl_kir_trailer']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tanggal Perpanjang KIR Trailer</label>
                            {!! Form::text('tgl_kir_trailer_expired', null, ['class'=>'form-control', 'id'=>'tgl_kir_trailer_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Foto KIR Trailer</label>
                            {!! Form::file('foto_kir_trailer', ['class'=>'form-control', 'id'=>'foto_kir_trailer']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">&nbsp;</label>
                            {!! Form::text('kir_trailer_not_verified', 'NOT VERIFIED YET', ['class'=>'form-control', 'id'=>'kir_trailer_not_verified', 'disabled']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tanggal Berlaku Pajak</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_pajak', isset($data) ? Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->tgl_pajak)->format('d-m-Y') : null, ['class'=>'form-control datepicker', 'id'=>'tgl_pajak']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Tanggal Perpanjang Pajak</label>
                            {!! Form::text('tgl_pajak_expired', null, ['class'=>'form-control', 'id'=>'tgl_pajak_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Foto Pajak</label>
                            {!! Form::file('foto_pajak', ['class'=>'form-control', 'id'=>'foto_pajak']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">&nbsp;</label>
                            {!! Form::text('pajak_not_verified', 'NOT VERIFIED YET', ['class'=>'form-control', 'id'=>'pajak_not_verified', 'disabled']) !!}
                        </div>
                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('master-armada.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    <input type="submit" class="btn btn-success" value="Simpan">
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
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        getNextExpiredDate('tgl_stnk');
        getNextExpiredDate('tgl_kir_head');
        getNextExpiredDate('tgl_kir_trailer');
        getNextExpiredDate('tgl_pajak');
    });

    $('.form-select-solid').select2();

    Inputmask({
        "regex" : "([A-Z]{1,2}) ([0-9]{1,4}) ([A-Z]{0,3})"
    }).mask("#nopol");

    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: 1901,
        autoApply: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    }).on("change", function (e) {
        getNextExpiredDate($(this).attr('id'))
    });

    function getNextExpiredDate(id){
        var date = $("#" + id).val();
        var day = date.substr(0, 2);
        var month = date.substr(3, 2);
        var year = date.substr(6,4);

        var nextExpiredDate = day + '-' + month + '-' + (parseInt(year)+5);

        var idExpired = "#" + id + '_expired';

        $(idExpired).val(nextExpiredDate);
    }
</script>
@endsection