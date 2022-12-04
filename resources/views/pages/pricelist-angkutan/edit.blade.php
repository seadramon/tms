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
                            {!! Form::select('kd_pat', $kd_pat, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kd_pat']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label">Periode</label>
                            {!! Form::select('tahun', $tahun, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'tahun']) !!}
                        </div>

                        <div class="form-group col-lg-6">
                            <a href="{{ asset('template/pricelist.xlsx') }}" class="btn btn-success">
                                Download Template Price List
                            </a>
                        </div>
                        <div class="form-group col-lg-6" style="text-align: right">
                            <button type="button" class="btn btn-light-primary btn_tambah">
                                <i class="la la-plus"></i>Tambah Detail
                            </button>
                        </div>
                    </div>

                    {{-- Repeater --}}
                    @include('pages.pricelist-angkutan.box-to-clone')

                    <div id="container_pricelist_angkutan">
                        @foreach ($data->pad as $key => $pad)
                            <div id="box_{{ ($key+1) }}" class="box" data-id="{{ ($key+1) }}">
                                <div class="separator separator-dashed border-primary my-10"></div>
                                <div class="row mb-5">
                                    <div class="form-group col-lg-6">
                                        <input type="hidden" name="index[{{($key+1)}}]" id="index_{{($key+1)}}" value="{{($key+1)}}">
                                    </div>
                                    <div class="form-group col-lg-6" style="text-align: right;">
                                        <button type="button" class="btn btn-light-danger btn_hapus mt-8" id="btn_hapus_{{($key+1)}}" data-id="{{($key+1)}}">
                                            <i class="la la-trash"></i>Hapus
                                        </button>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="form-label">Jenis Angkutan</label>
                                        {!! Form::select('kd_material[' . ($key+1) . ']', $kd_material, $pad->kd_material, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kd_material_' . ($key+1), 'data-id'=>($key+1)]) !!}
                                    </div>
            
                                    <div class="form-group col-lg-6">
                                        <label class="form-label">Jenis Pemuatan</label>
                                        {!! Form::select('jenis_muat[' . ($key+1) . ']', $jenis_muat, null, ['class'=>'form-control form-select-solid jenis_muat', 'data-control'=>'select2', 'id'=>'jenis_muat_' . ($key+1), 'data-id'=>($key+1), 'required']) !!}
                                    </div>
            
                                    <div class="form-group col-lg-3">
                                        <label class="form-label">Tanggal Mulai Berlaku</label>
                                        <div class="col-lg-12">
                                            <div class="input-group date">
                                                {!! Form::text('tgl_mulai[' . ($key+1) . ']', date('d-m-Y', strtotime($pad->tgl_mulai)), ['class'=>'form-control datepicker', 'id'=>'tgl_mulai_' . ($key+1), 'data-id'=>($key+1)]) !!}
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
                                                {!! Form::text('tgl_selesai[' . ($key+1) . ']', date('d-m-Y', strtotime($pad->tgl_selesai)), ['class'=>'form-control datepicker', 'id'=>'tgl_selesai_' . ($key+1), 'data-id'=>($key+1)]) !!}
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
                                        <select class="form-control form-select-solid" data-control="select2" name="kd_muat[{{ ($key+1) }}]" id="kd_muat_{{ ($key+1) }}" data-id="{{ ($key+1) }}" required>
                                            <option value="">Pilih Jenis Pemuatan terlebih dahulu!</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label class="form-label">Upload File Excel (Harga Satuan)</label>
                                        {!! Form::file('file_excel[' . ($key+1) . ']', ['class'=>'form-control', 'id'=>'file_excel_' . ($key+1), 'data-id'=>($key+1)]) !!}
                                    </div>
            
                                    <div class="form-group col-lg-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" id="upload_excel_{{ ($key+1) }}" data-id="{{ ($key+1) }}" class="btn btn-success form-control upload_excel">
                                            Upload Excel
                                        </button>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="form-label">Vendor</label>
                                        {!! Form::select('vendor[' . ($key+1) . '][]', $vendor, $pad->vendors ? explode('|', $pad->vendors) : null, ['class'=>'form-control form-select-solid vendor', 'data-control'=>'select2', 'id'=>'vendor_' . ($key+1), 'data-id'=>($key+1), 'multiple' => true, 'required']) !!}
                                    </div>
                                </div>

                                <div id="container_harsat_{{ ($key+1) }}" data-id="{{ ($key+1) }}">
                                    <label class="form-label">Harga Satuan</label>

                                    <table class="table table-row-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th width='10%'>No.</th>
                                                <th width='30%'>Range Jarak</th>
                                                <th width='30%'>Rencana Harsat Pusat</th>
                                                <th width='30%'>Harsat Final</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <input type="hidden" name="count_harsat[{{ ($key+1) }}]" class="count_harsat" value="{{ count($pad->pad2) }}">

                                            @foreach ($pad->pad2 as $key2 => $pad2)
                                                <tr>
                                                    <td>
                                                        {{ ($key2+1) }}
                                                        <input type="hidden" name="key_harsat[{{ ($key+1) }}][]" value="{{ $key2 }}">
                                                    </td>
                                                    <td>
                                                        {{ $pad2->range_min }} - {{ $pad2->range_max }}
                                                        <input type="hidden" name="range_min[{{ ($key+1) }}][]" value="{{ $pad2->range_min }}">
                                                        <input type="hidden" name="range_max[{{ ($key+1) }}][]" value="{{ $pad2->range_max }}">
                                                    </td>
                                                    <td>
                                                        {{ number_format($pad2->h_pusat) }}
                                                        <input type="hidden" name="h_pusat[{{ ($key+1) }}][]" value="{{ $pad2->h_pusat }}">
                                                    </td>
                                                    <td>
                                                        {{ number_format($pad2->h_final) }}
                                                        <input type="hidden" name="h_final[{{ ($key+1) }}][]" value="{{ $pad2->h_final }}">
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
    let isFirstAdd = true;
    let pad = {!! json_encode($data->pad)  !!};

    $(document).ready(function() {
        $.each(pad, function(key, value) {
            $('#jenis_muat_' + (key+1)).val(value.jenis_muat).trigger('change', value.kd_muat);
        });
    });

    $('.form-select-solid').select2();

    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: 1901,
        autoApply: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });
    
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

    $(document).on('click', '.upload_excel', function(){
        let dataId = $(this).data('id');
        let fileExcel = $('#file_excel_' + dataId).prop('files')[0];
        
        if (typeof fileExcel != typeof undefined) {
            let formData = new FormData();
            
            formData.append('file_excel', fileExcel);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('index', dataId);

            $.ajax({
                url: "{{ route('pricelist-angkutan.upload-excel') }}",
                type: "POST",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    blockUI.block();
                },
                complete: function() {
                    blockUI.release();
                },
                success: function(result) {
                    $('#container_harsat_' + dataId).html(result.html);
                },
                error: function(result) {
                }
            });
        }else{
            alert('File excel harus dipilih!')
        }
    });

    $(document).on('click', '.btn_tambah', function(){
        let dataId = $(this).data('id');
        
        var newIndex = parseInt($('.box:last').data('id')) + 1;
        console.log(newIndex);
        // find box to copy
        var box = document.getElementById('box_to_clone');

        // copy children too
        var clone = box.cloneNode(true);

        //Set Box Id
        clone.id = 'box_' + newIndex;
        
        //Set Id
        clone.getElementsByTagName('select')[0].id = 'kd_material_' + newIndex;
        clone.getElementsByTagName('select')[0].name = 'kd_material[' + newIndex + ']';
        clone.getElementsByTagName('select')[1].id = 'jenis_muat_' + newIndex;
        clone.getElementsByTagName('select')[1].name = 'jenis_muat[' + newIndex + ']';
        clone.getElementsByTagName('select')[2].id = 'kd_muat_' + newIndex;
        clone.getElementsByTagName('select')[2].name = 'kd_muat[' + newIndex + ']';
        clone.getElementsByTagName('select')[3].id = 'vendor_' + newIndex;
        clone.getElementsByTagName('select')[3].name = 'vendor[' + newIndex + '][]';
        clone.getElementsByTagName('input')[0].id = 'index_' + newIndex;
        clone.getElementsByTagName('input')[0].name = 'index[' + newIndex + ']';
        clone.getElementsByTagName('input')[1].id = 'tgl_mulai_' + newIndex;
        clone.getElementsByTagName('input')[1].name = 'tgl_mulai[' + newIndex + ']';
        clone.getElementsByTagName('input')[2].id = 'tgl_selesai_' + newIndex;
        clone.getElementsByTagName('input')[2].name = 'tgl_selesai[' + newIndex + ']';
        clone.getElementsByTagName('input')[3].id = 'file_excel_' + newIndex;
        clone.getElementsByTagName('input')[3].name = 'file_excel[' + newIndex + ']';
        clone.getElementsByTagName('button')[0].id = 'upload_excel_' + newIndex;
        clone.getElementsByTagName('button')[1].id = 'btn_hapus_' + newIndex;
        // clone.getElementsByTagName('button')[2].id = 'btn_tambah_' + newIndex;
        clone.getElementsByTagName('div')[18].id = 'container_harsat_' + newIndex;

        // add new box to end of div
        $('#container_pricelist_angkutan').append(clone);


        //set value on index
        $('#index_' + newIndex).val(newIndex);
        
        //Set Data Id
        $('#box_' + newIndex).attr('data-id', newIndex);
        $('#kd_material_' + newIndex).attr('data-id', newIndex);
        $('#jenis_muat_' + newIndex).attr('data-id', newIndex);
        $('#kd_muat_' + newIndex).attr('data-id', newIndex);
        $('#tgl_mulai_' + newIndex).attr('data-id', newIndex);
        $('#tgl_selesai_' + newIndex).attr('data-id', newIndex);
        $('#file_excel_' + newIndex).attr('data-id', newIndex);
        $('#upload_excel_' + newIndex).attr('data-id', newIndex);
        $('#btn_hapus_' + newIndex).attr('data-id', newIndex);
        // $('#btn_tambah_' + newIndex).attr('data-id', newIndex);
        $('#container_harsat_' + newIndex).attr('data-id', newIndex);

        //Set Required
        $('#jenis_muat_' + newIndex).attr('required');
        $('#kd_muat_' + newIndex).attr('required');
        $('#vendor_' + newIndex).attr('required');
        
        //Remove Disable
        $('#kd_material_' + newIndex).removeAttr('disabled');
        $('#jenis_muat_' + newIndex).removeAttr('disabled');
        $('#kd_muat_' + newIndex).removeAttr('disabled');
        $('#tgl_mulai_' + newIndex).removeAttr('disabled');
        $('#tgl_selesai_' + newIndex).removeAttr('disabled');
        $('#file_excel_' + newIndex).removeAttr('disabled');
        
        //Set Select2
        $('#kd_material_' + newIndex).select2();
        $('#jenis_muat_' + newIndex).select2();
        $('#kd_muat_' + newIndex).select2();
        $('#vendor_' + newIndex).select2();
        
        //Handle duplicate select2
        if(isFirstAdd){
            $('#box_' + newIndex).find('.select2-container').eq(1).remove();
            $('#box_' + newIndex).find('.select2-container').eq(2).remove();
            $('#box_' + newIndex).find('.select2-container').eq(3).remove();
            // $('#box_' + newIndex).find('.select2-container').last().remove();

            isFirstAdd = false;
        }

        //Set Datepicker
        $(".datepicker").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minDate: 1901,
            autoApply: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        
        //Show New Box
        $('#box_' + newIndex).show();
    });

    $(document).on('click', '.btn_hapus', function(){
        let dataId = $(this).data('id');

        if($('.box').length > 2){
            $('#box_' + dataId).remove();
        }else{
            alert("Tidak bisa hapus, minimal terdapat 1 data!")
        }
    });

    $("form").submit(function(event) {
        if($('.box').length-1 != $('.count_harsat').length){
            alert("File Excel Harga Satuan harus diupload!");

            return false;
        }
    });
</script>
@endsection