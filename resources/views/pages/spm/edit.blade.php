@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Edit SPM</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12 mb-3" id="box1">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit SPM</h3>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 required ">No. SPP</label>
                            <input type="text" value="{{ $data->no_sppb }}" class="form-control" id="no_spp" name="no_spp" />
                        </div>
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">No. SPM</label>
                            <input class="form-control" type="text" value="{{ $data->no_spm }}" readonly="readonly" />
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 required ">Tanggal</label>
                            <input  name="tanggal" value="{{ date('d-m-Y',strtotime($data->tgl_spm)) }}"
                             class="form-control flatpickr-input active" placeholder="Pilih Tanggal" id="kt_datepicker_3" type="text" readonly="readonly">
                        </div>

                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 required ">Jenis SPM</label>
                            <select class="form-control" data-control="select2" data-placeholder="Pilih Jenis SPM" id="jenis_spm" readonly>
                                <option></option>
                                <option value="2"
                                    @if($data->jns_spm == 2)
                                        selected
                                    @endif
                                >Stok Titipan</option>
                                <option value="0"
                                    @if($data->jns_spm == 0)
                                        selected
                                    @endif
                                >Stok Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 required">PBB Muat</label>
                            {{-- <select class="form-control" data-control="select2"  data-placeholder="Pilih PBB Muat" name="pbb_muat" id="pbb_muat"></select> --}}
                            <input class="form-control" value="{{ $data->pat->kd_pat ?? '' }}|{{ $data->pat->ket ?? '' }}" type="text" readonly="readonly" name="pbb_muat" id="pbb_muat" />
                        </div>
                    </div>
                </div>

                <div class="card-footer" style="text-align: right;">
                    <input type="button" class="btn btn-primary" id="buat_draft" value="Show Detail">
                </div>
            </div>
        </div>
        <div class="col-12 mb-3" id="box2">

        </div>
    </div>
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

var target = document.querySelector("#kt_body");
var blockUI = new KTBlockUI(target, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
});

$(document).ready(function() {
    $("#alert-box1").hide();

    // Start field tanggal
    $("#kt_datepicker_3").flatpickr({
        "minDate": result.min,
        "maxDate": result.max,
        dateFormat: "d-m-Y"
    });
    // end of field tanggal
});


$('#buat_draft').on('click', function(){
    let data = {
        '_token': '{{ csrf_token() }}',
        'no_spp': $('#no_spp').val(),
        'tanggal': $('#kt_datepicker_3').val(),
        'pbb_muat': $('#pbb_muat').val(),
        'jenis_spm': $('#jenis_spm').val()
    };

    $.ajax({
        url: "{{ route('spm.get-data-edit-box2') }}",
        type: "POST",
        data: data,
        dataType: 'json',
        beforeSend: function() {
            blockUI.block();
        },
        complete: function() {
            blockUI.release();
        },
        success: function(result) {
            $('#box2').html(result.html);

            box2();
        },
        error: function(result) {
        }
    });
});


function validate_vol(el){
    var maxValue =  parseFloat($(el).attr('sppb')) - parseFloat($(el).attr('spm'));
    if($(el).val() > maxValue){
        $(el).val(maxValue);
    }
}

function box2() {
    $('.form-select-solid').select2();
    $('#jenis_spm_select').val($('#jenis_spm').val());
    $('#tanggal_select').val($('#kt_datepicker_3').val());
    $('#muat_select').val($('#pbb_muat').val());
    $('#spp_select').val($('#no_spp').val());

    $(document).on('click', '.delete_muat', function(e) {
        $(this).parent().parent().remove();
    });
}


</script>
