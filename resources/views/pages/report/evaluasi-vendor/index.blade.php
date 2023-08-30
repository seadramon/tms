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
                @include('pages.report.evaluasi-vendor.box1')
            </div>

            <div id="box2" style="display: none">
                @include('pages.report.evaluasi-vendor.box2')
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

	// Class definition
	var KTDatatablesServerSide_sp3 = function () {
	    // Shared variables
	    var table;
	    var dt;
	    var filterPayment;

	    // Private functions
	    var initDatatable = function () {
	        dt = $("#tabel_evaluasi_vendor_sp3").DataTable({
				language: {
  					lengthMenu: "Show _MENU_",
 				},
 				dom: 'Blfrtip',
	            searchDelay: 500,
	            processing: true,
	            serverSide: true,
	            // order: [[0, 'asc']],
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
                    url: "{{ route('report-evaluasi-vendor.data-sp3') }}",
                    type: "POST",
                    data: function(d){
                        d._token = '{{ csrf_token() }}';
                        d.kd_pat = $("#kd_pat").val();
                        d.pekerjaan = $("#pekerjaan").val();
                        d.vendor_id = $("#vendor_id").val();
                        d.tipe = $("#tipe").val();
                        d.periode = $("#periode").val();
                    }
                },
	            columns: [
	                // {data: 'tgl_sp3', name: 'tgl_sp3', defaultContent: '-', class: "hide hidden"},
	                {data: 'bulan', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'no_sp3', defaultContent: '-'},
	                {data: 'volume', defaultContent: '-'},
	                {data: 'ket', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'volume', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'volume_diterima', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'volume_rusak', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'nilai_mutu', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'tepat_waktu', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'terlambat', defaultContent: '-', orderable: false, searchable: false},
                    {
                        "mData": "aspek_waktu",
                        "mRender": function (data, type, row) {
                            // console.log(row);
                            // console.log('DATA ' + data);
                            var nilai = 0;
                            if(parseInt(row.tepat_waktu) < 50){
                                nilai = 50;
                            }else if(parseInt(row.tepat_waktu) > 70){
                                nilai = 90;
                            }else{
                                nilai = 70;
                            }
                            return nilai;
                        },
                        orderable: false,
                        searchable: false,
                    },
	                {data: 'aspek_pelayanan', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'aspek_k3l', defaultContent: '-', orderable: false, searchable: false},
                    {
                        "mData": "nilai_total",
                        "mRender": function (data, type, row) {
                            var wkt = 0;
                            if(parseInt(row.tepat_waktu) < 50){
                                wkt = 50;
                            }else if(parseInt(row.tepat_waktu) > 70){
                                wkt = 90;
                            }else{
                                wkt = 70;
                            }
                            return ((wkt + parseInt(row.nilai_mutu) + parseInt(row.aspek_pelayanan) + parseInt(row.aspek_k3l)) / 4).toFixed(2);
                        },
                        orderable: false,
                        searchable: false,
                    },
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
            KTDatatablesServerSide_sp3.init();
            if($("#tipe").val() == "sp3"){
            }
        }
        if($("#tipe").val() == "sp3"){
            $('#tabel_evaluasi_vendor_sp3').DataTable().ajax.reload();            

            $('#div_evaluasi_vendor_sp3').show();
            $('#div_evaluasi_vendor_monthly').hide();
            $('#div_evaluasi_vendor_semester').hide();
        }else if($("#tipe").val() == "semester"){
            $.ajax({
                type: "post",
                url: "{{ route('report-evaluasi-vendor.data-vendor-semester') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kd_pat: $("#kd_pat").val(),
                    pekerjaan: $("#pekerjaan").val(),
                    vendor_id: $("#vendor_id").val(),
                    tipe: $("#tipe").val(),
                    periode: $("#periode").val()
                },
                success: function(res) {
                    $("#tbody-semester").html("");

                    $("#tbody-semester").html(res);

                    blockUI.release();
                },
                error: function(res) {
                    console.log(res);
                    blockUI.release();
                }
            })
            $('#div_evaluasi_vendor_sp3').hide();
            $('#div_evaluasi_vendor_monthly').hide();
            $('#div_evaluasi_vendor_semester').show();
        }

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
        var tahun = $("#tahun").val();
        var minggu = $("#minggu").val();
        return $.param({unitkerja, ppbmuat, tahun, minggu});
    }
</script>
@endsection