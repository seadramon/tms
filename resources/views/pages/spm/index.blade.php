@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SPM</h1>
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
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">LIST SPM</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('spm.create') }}" class="btn btn-success" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah SPM</a>
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