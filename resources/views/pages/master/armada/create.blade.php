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
                    <h3 class="card-title">@if (isset($data))Edit @else Tambah @endif Armada</h3>
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
                        <div class="form-group mb-3 col-lg-12">
                            <label class="form-label">Jenis Armada</label>
                            {!! Form::select('kd_armada', $jenis, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kd_armada']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tahun Pembuatan</label>
                            {!! Form::select('tahun', $tahun, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'tahun']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Nopol</label>
                            {!! Form::text('nopol', null, ['class'=>'form-control nopol', 'id'=>'nopol', 'autocomplete'=>'off']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Status</label>
                            {!! Form::select('status', $status, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'status']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Driver</label>
                            {!! Form::select('driver_id', $driver, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'driver_id']) !!}
                            <span class="text-danger">driver yang di pilih akan dilepas dari armada lain</span>
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Berlaku STNK</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_stnk', isset($data) ? Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->tgl_stnk)->format('d-m-Y') : null, ['class'=>'form-control datepicker', 'id'=>'tgl_stnk', 'data-inc-value' => "5", 'data-inc-type' => "years"]) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Perpanjang STNK</label>
                            {!! Form::text('tgl_stnk_expired', null, ['class'=>'form-control', 'id'=>'tgl_stnk_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Foto STNK</label>
                            {!! Form::file('foto_stnk', ['class'=>'form-control', 'id'=>'foto_stnk']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">&nbsp;</label>
                            {{-- {!! Form::text('stnk_not_verified', 'NOT VERIFIED YET', ['class'=>'form-control', 'id'=>'stnk_not_verified', 'disabled']) !!} --}}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Berlaku KIR Head</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_kir_head', isset($data) ? Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->tgl_kir_head)->format('d-m-Y') : null, ['class'=>'form-control datepicker', 'data-inc-value' => "6", 'data-inc-type' => "months", 'id'=>'tgl_kir_head']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Perpanjang KIR Head</label>
                            {!! Form::text('tgl_kir_head_expired', null, ['class'=>'form-control', 'id'=>'tgl_kir_head_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Foto KIR Head</label>
                            {!! Form::file('foto_kir_head', ['class'=>'form-control', 'id'=>'foto_kir_head']) !!}
                        </div>
                        
                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">&nbsp;</label>
                            {{-- {!! Form::text('kir_head_not_verified', 'NOT VERIFIED YET', ['class'=>'form-control', 'id'=>'kir_head_not_verified', 'disabled']) !!} --}}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Berlaku KIR Trailer</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_kir_trailer', isset($data) ? Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->tgl_kir_trailer)->format('d-m-Y') : null, ['class'=>'form-control datepicker', 'data-inc-value' => "6", 'data-inc-type' => "months", 'id'=>'tgl_kir_trailer']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Perpanjang KIR Trailer</label>
                            {!! Form::text('tgl_kir_trailer_expired', null, ['class'=>'form-control', 'id'=>'tgl_kir_trailer_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Foto KIR Trailer</label>
                            {!! Form::file('foto_kir_trailer', ['class'=>'form-control', 'id'=>'foto_kir_trailer']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">&nbsp;</label>
                            {{-- {!! Form::text('kir_trailer_not_verified', 'NOT VERIFIED YET', ['class'=>'form-control', 'id'=>'kir_trailer_not_verified', 'disabled']) !!} --}}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Berlaku Pajak</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_pajak', isset($data) ? Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->tgl_pajak)->format('d-m-Y') : null, ['class'=>'form-control datepicker', 'data-inc-value' => "1", 'data-inc-type' => "years", 'id'=>'tgl_pajak']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Perpanjang Pajak</label>
                            {!! Form::text('tgl_pajak_expired', null, ['class'=>'form-control', 'id'=>'tgl_pajak_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Foto Pajak</label>
                            {!! Form::file('foto_pajak', ['class'=>'form-control', 'id'=>'foto_pajak']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">&nbsp;</label>
                            {{-- {!! Form::text('pajak_not_verified', 'NOT VERIFIED YET', ['class'=>'form-control', 'id'=>'pajak_not_verified', 'disabled']) !!} --}}
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Foto STNK</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-200px me-3 images">
                                    <img src="{{asset('content/loader.gif')}}" data-src="{{ full_url_from_path($data->foto_stnk ?? 'foto_stnk.jpg') }}" class="lazy" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Foto KIR Head</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-200px me-3 images">
                                    <img src="{{asset('content/loader.gif')}}" data-src="{{ full_url_from_path($data->foto_kir_head ?? 'foto_kir_head.jpg') }}" class="lazy" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Foto KIR Trailer</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-200px me-3 images">
                                    <img src="{{asset('content/loader.gif')}}" data-src="{{ full_url_from_path($data->foto_kir_trailer ?? 'foto_kir_trailer.jpg') }}" class="lazy" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Foto Pajak</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-200px me-3 images">
                                    <img src="{{asset('content/loader.gif')}}" data-src="{{ full_url_from_path($data->foto_pajak ?? 'foto_pajak.jpg') }}" class="lazy" alt="">
                                </div>
                            </div>
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
        // getNextExpiredDate('tgl_stnk');
        // getNextExpiredDate('tgl_kir_head');
        // getNextExpiredDate('tgl_kir_trailer');
        // getNextExpiredDate('tgl_pajak');
        $(".datepicker").trigger('change');
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
        getNextExpiredDate($(this).attr('id'), $(this).attr('data-inc-value'), $(this).attr('data-inc-type'))
    });

    function getNextExpiredDate(id, inc, type){
        var date = $("#" + id).val();
        var idExpired = "#" + id + '_expired';
        if(date == ''){
            $(idExpired).val('');
        }else{
            $(idExpired).val(moment(date, 'DD-MM-YYYY').add(inc, type).format('DD-MM-YYYY'));
        }
    }
</script>
@endsection