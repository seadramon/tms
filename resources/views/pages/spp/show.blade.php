@extends('layout.layout2')
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

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
	<h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{ ucwords($tipe) }} SPP</h1>
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
					<h3 class="card-title">VIEW SPPB</h3>
				</div>
				
				<div class="card-body">
					<div class="mb-5 hover-scroll-x">
						<div class="d-grid">
							<ul class="nav nav-tabs flex-nowrap text-nowrap">
								<li class="nav-item">
									<a class="nav-link active btn btn-active-light btn-color-gray-600 btn-active-light-primary rounded-bottom-0" data-bs-toggle="tab" href="#spp">
										<span class="fs-4 fw-bold">SPP</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-light-success rounded-bottom-0" data-bs-toggle="tab" href="#rute_pengiriman">
										<span class="fs-4 fw-bold">Rute Pengiriman</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-light-info rounded-bottom-0" data-bs-toggle="tab" href="#kontrak">
										<span class="fs-4 fw-bold">Kontrak</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-light-danger rounded-bottom-0" data-bs-toggle="tab" href="#spprb">
										<span class="fs-4 fw-bold">List SPPRB</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-light-warning rounded-bottom-0" data-bs-toggle="tab" href="#angkutan">
										<span class="fs-4 fw-bold">List SP3</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
					
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="spp" role="tabpanel">
							@include('pages.spp.view-spp')
						</div>
						<div class="tab-pane fade" id="rute_pengiriman" role="tabpanel">
							@include('pages.spp.view-rute')
						</div>
						<div class="tab-pane fade" id="kontrak" role="tabpanel">
							@include('pages.spp.view-kontrak')
						</div>
						<div class="tab-pane fade" id="spprb" role="tabpanel">
							@include('pages.spp.view-spprb')
						</div>
						<div class="tab-pane fade" id="angkutan" role="tabpanel">
							@include('pages.spp.view-angkutan')
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!--end::Row-->
</div>
<!--end::Content container-->
@endsection
@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script type="text/javascript">

$( document ).ready(function() {	
	$("#daterange").daterangepicker();

	$(".select2spprb").select2({
		ajax: {
			url: '/select2/spprb',
			dataType: 'json',
			data: function (params) {
				return {
					q: $.trim(params.term)
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true,
			minimumInputLength:2
		}
	});

	$('.search-npp').select2({
        placeholder: 'Cari...',
        ajax: {
            url: "{{ route('sp3.search-npp') }}",
            minimumInputLength: 2,
            dataType: 'json',
            cache: true,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.no_npp + ' | ' + item.nama_proyek,
                            id: item.no_npp
                        }
                    })
                };
            },
        }
    });

	var target = document.querySelector("#kt_block_ui_target");

	var imgLoading = "{{ asset('assets/image_loader.gif') }}";

	var blockUI = new KTBlockUI(target, {
	    message: '<div class="blockui-message"><span class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></span> Loading...</div>',
	});

	$("#draft").submit(function(event) {
		event.preventDefault();

		blockUI.block();

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
				$("#kt_block_ui_target").html("");

				$("#kt_block_ui_target").html(res);

				blockUI.release();
			},
			error: function(res) {
				console.log(res);
				blockUI.release();
			}
		})
	});
});

"use strict";

// Class definition
var KTDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;
    var filterPayment;

    // Private functions SPPRB
    var initDatatable = function () {
        dt = $("#tabel_spprb").DataTable({
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
            ajax: "{{ route('spp.data-spprb') }}" + '?no_npp=' + "{{ $no_npp }}",
            columns: [
                {data: 'spprblast', defaultContent: '-'},
                {data: 'pat.ket', defaultContent: '-'},
                {data: 'produk.tipe', defaultContent: '-'},
                {data: 'kd_produk', defaultContent: '-'},
                {data: 'jadwal1', defaultContent: '-'},
                {data: 'jadwal2', defaultContent: '-'},
                {data: 'vol_spprb', defaultContent: '-'},
            ],
        });

        table = dt.$;
    }

    // Private functions SP3
    var initDatatableAngkutan = function () {
        dtAngkutan = $("#tabel_angkutan").DataTable({
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
            ajax: "{{ route('spp.data-angkutan') }}" + '?noSppb=' + "{{ $noSppb }}",
            columns: [
                {data: 'no_sp3', defaultContent: '-'},
                {data: 'vendorname', defaultContent: '-'},
                {data: 'volakhir', defaultContent: '-'},
                {data: 'voltonakhir', defaultContent: '-'},
                {data: 'status', defaultContent: '-'},
            ],
        });

        table = dtAngkuta.n$;
    }
    
    // Public methods
    return {
        init: function () {
            initDatatable();
            initDatatableAngkutan();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	console.log('test');
    KTDatatablesServerSide.init();
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
</script>
@endsection