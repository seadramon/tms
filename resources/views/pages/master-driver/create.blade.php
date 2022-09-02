@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Driver</h1>
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
                {!! Form::model($data, ['route' => ['master-driver.update', $data->id], 'class' => 'form', 'method' => 'put', 'enctype' => 'multipart/form-data']) !!}
            @else
                {!! Form::open(['url' => route('master-driver.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Tambah Baru Driver</h3>
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
                            <label class="form-label">Nama</label>
                            {!! Form::text('nama', null, ['class'=>'form-control', 'id'=>'nama', 'autocomplete'=>'off']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_lahir', null, ['class'=>'form-control datepicker', 'id'=>'tgl_lahir']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">No HP</label>
                            {!! Form::number('no_hp', null, ['class'=>'form-control', 'id'=>'no_hp']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Jenis SIM</label>
                            {!! Form::select('sim_jenis', $jenisSim, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'sim_jenis']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Foto SIM</label>
                            {!! Form::file('foto_sim', ['class'=>'form-control', 'id'=>'foto_sim']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Tanggal SIM Kadaluarsa</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('sim_expired', null, ['class'=>'form-control', 'id'=>'sim_expired']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Tanggal Bergabung</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_bergabung', null, ['class'=>'form-control datepicker', 'id'=>'tgl_bergabung']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('master-driver.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
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
        
    });

    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        autoApply: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    $("#sim_expired").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: new Date(),
        autoApply: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    $('.form-select-solid').select2();
</script>
@endsection