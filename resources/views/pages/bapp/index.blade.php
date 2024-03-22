@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Berita Acara Pemeriksaan Pekerjaan</h1>
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
                    <h3 class="card-title">LIST BAPP</h3>
                    <div class="card-toolbar">
                        @auth
                            <a href="{{route('bapp.create')}}" class="btn btn-light-primary me-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah BAPP</a>

                        @endauth
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Unit Kerja</label>
                            {!! Form::select('pat', $pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'pat']) !!}
                        </div>
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Periode</label>
                            {!! Form::select('periode', $periode, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'periode']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Vendor</label>
                            {!! Form::select('vendor', $vendor_id, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'vendor']) !!}
                        </div>
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Cut Off</label>
                            {!! Form::select('rangeCutOff', $rangeCutOff, null, ['class'=>'form-control form-select-solid col-sm-1', 'data-control'=>'select2', 'id'=>'rangeCutOff', 'disabled' => 'disabled']) !!}
                            {!! Form::select('monthCutOff', $monthCutOff, null, ['class'=>'form-control form-select-solid col-sm-2', 'data-control'=>'select2', 'id'=>'monthCutOff', 'disabled' => 'disabled']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6 custom-form mb-2">
                            <label class="form-label col-sm-3 custom-label">Status Penilaian</label>
                            {!! Form::select('status', $status, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'status']) !!}
                        </div>
                        <div class="col-lg-6 custom-form mb-2">
                            <button class="btn btn-light-info col-sm-3" id="filter">Filter</a>
                            {{-- <label class="form-label col-sm-3 custom-label">&nbsp;</label> --}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 custom-form mb-2">
                        </div>
                    </div>
                </div>

                <div class="card-body py-0">
                    <table id="tabel_bapp" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>Nama Vendor</th>
                                <th>SP3</th>
                                <th>BAPP</th>
                                <th>Tanggal BAPP</th>
                                <th>Status Penilaian</th>
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

	// Class definition
	var KTDatatablesServerSide = function () {
	    // Shared variables
	    var table;
	    var dt;
	    var filterPayment;

	    // Private functions
	    var initDatatable = function () {
	        dt = $("#tabel_bapp").DataTable({
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
	            order: [[3, 'desc']],
	            stateSave: true,
	            ajax: {
                    url: "{{ route('bapp.data') }}",
                    type: "POST",
                    data: function(d){
                        d._token = '{{ csrf_token() }}';
                        d.pat = $("#pat").val();
                        d.periode = $("#periode").val();
                        d.range = $("#rangeCutOff").val();
                        d.month = $("#monthCutOff").val();
                        d.status = $("#status").val();
                        d.vendor = $("#vendor").val();
                    }
                },
	            columns: [
	                {data: 'vendor.nama', name: 'vendor.nama', defaultContent: '-'},
	                {data: 'no_sp3', name: 'no_sp3', defaultContent: '-'},
	                {data: 'no_bapp', name: 'no_bapp', defaultContent: '-'},
	                {data: 'tgl_bapp', name: 'tgl_bapp', defaultContent: '-'},
	                {data: 'status', name: 'status', orderable: false, searchable: false, defaultContent: '-'},
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
        $('#tabel_spk').DataTable().ajax.reload()
    });
    $(document).on('change', '#periode', function(){
        if($('#periode').val() == ''){
            $("#monthCutOff").attr("disabled", disabled);
            $("#rangeCutOff").attr("disabled", disabled);
        }else{
            $("#rangeCutOff").removeAttr("disabled");
            $("#monthCutOff").removeAttr("disabled");
        }
    });
</script>
@endsection
