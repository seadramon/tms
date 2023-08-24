@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Kontrak / SP3 / SPK</h1>
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
                    <h3 class="card-title">LIST Kontrak / SP3 / SPK</h3>
                    <div class="card-toolbar">
                        @if (in_array('create', json_decode(session('TMS_ACTION_MENU'))))
                            <a href="{{route('sp3.create')}}" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah Data</a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Unit Kerja</label>
                            {!! Form::select('pat', $pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'pat']) !!}
                        </div>
                        
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Lokasi Muat</label>
                            {!! Form::select('ppb_muat', $muat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'ppb_muat']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Periode</label>
                        {!! Form::select('periode', $periode, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'periode']) !!}
                        </div>
                        
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Cut Off</label>
                            {!! Form::select('rangeCutOff', $rangeCutOff, null, ['class'=>'form-control form-select-solid col-sm-1', 'data-control'=>'select2', 'id'=>'rangeCutOff', "disabled" => true]) !!}
                            {!! Form::select('monthCutOff', $monthCutOff, null, ['class'=>'form-control form-select-solid col-sm-2', 'data-control'=>'select2', 'id'=>'monthCutOff', "disabled" => true]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Status SP3/SPK</label>
                            {!! Form::select('status', $status, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'status']) !!}
                        </div>
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Pekerjaan</label>
                            {!! Form::select('pekerjaan', $jenisPekerjaan, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'pekerjaan']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">&nbsp;</label>
                            <button class="btn btn-light-info" id="filter">Filter</a>
                        </div>
                    </div>
                </div>

                <div class="card-body py-0">
                    <table id="tabel_sp3" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th rowspan="2">NO KONTRAK</th>
                                <th rowspan="2">NPP</th>
                                <th rowspan="2">TANGGAL</th>
                                @if (Auth::check())
                                    <th rowspan="2">UNIT KERJA</th>
                                @else
                                    <th rowspan="2">VENDOR</th>
                                @endif
                                <th rowspan="2">APPROVE</th>
                                <th colspan="3" style="text-align: center">PROGRESS</th>
                                <th rowspan="2">OPTION</th>
                            </tr>
                            
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>VOL</th>
                                <th>RUPIAH</th>
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
	        dt = $("#tabel_sp3").DataTable({
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
	            ajax: {
                    url: "{{ route('sp3.data') }}",
                    type: "POST",
                    data: function(d){
                        d._token = '{{ csrf_token() }}';
                        d.pat = $("#pat").val();
                        d.periode = $("#periode").val();
                        d.range = $("#rangeCutOff").val();
                        d.month = $("#monthCutOff").val();
                        d.status = $("#status").val();
                        d.pekerjaan = $("#pekerjaan").val();
                        d.ppb_muat = $("#ppb_muat").val();
                    }
                },
	            columns: [
	                {data: 'no_sp3', name: 'no_sp3', defaultContent: '-'},
	                {data: 'no_npp', name: 'no_npp', defaultContent: '-'},
	                {data: 'tgl_sp3', name: 'tgl_sp3', defaultContent: '-'},
	                {data: 'custom', name: 'vendor.nama', defaultContent: '-'},
	                {data: 'approval', name: 'app1', defaultContent: '-'},
	                {data: 'progress_vol', name: 'progress_vol', orderable: false, searchable: false, defaultContent: '-'},
	                {data: 'progress_rp', name: 'progress_rp', orderable: false, searchable: false, defaultContent: '-'},
	                {data: 'progress_wkt', name: 'progress_wkt', orderable: false, searchable: false, defaultContent: '-'},
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
				url: "{{ url('sp3/destroy') }}",
				data: {id : id, _token: "{{ csrf_token() }}"},
				success: function(res){
					if (res.result == 'success') {
						flasher.success("Data telah berhasil dihapus!");

						$('#tabel_jenis').DataTable().ajax.url("{{ route('sp3.data') }}").load();
					}
				}
			});
		}
	});

    $(document).on('click', '#filter', function(){
        $('#tabel_sp3').DataTable().ajax.reload()
    });
    $(document).on('change', '#periode', function(){
        if($('#periode').val() == ''){
            var disabled = true; 
        }else{
            var disabled = false; 
        }
        $("#rangeCutOff").attr("disabled", disabled);
        $("#monthCutOff").attr("disabled", disabled);
    });
</script>
@endsection