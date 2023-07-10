@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SPP</h1>
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
                    <h3 class="card-title">LIST SPP</h3>
                    <div class="card-toolbar">
                        <a href="{{route('spp.monitor-approval')}}" class="btn btn-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Monitor Approval</a>&nbsp;
                        @if (in_array('create', json_decode(session('TMS_ACTION_MENU'))))
                            <a href="{{ route('spp.create') }}" class="btn btn-success" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah SPP</a>
                        @endif
                    </div>
                </div>

                <div class="card-body py-5">
                    <table id="tabel_spp" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th rowspan="2" class="hidden">NO SPP</th>
                                <th rowspan="2">NO SPP</th>
                                <th rowspan="2">NPP</th>
                                <th rowspan="2">NO SPPRB</th>
                                <th rowspan="2">NO SP3</th>
                                <th rowspan="2">TUJUAN</th>
                                <th rowspan="2">JADWAL AWAL</th>
                                <th rowspan="2">JADWAL AKHIR</th>
                                <th colspan="2" style="text-align: center">PROGRESS</th>
                                <th rowspan="2">APP</th>
                                <th rowspan="2">Option</th>
                            </tr>

                            <tr class="fw-semibold fs-6 text-muted">
                                <th>VOL</th>
                                <th>WAKTU</th>
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
            dt = $("#tabel_spp").DataTable({
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
                ajax: "{{ route('spp.data') }}",
                columns: [
                    {data: 'created_date', name: 'created_date', defaultContent: '-', class: "hidden"},
                    {data: 'no_sppb', name: 'no_sppb', defaultContent: '-'},
                    {data: 'spprb.no_npp', name: 'spprb.no_npp', defaultContent: '-'},
                    {data: 'no_spprb', name: 'no_spprb', defaultContent: '-'},
                    {data: 'no_sp3', name: 'no_sp3', defaultContent: '-',orderable: false, searchable: false},
                    {data: 'tujuan', name: 'tujuan', defaultContent: '-'},
                    {data: 'jadwal1', name: 'jadwal1', defaultContent: '-'},
                    {data: 'jadwal2', name: 'jadwal2', defaultContent: '-'},
                    {data: 'vol', name: 'vol', defaultContent: '-',orderable: false, searchable: false},
                    {data: 'waktu', name: 'waktu', defaultContent: '-',orderable: false, searchable: false},
                    {data: 'approval', name: 'approval', defaultContent: '-', orderable: false, searchable: false},
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
                url: "{{ url('spp/destroy') }}",
                data: {id : id, _token: "{{ csrf_token() }}"},
                success: function(res){
                    if (res.result == 'success') {
                        flasher.success("Data telah berhasil dihapus!");

                        $('#tabel_tipe_pc').DataTable().ajax.url("{{ route('spp.data') }}").load();
                    }
                }
            });
        }
    });
</script>
@endsection