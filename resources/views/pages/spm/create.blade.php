@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SPM</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <div class="col-12 mb-md-5 mb-xl-10">
        <form class="form-control" method="POST" enctype="multipart/form-data" action="">
            <div id="box1">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Baru SPM</h3>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-danger alert-dismissible fade" id="alert-box1" role="alert">
                            SPP, PPB Muat, Tanggal dan Jenis SPM harus diisi !
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 required ">No. SPP</label>
                                <select class="form-control" data-control="select2" name="no_spp" id="no_spp"  data-placeholder="Pilih No. SPP">
                                    <option></option>
                                    @foreach ( $no_spp as $row)
                                        <option value="{{ $row->no_sppb }}">{{ $row->no_sppb }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 custom-label">No. SPM</label>
                                <input class="form-control" type="text" value="AUTO" readonly />
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 required ">Tanggal</label>
                                <input  name="tanggal" class="form-control flatpickr-input active" placeholder="Pilih Tanggal" id="kt_datepicker_3" type="text" readonly="readonly">
                            </div>

                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 required ">Jenis SPM</label>
                                <select class="form-control" data-control="select2" data-placeholder="Pilih Jenis SPM" name="jenis_spm" id="jenis_spm">
                                    <option></option>
                                    <option value="2">Stok Titipan</option>
                                    <option value="0">Stok Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 required">PBB Muat</label>
                                <select class="form-control" data-control="select2"  data-placeholder="Pilih PBB Muat" name="pbb_muat" id="pbb_muat">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="text-align: right;">
                        <input type="button" class="btn btn-primary" id="buat_draft" value="Buat Draft">
                    </div>
                </div>
            </div>
        </form>

        <div id="box2">

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
});

// Start field tanggal
$("#kt_datepicker_3").flatpickr({
    dateFormat: "d-m-Y",
});
// end of field tanggal


$('#no_spp').on("change", function(e) {
    var no_spp = ($('#no_spp :selected').val());
    $.ajax({
        type: 'POST',
        url: "{{ route('spm.getPbbMuat') }}",
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        data: {
            no_spp: no_spp,
        },
        success: function(result){
            $('#pbb_muat').empty();
            if(result){
                for(i = 0; i < result.length; i++){
                    $('#pbb_muat').append('<option value="'+ result[i].pat.kd_pat +'">'+ result[i].pat.kd_pat +' | '+ result[i].pat.ket +'</option>');
                }
            }

        }
    });
});

$('#buat_draft').on('click', function(){
    if(!$('#no_spp').val() || !$('#pbb_muat').val() || !$('#jenis_spm').val() || !$('#kt_datepicker_3').val() ){
        $("#alert-box1").show();
        $("#alert-box1").addClass("show");

        setTimeout(function() {
            $("#alert-box1").hide();
        }, 5000);

        return false;
    }else{
        let data = {
            '_token': '{{ csrf_token() }}',
            'no_spp': $('#no_spp').val(),
            'tanggal': $('#kt_datepicker_3').val(),
            'pbb_muat': $('#pbb_muat').val(),
            'jenis_spm': $('#jenis_spm').val()
        };

        $.ajax({
            url: "{{ route('spm.get-data-box2') }}",
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

                // box2();
            },
            error: function(result) {
            }
        });
    }
    });

</script>

@endsection
