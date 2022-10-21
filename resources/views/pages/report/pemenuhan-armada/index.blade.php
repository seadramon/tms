@extends('layout.layout2')

@section('page-title')
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Ra Ri Pemenuhan Armada</h1>
</div>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="row g-5 g-xl-8">
        <div class="col-12 mb-md-5 mb-xl-10">
            <div id="box1" style="margin-bottom: 20px">
                @include('pages.report.pemenuhan-armada.box1')
            </div>

            <div id="box2" style="display: none">
                @include('pages.report.pemenuhan-armada.box2')
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
</style>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
<script type="text/javascript" src="https://rawgit.com/fusioncharts/fusioncharts-jquery-plugin/develop/dist/fusioncharts.jqueryplugin.min.js"></script>
<script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
<script type="text/javascript">
	"use strict";

    var isShowBox2 = false;

    var listBulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    var target = document.querySelector(".box-ui-loading-chart");
            
    var blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
    });

	// Class definition
	var KTDatatablesServerSide = function () {
	    // Shared variables
	    var table;
	    var dt;
	    var filterPayment;

	    // Private functions
	    var initDatatable = function () {
	        dt = $("#tabel_pemenuhan_armada").DataTable({
				language: {
  					lengthMenu: "Show _MENU_",
 				},
 				dom: 'Bfrtip',
	            searchDelay: 500,
	            processing: true,
	            serverSide: true,
	            order: [[0, 'desc']],
	            stateSave: true,
                searching: false,
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Export Excel',
                        className: 'btn-success',
                        action: exportDatatables
                    },
                    {
                        extend: 'pdf',
                        text: 'Export PDF',
                        className: 'btn-danger',
                        action: exportDatatables
                    }
                ],
	            ajax: {
                    url: "{{ route('report-pemenuhan-armada.data') }}",
                    type: "POST",
                    data: function(d){
                        d._token = '{{ csrf_token() }}';
                        d.kd_pat = $("#kd_pat").val();
                        d.pbb_muat = $("#pbb_muat").val();
                        d.vendor_id = $("#vendor_id").val();
                        d.kd_material = $("#kd_material").val();
                        d.periode = $("#periode").val();
                    }
                },
	            columns: [
	                {data: 'no_npp', defaultContent: '-'},
	                {data: 'ket', defaultContent: '-'},
	                {data: 'no_spm', defaultContent: '-'},
	                {data: 'tgl_spm', defaultContent: '-'},
	                {data: 'no_sptb', defaultContent: '-'},
	                {data: 'tgl_sptb', defaultContent: '-'},
	                {data: 'nama_vendor', defaultContent: '-'},
	                {data: 'no_pol', defaultContent: '-'},
	                {data: 'jenis_armada', defaultContent: '-'}
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
            $('#box2').show();

            isShowBox2 = true;
            
            KTDatatablesServerSide.init();
        }else{
            $('#tabel_pemenuhan_armada').DataTable().ajax.reload();            
        }

        getChart();
    });

    $(".datepicker").daterangepicker({
        showDropdowns: true,
        minYear: 1901,
        autoApply: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    function getChart(){
        $.ajax({
            url: "{{ route('report-pemenuhan-armada.chart') }}",
            type: "POST",
            data: {
                '_token': '{{ csrf_token() }}',
                'kd_pat' : $("#kd_pat").val(),
                'pbb_muat' : $("#pbb_muat").val(),
                'vendor_id' : $("#vendor_id").val(),
                'kd_material' : $("#kd_material").val(),
                'periode' : $("#periode").val()
            },
            dataType: 'json',
            beforeSend: function() {
                blockUI.block();
            },
            complete: function() {
                blockUI.release();
            },
            success: function(result) {
                $("#chart-container").html('');

                var kategori = [];
                var totalRencana = [];
                var totalRealisasi = [];
                var isRencanaEmpty, isRealisasiEmpty;

                for (let i = 1; i <= listBulan.length; i++) {
                    isRencanaEmpty = true;
                    isRealisasiEmpty = true;
                    
                    kategori.push(
                        {
                            label: listBulan[i-1]
                        }
                    )

                    result.rencana.forEach(element => {
                        if(element.bulan == i){
                            totalRencana.push(
                                {
                                    value: element.total
                                }
                            )

                            isRencanaEmpty = false;
                        }
                    });

                    if(isRencanaEmpty){
                        totalRencana.push(
                            {
                                value: 0
                            }
                        )
                    }

                    result.realisasi.forEach(element => {
                        if(element.bulan == i){
                            totalRealisasi.push(
                                {
                                    value: element.total
                                }
                            )

                            isRealisasiEmpty = false;
                        }
                    });

                    if(isRealisasiEmpty){
                        totalRealisasi.push(
                            {
                                value: 0
                            }
                        )
                    }
                }

                $("#chart-container").insertFusionCharts({
                    type: "msline",
                    width: "100%",
                    height: "500",
                    dataFormat: "json",
                    dataSource: {
                        chart: {
                            caption: "Pemenuhan Armada",
                            subcaption: $("#periode").val(),
                            showhovereffect: "1",
                            drawcrossline: "1",
                            plottooltext: "<b>$dataValue</b>",
                            theme: "fusion"
                        },
                        categories: [
                            {
                                category: kategori
                            }
                        ],
                        dataset: [
                            {
                                seriesname: "Rencana",
                                data: totalRencana
                            },
                            {
                                seriesname: "Realisasi",
                                data: totalRealisasi
                            },
                        ]
                    }
                });
            },
            error: function(result) {
                console.log(result);
            }
        });
    }

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
</script>
@endsection