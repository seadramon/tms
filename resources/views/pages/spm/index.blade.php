@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SPM</h1>
</div>
<!--end::Page title-->
@endsection
@section('css')
     <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12 mb-md-5 mb-xl-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">LIST SPM</h3>
                    <div class="card-toolbar">
                        @if (in_array('create', json_decode(session('TMS_ACTION_MENU'))))
                            <a href="{{ route('spm.create') }}" class="btn btn-success" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah SPM</a>
                        @endif
                    </div>
                </div>

                <div class="card-body py-5">
                    <table id="tabel_spm" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>NO SPM</th>
                                <th>NO SPP</th>
                                <th>NPP</th>
                                <th>TANGGAL</th>
                                <th>VENDOR</th>
                                <th>NOPOL</th>
                                <th>STATUS</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
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

<!--begin::Modal - Create Api Key-->
<div class="modal fade" id="modal_konfirmasi" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="modal_konfirmasi_header">
                <!--begin::Modal title-->
                <h2>Konfirmasi</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Form-->
            <form method="post" id="modal_konfirmasi_form" class="form" action="{{ route('spm.konfirmasi') }}">
                <input type="hidden" name="no_spm" id="no_spm">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-10">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="modal_konfirmasi_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_konfirmasi_header" data-kt-scroll-wrappers="#modal_konfirmasi_scroll" data-kt-scroll-offset="300px">
                        
                        <div class="form-group row">
                            <div class="col-lg-12 custom-form">
                                <label class="form-label col-sm-3 custom-label">Jalur</label>
                                {!! Form::select('jalur[]', $vendor, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'jalur_id', 'multiple' => 'multiple']) !!}
                            </div>
                        </div>

                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->

                <!--begin::Modal footer-->
                <div class="modal-footer flex-right">
                    <button type="submit" id="modal_konfirmasi_submit" class="btn btn-primary">
                        <span class="indicator-label">Konfirmasi</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--begin::Modal - Create Api Key-->
<div class="modal fade" id="modal_armada" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="modal_armada_header">
                <!--begin::Modal title-->
                <h2>Form Penilaian VSMS</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Form-->
            <form method="post" id="modal_armada_form" class="form" action="{{ route('spm.armada-tiba') }}">
                <input type="hidden" name="no_spm" id="armada_no_spm">
                <input type="hidden" name="type" value="with-form">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-10">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="modal_armada_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_armada_header" data-kt-scroll-wrappers="#modal_armada_scroll" data-kt-scroll-offset="300px">
                        <table style="width: 100%;" class="table table-bordered">
                            <thead>
                                <tr style="font-weight: bold">
                                    <th style="width: 10%; border: solid 1px black; text-align: center;">No</th>
                                    <th style="width: 35%; border: solid 1px black;">Kriteria</th>
                                    <th style="width: 35%; border: solid 1px black;">Deskripsi</th>
                                    <th style="width: 10%; border: solid 1px black; text-align: center;">Ya
                                    </th>
                                    <th style="width: 10%; border: solid 1px black; text-align: center;">Tidak
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($criterias as $index => $criteria)
                                    <tr>
                                        <td style="width: 10%; border: solid 1px black; text-align: center;">{{$index+1}}.</td>
                                        <td style="width: 35%; border: solid 1px black;">{{$criteria->criteria}}</td>
                                        <td style="width: 35%; border: solid 1px black;">{{$criteria->description}}</td>
                                        <td style="width: 10%; border: solid 1px black; text-align: center;">
                                            <input class="form-check-input criteria-radio" name="{{$criteria->code}}" type="radio" data-type="yes" value="{{$criteria->bobot}}" id="flexRadioDefault"/>
                                        </td>
                                        <td style="width: 10%; border: solid 1px black; text-align: center;">
                                            <input class="form-check-input criteria-radio" name="{{$criteria->code}}" type="radio" data-type="no" value="0" id="flexRadioDefault"/>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->

                <!--begin::Modal footer-->
                <div class="modal-footer flex-right">
                    <button type="submit" id="modal_armada_submit" class="btn btn-primary">
                        <span class="indicator-label">Konfirmasi Armada Tiba</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Create Api Key-->
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    "use strict";

    // Class definition
    var KTDatatablesServerSide = function () {
        // Shared variables
        var table;
        var dt;
        var filterPayment;

        // Private functions
        var initDatatable = function () {
            dt = $("#tabel_spm").DataTable({
                language: {
                    lengthMenu: "Show _MENU_",
                },
                dom:
                    "<'row'" +
                    "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                    ">" +

                    "<'table-responsive'tr>" +

                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">",
                searchDelay: 500,
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                stateSave: true,
                ajax: "{{ route('spm.data') }}",
                columns: [
                    {data: 'no_spm', name: 'no_spm', defaultContent: '-'},
                    {data: 'no_sppb', name: 'no_sppb', defaultContent: '-'},
                    {data: 'sppb.no_npp', name: 'sppb.no_npp', defaultContent: '-',orderable: false, searchable: false},
                    {data: 'tgl_spm', name: 'tgl_spm', defaultContent: '-'},
                    {data: 'vendornya.nama', name: 'vendornya.nama', defaultContent: '-',orderable: false, searchable: false},
                    {data: 'no_pol', name: 'no_pol', defaultContent: '-'},
                    {data: 'status', name: 'status', defaultContent: '-',orderable: false, searchable: false},
                    {data: 'menu', orderable: false, searchable: false}
                ],
            });

            table = dt.$;
        }
        
        // Public methods
        return {
            init: function () {
                initDatatable();
            }
        }
    }();

    // On document ready
    KTUtil.onDOMContentLoaded(function () {
        KTDatatablesServerSide.init();
    });

    $(document).on("click", ".konfirmasi", function () {
        var id = $(this).data('id');
        var pat = $(this).data('pat');
        $("#no_spm").val(id);

        $('#jalur_id').val(null).trigger('change');
        var data = [{
            id: 1,
            text: 'Barn owl'
        }];

        $.ajax({
            type:"get",
            url: "{{ route('spm.select-pat') }}?pat=" + pat,
            success: function(res) {
                $("#jalur_id").select2({
                    data: res
                })     
            }
        });
    });
    
    $(document).on("click", ".armada-tiba", function () {
        var spm = $(this).data('spm');
        $.ajax({
            type:"get",
            url: "{{ route('spm.armada-tiba-validation') }}?no_spm=" + spm,
            success: function(res) {
                // alert(res.filled)  
                if(res.filled){
                    swal({
                        title: "Apakah Armada Sudah Tiba?",
                        text: "Konfirmasi Armada Tiba",
                        icon: "success",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('spm.armada-tiba') }}",
                                headers: {
                                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                                },
                                data: {
                                        type: 'without-form',
                                        no_spm : spm,
                                    },
                                success: function(result) {
                                    // swal("Menu Successfully Update");
                                    if(result.success){
                                        flasher.success("Konfirmasi Armada Tiba berhasil!");
                                        $('#tabel_spm').DataTable().ajax.url("{{ route('spm.data') }}").load();
                                    }else{
                                        flasher.error(result.message);
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        } else {
                            swal("Batal", {
                                icon: "success",
                            }); 
                        }
                    });
                }else{
                    $(".criteria-radio[data-type=yes]").prop('checked', true);
                    $("#armada_no_spm").val(spm);
                    $('#modal_armada').modal('toggle');
                }
            }
        });
    });

    $("#modal_armada_form").submit(function(event) {
        event.preventDefault();

        $("#modal_armada_submit").attr("data-kt-indicator", "on");

        let data = $(this).serialize();
        let url = $(this).attr('action');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"post",
            url: url,
            data: data,
            success: function(result) {
                if(result.success){
                    $("#modal_armada_submit").removeAttr("data-kt-indicator");
                    $('#modal_armada').modal('toggle');
                    flasher.success("Konfirmasi Armada Tiba berhasil!");
                    $('#tabel_spm').DataTable().ajax.url("{{ route('spm.data') }}").load();
                }else{
                    flasher.error(result.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#modal_armada_submit").removeAttr("data-kt-indicator");

                $('#modal_armada').modal('toggle');
                flasher.error("Konfirmasi gagal!");
            }
        })
    });

    $("#modal_konfirmasi_form").submit(function(event) {
        event.preventDefault();

        $("#modal_konfirmasi_submit").attr("data-kt-indicator", "on");

        let data = $(this).serialize();
        let url = $(this).attr('action');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"post",
            url: url,
            data: data,
            success: function(res) {
                $("#modal_konfirmasi_submit").removeAttr("data-kt-indicator");

                $('#modal_konfirmasi').modal('toggle');
                flasher.success("Konfirmasi berhasil!");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#modal_konfirmasi_submit").removeAttr("data-kt-indicator");

                $('#modal_konfirmasi').modal('toggle');
                flasher.error("Konfirmasi gagal!");
            }
        })
    });

    $('body').on('click', '.delete', function () {
        if (confirm("Delete Record?") == true) {
            var id = $(this).data('id');

            // ajax
            $.ajax({
                type:"post",
                url: "{{ url('spm/destroy') }}",
                data: {id : id, _token: "{{ csrf_token() }}"},
                success: function(res){
                    if (res.result == 'success') {
                        flasher.success("Data telah berhasil dihapus!");

                        $('#tabel_spm').DataTable().ajax.url("{{ route('spm.data') }}").load();
                    }
                }
            });
        }
    });
</script>
@endsection