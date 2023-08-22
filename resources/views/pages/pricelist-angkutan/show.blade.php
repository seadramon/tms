@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Price List Angkutan</h1>
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
            {!! Form::model($data, ['route' => ['pricelist-angkutan.update', $data->id], 'class' => 'form', 'method' => 'PUT']) !!}

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Tambah Baru Price List Angkutan</h3>
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
                            <label class="form-label">Unit Kerja</label>
                            {!! Form::select('kd_pat', $kd_pat, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kd_pat', 'disabled']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Periode</label>
                            {!! Form::select('tahun', $tahun, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'tahun', 'disabled']) !!}
                        </div>
                    </div>

                    {{-- Repeater --}}
                    {{-- @include('pages.pricelist-angkutan.box-to-clone') --}}

                    <div id="container_pricelist_angkutan">
                        @foreach ($data->pad as $key => $pad)
                            <div id="box_1" class="box" data-id="{{ ($key+1) }}">
                                <div class="separator separator-dashed border-primary my-10"></div>
                                <div class="row mb-5">
                                    <div class="form-group col-lg-6">
                                        <label class="form-label">Jenis Angkutan</label>
                                        {!! Form::select('kd_material['.($key+1).']', $kd_material, $pad->kd_material, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kd_material_' . ($key+1), 'data-id'=>($key+1), 'disabled']) !!}
                                    </div>
            
                                    <div class="form-group col-lg-6">
                                        <label class="form-label">Jenis Pemuatan</label>
                                        {!! Form::select('jenis_muat['.($key+1).']', $jenis_muat, null, ['class'=>'form-control form-select-solid jenis_muat', 'data-control'=>'select2', 'id'=>'jenis_muat_' . ($key+1), 'data-id'=>($key+1), 'required', 'disabled']) !!}
                                    </div>
            
                                    <div class="form-group col-lg-3">
                                        <label class="form-label">Tanggal Mulai Berlaku</label>
                                        <div class="col-lg-12">
                                            <div class="input-group date">
                                                {!! Form::text('tgl_mulai['.($key+1).']', date('d-m-Y', strtotime($pad->tgl_mulai)), ['class'=>'form-control datepicker', 'id'=>'tgl_mulai_' . ($key+1), 'data-id'=>($key+1), 'disabled']) !!}
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="display: block">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="form-group col-lg-3">
                                        <label class="form-label">Tanggal Selesai Berlaku</label>
                                        <div class="col-lg-12">
                                            <div class="input-group date">
                                                {!! Form::text('tgl_selesai['.($key+1).']', date('d-m-Y', strtotime($pad->tgl_selesai)), ['class'=>'form-control datepicker', 'id'=>'tgl_selesai_' . ($key+1), 'data-id'=>($key+1), 'disabled']) !!}
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="display: block">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="form-group col-lg-6">
                                        <label class="form-label">Lokasi Pemuatan</label>
                                        <select class="form-control form-select-solid" data-control="select2" name="kd_muat[{{($key+1)}}]" id="kd_muat_{{ ($key+1) }}" data-id="{{ ($key+1) }}" disabled>
                                            <option value="">Pilih Jenis Pemuatan terlebih dahulu!</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="form-label">Vendor</label>
                                        {!! Form::select('vendor[' . ($key+1) . '][]', $vendor, $pad->vendors ? explode('|', $pad->vendors) : null, ['class'=>'form-control form-select-solid vendor', 'data-control'=>'select2', 'id'=>'vendor_' . ($key+1), 'data-id'=>($key+1), 'multiple' => true, 'disabled']) !!}
                                    </div>
                                </div>

                                <div id="container_harsat_{{ ($key+1) }}" data-id="{{ ($key+1) }}">
                                    <label class="form-label">Harga Satuan</label>

                                    <table class="table table-row-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th width='10%'>No.</th>
                                                <th width='30%'>Range Jarak</th>
                                                @if (session('TMP_KDWIL') == '0A')
                                                    <th width='30%'>Rencana Harsat Pusat</th>
                                                @endif
                                                <th width='30%'>Harsat Final</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <input type="hidden" name="count_harsat[{{($key+1)}}]" class="count_harsat" value="{{ count($pad->pad2) }}">

                                            @foreach ($pad->pad2 as $key2 => $pad2)
                                                <tr>
                                                    <td>
                                                        {{ ($key2+1) }}
                                                        <input type="hidden" name="key_harsat[{{($key+1)}}][]" value="{{ $key2 }}">
                                                    </td>
                                                    <td>
                                                        {{ $pad2->range_min }} - {{ $pad2->range_max }}
                                                        <input type="hidden" name="range_min[{{($key+1)}}][]" value="{{ $pad2->range_min }}">
                                                        <input type="hidden" name="range_max[{{($key+1)}}][]" value="{{ $pad2->range_max }}">
                                                    </td>
                                                    @if (session('TMP_KDWIL') == '0A')
                                                    <td>
                                                        {{ number_format($pad2->h_pusat) }}
                                                        <input type="hidden" name="h_pusat[{{($key+1)}}][]" value="{{ $pad2->h_pusat }}">
                                                    </td>
                                                    @endif
                                                    <td>
                                                        {{ number_format($pad2->h_final) }}
                                                        <input type="hidden" name="h_final[{{($key+1)}}][]" value="{{ $pad2->h_final }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                        @endforeach
                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('pricelist-angkutan.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
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

    .form-group{
        margin-bottom: 10px;
    }

    #container_pricelist_angkutan {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .btn-add-delete {
        margin-top: 30px; 
        text-align: right;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script type="text/javascript">
    let pad = {!! json_encode($data->pad)  !!};

    $(document).ready(function() {
        $.each(pad, function(key, value) {
            $('#jenis_muat_' + (key+1)).val(value.jenis_muat).trigger('change', value.kd_muat);
        });
    });

    $('.form-select-solid').select2();
    
    var target = document.querySelector("#block-ui-loading");
            
    var blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
    });

    $(document).on('change', '.jenis_muat', function(e, kd_muat = null){
        let dataId = $(this).data('id');

        $.ajax({
            url: "{{ route('pricelist-angkutan.get-lokasi-pemuatan') }}",
            type: "POST",
            data: {
                '_token': '{{ csrf_token() }}',
                'jenis_muat': $('#jenis_muat_' + dataId).val()
            },
            dataType: 'json',
            beforeSend: function() {
                if(!kd_muat || $('.blockui').length == 0){
                    blockUI.block();
                }
            },
            complete: function() {
                blockUI.release();
            },
            success: function(result) {
                $('#kd_muat_' + dataId).html('');

                $.each(result, function(index, value) {
                    if($('#jenis_muat_' + dataId).val() == 'site'){
                        $('#kd_muat_' + dataId).append(`
                            <option value="`+ index +`">`+ index + ' - ' + value +`</option>
                        `);
                    }else{
                        $('#kd_muat_' + dataId).append(`
                            <option value="`+ index +`">`+ value +`</option>
                        `);
                    }
                });

                if(kd_muat){
                    $('#kd_muat_' + dataId).val(kd_muat).trigger('change');
                }
            },
            error: function(result) {
            }
        });
    });
</script>
@endsection