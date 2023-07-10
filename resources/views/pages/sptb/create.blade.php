@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SPTB</h1>
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
            {!! Form::open(['url' => route('sptb.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Tambah Baru SPTB</h3>
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

                    <div class="form-group row">
                        {{-- <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-2 custom-label">No. SPTB</label>
                            {!! Form::text('no_sptb', 'AUTO', ['class'=>'form-control col-sm-4 custom-width', 'id'=>'no_sptb', 'disabled']) !!}
                        </div> --}}
                        
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-2 custom-label">Jenis SPM</label>
                            {!! Form::text('jenis_spm', 'STOK AKTIF', ['class'=>'form-control col-sm-4 custom-width', 'id'=>'jenis_spm', 'disabled']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            @php
                                $spm_list = $no_spm;
                                if($spm){
                                    $spm_list += [$spm => $spm];
                                }
                            @endphp
                            <label class="form-label">No. SPM</label>
                            {!! Form::select('no_spm', $spm_list, $spm, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_spm']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Jenis SPTB</label>
                            {!! Form::select('jns_sptb', $jns_sptb, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'jns_sptb', 'readonly']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Nama Pelanggan</label>
                            {!! Form::text('nama_pelanggan', null, ['class'=>'form-control', 'id'=>'nama_pelanggan', 'readonly']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Lokasi Proyek</label>
                            {!! Form::text('tujuan', null, ['class'=>'form-control', 'id'=>'tujuan', 'readonly']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Nama Proyek</label>
                            {!! Form::text('nama_proyek', null, ['class'=>'form-control', 'id'=>'nama_proyek', 'readonly']) !!}
                        </div>
                        
                        <div class="form-group col-lg-6">
                            <label class="form-label">Perusahaan / Pemilik Angkutan</label>
                            {!! Form::text('angkutan', null, ['class'=>'form-control', 'id'=>'angkutan', 'readonly']) !!}
                        </div>

                        <div class="form-group col-lg-3">
                            <label class="form-label">Berangkat Tanggal</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('tgl_berangkat', null, ['class'=>'form-control', 'id'=>'tgl_berangkat']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="form-group col-lg-3">
                            <label class="form-label">Jam</label>
                            <div class="col-lg-12">
                                <div class="input-group date">
                                    {!! Form::text('jam_berangkat', null, ['class'=>'form-control', 'id'=>'jam_berangkat']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">No. Polisi Kendaraan</label>
                            {!! Form::text('no_pol', null, ['class'=>'form-control', 'id'=>'no_pol', 'readonly']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Dari Pabrik</label>
                            {!! Form::text('dari_pabrik', null, ['class'=>'form-control', 'id'=>'dari_pabrik', 'readonly']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Nama Driver</label>
                            {!! Form::text('nama_driver', null, ['class'=>'form-control', 'id'=>'nama_driver', 'readonly']) !!}
                        </div>
                        
                        <div class="form-group col-lg-6">
                            <label class="form-label">No. HP Driver</label>
                            {!! Form::text('no_hp_driver', null, ['class'=>'form-control', 'id'=>'no_hp_driver', 'readonly']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Jarak</label>
                            {!! Form::text('jarak_km', null, ['class'=>'form-control', 'id'=>'jarak_km', 'readonly']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea name="ket" id="ket" rows="5" class="col-md-12"></textarea>
                    </div>

                    <table class="table table-bordered" id="table-produk" style="margin-top: 30px;">
                        <thead>
                            <tr>
                                <th width='50%'>Tipe Produk</th>
                                <th width='20%'>Volume</th>
                                <th width='30%'>Tanggal Produksi</th>
                            </tr>
                        </thead>
                    </table>

                    <div class="row" id="container-produk">
                        
                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('sptb.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
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
<style>
    .custom-form {
        display: flex;
    }

    .custom-label {
        display: flex; 
        align-items: center;
        margin-bottom: 0px;
    }

    .custom-width{
        width: 85%;
    }

    #jenis_spm {
        text-align: center;
        color: white;
        background-color: red;
    }

    .form-group{
        margin-bottom: 10px;
    }

    #table-produk thead {
        background-color: rgb(0, 119, 255);
        color: white;
        border: 1px solid white;
    }

    #table-produk thead tr th {
        padding-left: 10px;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script type="text/javascript">
    $('.form-select-solid').select2();

    $("#tgl_berangkat").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: 1901,
        autoApply: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    $("#jam_berangkat").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
    
    var target = document.querySelector("#kt_body");
            
    var blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
    });
    
    $(document).ready(function() {
        if("{{$spm}}" != ""){
            $('#no_spm').trigger('change');
        }
    });
    
    $('#no_spm').on('change', function(){
        $.ajax({
            url: "{{ route('sptb.get-spm') }}",
            type: "POST",
            data: {
                '_token': '{{ csrf_token() }}', 
                'no_spm': $('#no_spm').val(),
            },
            beforeSend: function() {
                blockUI.block();
            },
            complete: function() {
                blockUI.release();
            },
            success: function(result) {
                $('#jns_sptb').val(result.spm.jns_spm).trigger('change');
                $('#nama_pelanggan').val(result.spm.sppbh?.npp?.nama_pelanggan);

                // if(typeof result.spm.sppbh?.npp?.infoPasar?.region?.kabupaten_name !== typeof undefined){
                //     $('#tujuan').val(result.spm.sppbh?.npp?.infoPasar?.region?.kabupaten_name + ', ' + result.spm.sppbh?.npp?.infoPasar?.region?.kecamatan_name);
                // }
                $('#tujuan').val(result.spm.sppbh.tujuan);
                
                $('#nama_proyek').val(result.spm.sppbh?.npp?.nama_proyek);
                $('#angkutan').val(result.spm.vendor?.nama);
                $('#no_pol').val(result.spm.no_pol);
                $('#dari_pabrik').val(result.spm.pat?.ket);
                $('#nama_driver').val(result.spm.app2_name);
                $('#no_hp_driver').val(result.spm.app2_hp);
                $('#jarak_km').val(result.spm.jarak_km);
                
                $('#container-produk').html('');

                for(i=0; i<result.spm.spmd?.length; i++){
                    var volume = result.spm.spmd[i].vol;
                    
                    $('#container-produk').append(`
                        <div class="form-group col-lg-6">
                            <input type="text" class="form-control" value="` + result.spm.spmd[i].kd_produk + ' - ' + result.spm.spmd[i].produk.tipe + `" readonly style="background-color: var(--kt-input-disabled-bg);">
                            <input type="hidden" name="kd_produk[]" class="form-control" value="` + result.spm.spmd[i].kd_produk + `">
                        </div>
                        <div class="form-group col-lg-3">
                            <input type="text" name="vol[]" class="form-control" value="` + volume + `" readonly style="background-color: var(--kt-input-disabled-bg);">
                        </div>
                        <div class="form-group col-lg-3">
                            <div class="input-group date">
                                <input type="text" data-id=` + i + ` name="tgl_produksi[]" class="form-control tgl_produksi datepicker">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="display: block">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    `);

                    if(volume > 0){
                        $('#container-produk').append(`
                            <label class="form-label col-lg-6">Tanggal Produksi</label>
                            <label class="form-label col-lg-6">No. Produk</label>
                        `);
                    }

                    for(j=0; j<volume; j++){
                        $('#container-produk').append(`
                            <div class="form-group col-lg-6">
                                <div class="input-group date">
                                    <input type="text" name="child_tgl_produksi[]" class="form-control datepicker child_tgl_produksi_` + i + `">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <input type="text" name="child_kd_produk[]" required class="form-control" value="">
                            </div>
                        `);
                    }
                }

                $(".datepicker").daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    minDate: 1901,
                    autoApply: true,
                    locale: {
                        format: 'DD-MM-YYYY'
                    }
                });

                $('.tgl_produksi').on('change', function(){
                    $('.child_tgl_produksi_' + $(this).data('id')).val($(this).val()).trigger('change');
                });
            },
            error: function(result) {
                console.log(result)
            }
        });
    });
</script>
@endsection