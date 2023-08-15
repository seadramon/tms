@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">List Potensi Kebutuhan Armada</h1>
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
                    <h3 class="card-title">List Potensi Kebutuhan Armada</h3>
                    
                </div>
                <div class="card-body py-5">
					<div class="row">
						<div class="col-6">
							<div class="form-group mb-2">
								<label class="form-label col-sm-3 custom-label">Unit Kerja</label>
								{!! Form::select('unitkerja', $kd_pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'unitkerja']) !!}
							</div>
							<div class="form-group mb-2">
								<label class="form-label custom-label">Bulan Mulai Distribusi</label>
								<div class="row">
									<div class="col-3">
										{!! Form::select('bulan1', $bulan1, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'bulan1']) !!}
									</div>
									<div class="col-9">
										{!! Form::select('bulan2', $bulan2, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'bulan2']) !!}
									</div>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group mb-2">
								<label class="form-label col-sm-3 custom-label">PPB Muat</label>
								{!! Form::select('ppbmuat', $ppb_muat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'ppbmuat']) !!}
							</div>
							<div class="form-group mb-2">
								<label class="form-label col-sm-3 custom-label">&nbsp;</label>
								<button class="btn btn-info" id="filter">Filter</button>
							</div>
						</div>
					</div>
                    <table id="tabel" class="table table-row-bordered table-striped g-2" style="vertical-align: middle;">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>NPP</th>
                                <th>VOL (BTG)</th>
                                <th>VOL (TON)</th>
                                <th>Tgl Mulai Distribusi</th>
                                <th>Tgl Akhir Distribusi</th>
                                <th>Jenis Armada</th>
                                <th>Total Rit</th>
                                <th>Rit/Hari</th>
                                <th>PPB Muat</th>
                                <th>Jarak</th>
                                <th class="{{ Auth::check() ? 'hidden' : '' }}">Vendor Offers</th>
                                <th>{{ Auth::check() ? 'Tujuan' : 'Status' }}</th>
                                <th>Menu</th>
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
	$(document).ready(function () {
		$("#filter").click(function(){
            var datatableUrl = "{{ route('potensi.detail.armada.data') }}?" + getParam();
            $('#tabel').DataTable().ajax.url(datatableUrl).load();
        });
	});

	// Class definition
	var KTDatatablesServerSide = function () {
	    // Shared variables
	    var table;
	    var dt;

	    // Private functions
	    var initDatatable = function () {
	        dt = $("#tabel").DataTable({
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
	            ajax: "{{ route('potensi.detail.armada.data') }}?" + getParam(),
	            columns: [
	                {data: 'no_npp', name: 'no_npp', defaultContent: '-'},
	                {data: 'vol_btg', name: 'vol_btg', defaultContent: '-'},
	                {data: 'tonase', name: 'tonase', defaultContent: '-'},
	                {data: 'jadwal3', name: 'jadwal3', defaultContent: '-'},
	                {data: 'jadwal4', name: 'jadwal4', defaultContent: '-'},
	                {data: 'jenis_armada', name: 'jenis_armada', defaultContent: '-'},
	                {data: 'jml_rit', name: 'jml_rit', defaultContent: '-'},
	                {data: 'rit_hari', name: 'jml_rit', defaultContent: '-'},
	                {data: 'ppbmuat.singkatan', name: 'ppbmuat.singkatan', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'jarak_km', name: 'jarak_km', defaultContent: '-'},
	                {data: 'offer', class: "{{ Auth::check() ? 'hidden' : '' }}", orderable: false, searchable: false},
	                {data: 'status', orderable: false, searchable: false},
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

	function getParam(){
        var unitkerja = $("#unitkerja").val();
        var ppbmuat = $("#ppbmuat").val();
        var bulan1 = $("#bulan1").val();
        var bulan2 = $("#bulan2").val();
        return $.param({unitkerja, ppbmuat, bulan1, bulan2});
    }
</script>
@endsection