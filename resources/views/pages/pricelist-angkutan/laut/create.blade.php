@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Price List Angkutan Laut</h1>
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
            @if ($mode == 'create')
                {!! Form::open(['url' => route('pricelist-angkutan.store-laut'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            @else
                {!! Form::model($data, ['route' => ['pricelist-angkutan.update-laut', $data->id], 'class' => 'form', 'method' => 'PUT']) !!}
                @method("PUT")
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Tambah Baru Price List Angkutan Laut</h3>
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
                            {{-- <a href="{{ asset('template/pricelist.xlsx') }}" class="btn btn-success">
                                Download Template Price List
                            </a> --}}
                        </div>
                        <div class="form-group col-lg-6" style="text-align: right">
                            <button type="button" class="btn btn-light-primary btn_tambah">
                                <i class="la la-plus"></i>Tambah Detail
                            </button>
                        </div>
                    </div>

                    {{-- Repeater --}}
                    @include('pages.pricelist-angkutan.laut.box-to-clone')

                    <div id="container_pricelist_angkutan_laut">
                        @if ($mode == "create")
                            <div id="box_1" class="box" data-id="1">
                                <div class="separator separator-dashed border-primary my-10"></div>
                                <div class="row mb-5">
                                    <div class="form-group col-lg-6">
                                        <input type="hidden" name="index[1]" id="index_1" value="1">
                                    </div>
                                    <div class="form-group col-lg-6" style="text-align: right;">
                                        <button type="button" class="btn btn-light-danger btn_hapus mt-8" id="btn_hapus_1" data-id="1">
                                            <i class="la la-trash"></i>Hapus
                                        </button>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="form-label">Tanggal Mulai Berlaku</label>
                                        <div class="col-lg-12">
                                            <div class="input-group date">
                                                {!! Form::text('tgl_mulai[1]', $awal, ['class'=>'form-control datepicker', 'id'=>'tgl_mulai_1', 'data-id'=>'1']) !!}
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
                                                {!! Form::text('tgl_selesai[1]', $akhir, ['class'=>'form-control datepicker', 'id'=>'tgl_selesai_1', 'data-id'=>'1']) !!}
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="display: block">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6" style="text-align: right;">
                                        <button type="button" class="btn btn-light-dark add_harsat mt-8" id="add_harsat_1" data-id="1">
                                            <i class="la la-plus"></i>Tambah Harga Satuan
                                        </button>
                                    </div>
                                </div>

                                <div id="container_harsat_1" data-id="1">
                                    <label class="form-label">Harga Satuan</label>

                                    <table class="table table-row-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>Kondisi Penyerahan</th>
                                                <th>Unit Asal</th>
                                                <th>Pelabuhan Asal</th>
                                                <th>Pelabuhan Tujuan</th>
                                                <th>Site</th>
                                                <th>Harsat Final</th>
                                                <th>Satuan</th>
                                            </tr>
                                        </thead>

                                        <tbody class="tbody-harsat" id="tbody-harsat-1">
                                        </tbody>
                                    </table>    
                                </div>
                            </div>
                        @else
                            @foreach ($data->pad as $index => $pad)
                                <div id="box_{{$index+1}}" class="box" data-id="{{$index+1}}">
                                    <div class="separator separator-dashed border-primary my-10"></div>
                                    <div class="row mb-5">
                                        <div class="form-group col-lg-6">
                                            <input type="hidden" name="index[{{$index+1}}]" id="index_{{$index+1}}" value="{{$index+1}}">
                                        </div>
                                        <div class="form-group col-lg-6" style="text-align: right;">
                                            <button type="button" class="btn btn-light-danger btn_hapus mt-8" id="btn_hapus_{{$index+1}}" data-id="{{$index+1}}">
                                                <i class="la la-trash"></i>Hapus
                                            </button>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label class="form-label">Tanggal Mulai Berlaku</label>
                                            <div class="col-lg-12">
                                                <div class="input-group date">
                                                    {!! Form::text('tgl_mulai[' . ($index+1) . ']', date('d-m-Y', strtotime($pad->tgl_mulai)), ['class'=>'form-control datepicker', 'id'=>'tgl_mulai_{{$index+1}}', 'data-id'=>'{{$index+1}}']) !!}
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
                                                    {!! Form::text('tgl_selesai[' . ($index+1) . ']', date('d-m-Y', strtotime($pad->tgl_selesai)), ['class'=>'form-control datepicker', 'id'=>'tgl_selesai_{{$index+1}}', 'data-id'=>'{{$index+1}}']) !!}
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" style="display: block">
                                                            <i class="la la-calendar-check-o"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-6" style="text-align: right;">
                                            <button type="button" class="btn btn-light-dark add_harsat mt-8" id="add_harsat_{{$index+1}}" data-id="{{$index+1}}">
                                                <i class="la la-plus"></i>Tambah Harga Satuan
                                            </button>
                                        </div>
                                    </div>

                                    <div id="container_harsat_{{$index+1}}" data-id="{{$index+1}}">
                                        <label class="form-label">Harga Satuan</label>

                                        <table class="table table-row-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>Kondisi Penyerahan</th>
                                                    <th>Unit Asal</th>
                                                    <th>Pelabuhan Asal</th>
                                                    <th>Pelabuhan Tujuan</th>
                                                    <th>Site</th>
                                                    <th>Harsat Final</th>
                                                    <th>Satuan</th>
                                                </tr>
                                            </thead>

                                            <tbody class="tbody-harsat" id="tbody-harsat-{{$index+1}}">
                                                @foreach ($pad->pad2 as $pad2)
                                                    <tr>
                                                        <td><input name="kondisi[{{$index+1}}][]" class="kondisi" type="hidden" value="{{$pad2->kondisi}}">{{$kondisi[$pad2->kondisi]}}</td> 
                                                        <td><input name="unit[{{$index+1}}][]" class="unit" type="hidden" value="{{$pad2->unit}}">{{$pad2->unit_->ket}}</td> 
                                                        <td><input name="pelabuhan_asal[{{$index+1}}][]" class="pelabuhan_asal" type="hidden" value="{{$pad2->port_asal}}">{{$pad2->port_asal}}</td> 
                                                        <td><input name="pelabuhan_tujuan[{{$index+1}}][]" class="pelabuhan_tujuan" type="hidden" value="{{$pad2->port_tujuan}}">{{$pad2->port_tujuan}}</td> 
                                                        <td><input name="site[{{$index+1}}][]" class="site" type="hidden" value="{{$pad2->site}}">{{$pad2->site}}</td>
                                                        <td><input name="hargasatuan[{{$index+1}}][]" class="hargasatuan" type="hidden" value="{{$pad2->h_final}}">{{number_format($pad2->h_final)}}</td> 
                                                        <td><input name="satuan[{{$index+1}}][]" class="satuan" type="hidden" value="{{$pad2->satuan}}">{{$pad2->satuan}}</td> 
                                                        <td><button class="btn btn-danger btn-sm delete_harsat me-1 mb-1" style="padding: 5px 6px;"><span class="bi bi-trash"></span></button><button class="btn btn-warning btn-sm edit_harsat me-1 mb-1" data-index="{{$index+1}}" style="padding: 5px 6px;"><span class="bi bi-pencil-square"></span></button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>    
                                    </div>
                                </div>
                            @endforeach
                        @endif
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
@include('pages.pricelist-angkutan.laut.modal_harsat')
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

    #container_pricelist_angkutan_laut {
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

    $(document).ready(function() {
        $('.form-select-modal-solid').select2({
            dropdownParent: $("#modal_harsat")
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

    $(document).on('click', '.btn_tambah', function(){
        let dataId = $(this).data('id');
        
        var newIndex = parseInt($('.box:last').data('id')) + 1;

        // find box to copy
        var box = document.getElementById('box_to_clone');

        // copy children too
        var clone = box.cloneNode(true);

        //Set Box Id
        clone.id = 'box_' + newIndex;
        
        //Set Id and name
        // clone.getElementsByTagName('select')[0].id = 'kd_material_' + newIndex;
        // clone.getElementsByTagName('select')[0].name = 'kd_material[' + newIndex + ']';
        // clone.getElementsByTagName('select')[1].id = 'jenis_muat_' + newIndex;
        // clone.getElementsByTagName('select')[1].name = 'jenis_muat[' + newIndex + ']';
        // clone.getElementsByTagName('select')[2].id = 'kd_muat_' + newIndex;
        // clone.getElementsByTagName('select')[2].name = 'kd_muat[' + newIndex + ']';
        // clone.getElementsByTagName('select')[3].id = 'vendor_' + newIndex;
        // clone.getElementsByTagName('select')[3].name = 'vendor[' + newIndex + '][]';
        clone.getElementsByTagName('input')[0].id = 'index_' + newIndex;
        clone.getElementsByTagName('input')[0].name = 'index[' + newIndex + ']';
        clone.getElementsByTagName('input')[1].id = 'tgl_mulai_' + newIndex;
        clone.getElementsByTagName('input')[1].name = 'tgl_mulai[' + newIndex + ']';
        clone.getElementsByTagName('input')[2].id = 'tgl_selesai_' + newIndex;
        clone.getElementsByTagName('input')[2].name = 'tgl_selesai[' + newIndex + ']';
        clone.getElementsByTagName('button')[0].id = 'btn_hapus_' + newIndex;
        clone.getElementsByTagName('button')[1].id = 'add_harsat_' + newIndex;
        // clone.getElementsByTagName('button')[2].id = 'btn_tambah_' + newIndex;
        clone.getElementsByTagName('div')[13].id = 'container_harsat_' + newIndex;
        clone.getElementsByTagName('tbody')[0].id = 'tbody-harsat-' + newIndex;

        // add new box to end of div
        $('#container_pricelist_angkutan_laut').append(clone);

        //set value on index
        $('#index_' + newIndex).val(newIndex);
        
        //Set Data Id
        $('#box_' + newIndex).attr('data-id', newIndex);
        $('#tgl_mulai_' + newIndex).attr('data-id', newIndex);
        $('#tgl_selesai_' + newIndex).attr('data-id', newIndex);
        $('#add_harsat_' + newIndex).attr('data-id', newIndex);
        $('#btn_hapus_' + newIndex).attr('data-id', newIndex);
        $('#container_harsat_' + newIndex).attr('data-id', newIndex);

        //Set Required
        // $('#jenis_muat_' + newIndex).attr('required');
        // $('#kd_muat_' + newIndex).attr('required');
        // $('#vendor_' + newIndex).attr('required');
        
        //Remove Disable
        $('#tgl_mulai_' + newIndex).removeAttr('disabled');
        $('#tgl_selesai_' + newIndex).removeAttr('disabled');
        
        
        //Handle duplicate select2
        if(isFirstAdd){
            // $('#box_' + newIndex).find('.select2-container').eq(1).remove();
            // $('#box_' + newIndex).find('.select2-container').eq(2).remove();
            // $('#box_' + newIndex).find('.select2-container').eq(3).remove();
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

    $(document).on('click', '.add_harsat', function(event){
        resetModalHarsat()
        $("#modal_for_index").val($(this).attr('data-id'));
        $('#modal_harsat').modal('toggle');
    });

    $(document).on('click', '.delete_harsat', function(event){
        event.preventDefault();
        $(this).parent().parent().remove();
    });
    $(document).on('click', '.edit_harsat', function(event){
        event.preventDefault();
        resetModalHarsat()

        $(this).parent().parent().addClass('editing');
        $("#modal_for_index").val($(this).attr('data-index'));
        $("#modal_for").val("edit");
        $("#modal_harsat_btn").text("Edit");

        $("#modal_kondisi").val($(this).parent().parent().find("input.kondisi").val()).trigger("change");
        $("#modal_unit").val($(this).parent().parent().find("input.unit").val()).trigger("change");
        $("#modal_site").val($(this).parent().parent().find("input.site").val()).trigger("change");
        $("#modal_pelabuhan_asal").val($(this).parent().parent().find("input.pelabuhan_asal").val()).trigger("change");
        $("#modal_pelabuhan_tujuan").val($(this).parent().parent().find("input.pelabuhan_tujuan").val()).trigger("change");
        $("#modal_satuan").val($(this).parent().parent().find("input.satuan").val()).trigger("change");
        $("#modal_hargasatuan").val($(this).parent().parent().find("input.hargasatuan").val());
        $("#modal_hargasatuan").trigger('keyup');
        $('#modal_harsat').modal('toggle');
        // calculateTotal();
    });

    function resetModalHarsat(){
        $(".modal-select2").val("").trigger("change");
        $(".modal-text").val("");
        $("#modal_for").val("add");
        $("#modal_harsat_btn").text("Add");
    }
    
    $('#modal_harsat_submit').on('click', function(e){
        e.preventDefault();
        var index = $("#modal_for_index").val();
        var data_ = modalHarsatData();
        // kd_jpekerjaan
        var table_row = "<td><input name=\"kondisi[" + index + "][]\" class=\"kondisi\" type=\"hidden\" value=\"" + data_.kondisi + "\">" + data_.kondisi_teks + "</td>" + 
            "<td><input name=\"unit[" + index + "][]\" class=\"unit\" type=\"hidden\" value=\"" + data_.unit + "\">" + data_.unit_teks + "</td>" + 
            "<td><input name=\"pelabuhan_asal[" + index + "][]\" class=\"pelabuhan_asal\" type=\"hidden\" value=\"" + data_.pelabuhan_asal + "\">" + data_.pelabuhan_asal + "</td>" + 
            "<td><input name=\"pelabuhan_tujuan[" + index + "][]\" class=\"pelabuhan_tujuan\" type=\"hidden\" value=\"" + data_.pelabuhan_tujuan + "\">" + data_.pelabuhan_tujuan + "</td>" + 
            "<td><input name=\"site[" + index + "][]\" class=\"site\" type=\"hidden\" value=\"" + data_.site + "\">" + data_.site + "</td>" +
            "<td><input name=\"hargasatuan[" + index + "][]\" class=\"hargasatuan\" type=\"hidden\" value=\"" + data_.hargasatuan + "\">" + data_.hargasatuan + "</td>" + 
            "<td><input name=\"satuan[" + index + "][]\" class=\"satuan\" type=\"hidden\" value=\"" + data_.satuan + "\">" + data_.satuan + "</td>" + 
            "<td><button class=\"btn btn-danger btn-sm delete_harsat me-1 mb-1\" style=\"padding: 5px 6px;\"><span class=\"bi bi-trash\"></span></button><button class=\"btn btn-warning btn-sm edit_harsat me-1 mb-1\" data-index=\"" + index + "\" style=\"padding: 5px 6px;\"><span class=\"bi bi-pencil-square\"></span></button></td>";
        
        if($("#modal_for").val() == "add"){
            $("#tbody-harsat-" + index).append(
                "<tr>" + table_row + "</tr>"
            );
        }else{
            $(".editing").html(table_row);
            $(".editing").removeClass("editing");
        }
        $('#modal_harsat').modal('toggle');
    });

    function modalHarsatData(){
        var unit = $("#modal_unit").val();
        var unit_teks = $("#modal_unit option:selected").text();
        var pelabuhan_asal = $("#modal_pelabuhan_asal").val();
        var pelabuhan_tujuan = $("#modal_pelabuhan_tujuan").val();
        var site = $("#modal_site").val();
        var kondisi = $("#modal_kondisi").val();
        var kondisi_teks = $("#modal_kondisi option:selected").text();
        var hargasatuan = $("#modal_hargasatuan").val();
        var satuan = $("#modal_satuan").val();
        
        return {
            unit: unit,
            unit_teks: unit_teks,
            pelabuhan_asal: pelabuhan_asal,
            pelabuhan_tujuan: pelabuhan_tujuan,
            site: site,
            kondisi: kondisi,
            kondisi_teks: kondisi_teks,
            hargasatuan: hargasatuan,
            satuan: satuan
        };
    }

</script>
@endsection