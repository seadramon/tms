@extends('layout.layout2')

@section('page-title')
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Evaluasi Vendor</h1>
</div>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="row g-5 g-xl-8">
        <div class="col-12 mb-md-5 mb-xl-10">
            <div id="box1" style="margin-bottom: 20px">
                @include('pages.report.proyek-berjalan.box1')
            </div>

            <div id="box2" style="display: none">
                @include('pages.report.proyek-berjalan.box2')
            </div>
        </div>
    </div>
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

    .form-group {
        margin-bottom: 5px;
    }

    .dt-buttons {
        float: right;
        display: block;
    }

    p {
        display: inline;
        font-weight: bold;
    }

    .box2-style1 {
        font-size: 60px;
    }

    .box2-style2 {
        font-size: 30px;
    }

    .box2-style3 {
        font-size: 15px;
    }

    .box2-style4 {
        font-size: 15px;
        font-weight: normal;
    }
    tbody tr td {
        border: solid 0.5px black!important;
    }
    .table tbody tr:last-child td, .table tbody tr:last-child th, .table tfoot tr:last-child td, .table tfoot tr:last-child th {
        border: solid 0.5px black!important;
    }
</style>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/fusion/js/fusioncharts.js') }}"></script>
<script src="{{ asset('assets/fusion/js/themes/fusioncharts.theme.fusion.js') }}"></script>
<script src="{{ asset('assets/fusion/js/jquery-fusioncharts.min.js') }}"></script>
<script type="text/javascript">
	"use strict";

    var isShowBox2 = false;

    var target = document.querySelector(".box-ui-loading-chart");
            
    var blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
    });

    var targetBox = document.querySelector(".box-ui-loading-box-data");
            
    var blockUIBox = new KTBlockUI(targetBox, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
    });

    $(document).ready(function() {
        loadPeriodeMinggu();
        $("#tahun").change(function(){
            loadPeriodeMinggu();
        });
    });
	// Class definition
	var DT_proyek_berjalan = function () {
	    // Shared variables
	    var table;
	    var dt;
	    var filterPayment;

	    // Private functions
	    var initDatatable = function () {
	        dt = $("#tabel_proyek_berjalan").DataTable({
				language: {
  					lengthMenu: "Show _MENU_",
 				},
 				dom: 'Blfrtip',
	            searchDelay: 500,
	            processing: true,
	            serverSide: true,
	            // order: [[0, 'asc']],
                "ordering": false,
	            stateSave: true,
                // searching: true,
                buttons: [
                    // {
                    //     extend: 'excel',
                    //     text: 'Export Excel',
                    //     className: 'btn-sm btn-success',
                    //     action: exportDatatables
                    // },
                    // {
                    //     extend: 'pdf',
                    //     text: 'Export PDF',
                    //     className: 'btn-sm btn-danger',
                    //     action: exportDatatables
                    // }
                ],
	            ajax: {
                    url: "{{ route('report-proyek-berjalan.data') }}",
                    type: "POST",
                    data: function(d){
                        d._token = '{{ csrf_token() }}';
                        d.kd_pat = $("#kd_pat").val();
                        d.pekerjaan = $("#pekerjaan").val();
                        d.vendor_id = $("#vendor_id").val();
                        d.tipe = $("#tipe").val();
                        d.tahun2 = $("#tahun2").val();
                        d.range = $("#range").val();
                        d.month = $("#month").val();
                    }
                },
	            columns: [
	                // {data: 'tgl_sp3', name: 'tgl_sp3', defaultContent: '-', class: "hide hidden"},
                    {
                        "mData": "pelanggan",
                        "mRender": function (data, type, row) {
                            return row.nama_pelanggan + "<br><br>" + row.nama_proyek + "<br><br>" + row.no_npp;
                        },
                        orderable: false,
                        searchable: false,
                    },
	                {data: 'tipe', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'panjang', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'vol_kontrak', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'vol_produksi', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'vol_distribusi', defaultContent: '-', orderable: false, searchable: false},
                    {
                        "mData": "stock_pabrik",
                        "mRender": function (data, type, row) {
                            return parseInt(row.vol_produksi) - parseInt(row.vol_distribusi);
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        "mData": "stock_site",
                        "mRender": function (data, type, row) {
                            // return parseInt(row.vol_produksi) - parseInt(row.vol_distribusi);
                            return 0;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        "mData": "gagal_site",
                        "mRender": function (data, type, row) {
                            // return parseInt(row.vol_produksi) - parseInt(row.vol_distribusi);
                            return 0;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        "mData": "prog_produksi_qty",
                        "mRender": function (data, type, row) {
                            // return parseInt(row.vol_produksi) - parseInt(row.vol_distribusi);
                            return 0;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        "mData": "prog_produksi_bobot",
                        "mRender": function (data, type, row) {
                            // return parseInt(row.vol_produksi) - parseInt(row.vol_distribusi);
                            return "0%";
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        "mData": "prog_distribusi_qty",
                        "mRender": function (data, type, row) {
                            // return parseInt(row.vol_produksi) - parseInt(row.vol_distribusi);
                            return 0;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        "mData": "prog_distribusi_bobot",
                        "mRender": function (data, type, row) {
                            // return parseInt(row.vol_produksi) - parseInt(row.vol_distribusi);
                            return "0%";
                        },
                        orderable: false,
                        searchable: false,
                    },
	                {data: 'pabrik', defaultContent: '-', orderable: false, searchable: false},
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

    $(document).on('click', '#filter', function(){
        if(!isShowBox2){
            isShowBox2 = true;
            $('#box2').show();
            DT_proyek_berjalan.init();
            $('#div_proyek_berjalan').show();
            if($("#tipe").val() == "sp3"){
            }
        }
        $('#tabel_proyek_berjalan').DataTable().ajax.reload();            
        
        // getChart();
    });

    $(".datepicker").daterangepicker({
        showDropdowns: true,
        minYear: 1901,
        autoApply: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });


    function exportDatatables(e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;

        dt.one('preXhr', function (e, s, data) {
            // Just this once, load all data from the server...
            data.start = 0;
            data.length = 2147483647;

            dt.one('preDraw', function (e, settings) {
                // Call the original action function
                if (button[0].className.indexOf('buttons-excel') >= 0) {
                    $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                    $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                }

                dt.one('preXhr', function (e, s, data) {
                    // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                    // Set the property to what it was before exporting.
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });

                // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                setTimeout(dt.ajax.reload, 0);
                
                // Prevent rendering of the full data to the DOM
                return false;
            });
        });

        // Requery the server with the new one-time export settings
        dt.ajax.reload();
    }

    function getParam(){
        var unitkerja = $("#unitkerja").val();
        var ppbmuat = $("#ppbmuat").val();
        var tahun1 = $("#tahun1").val();
        var minggu1 = $("#minggu1").val();
        var minggu2 = $("#minggu2").val();
        return $.param({unitkerja, ppbmuat, tahun, minggu1, minggu2});
    }

    function loadPeriodeMinggu(){
        $("#minggu1").empty();
        $("#minggu2").empty();
        $.ajax({
            type:"get",
            url: "{{ route('kalender-pengiriman.periode-minggu') }}?type=date&tahun=" + $("#tahun").val(),
            data: {_token: "{{ csrf_token() }}"},
            success: function(result){
                $.each(result.periode_minggu, function(k, v){
                    $("#minggu1").append('<option value="' + k + '">' + v + '</option>')
                    $("#minggu2").append('<option value="' + k + '">' + v + '</option>')
                })
                $("#minggu1").select2("destroy").select2();
                $("#minggu2").select2("destroy").select2();
            }
        });
    }
</script>
@endsection