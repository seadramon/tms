@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">PRICELIST ANGKUTAN</h1>
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
                    <h3 class="card-title">Price List Angkutan</h3>
                    <div class="card-toolbar">
						@if (in_array('create', json_decode(session('TMS_ACTION_MENU'))))
							<a href="{{route('pricelist-angkutan.create')}}" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah Data</a>
						@endif
					</div>
                </div>

                <div class="card-body py-5">
                    <table id="tabel_sptb" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>Unit Kerja</th>
                                <th>Angkutan</th>
                                <th>Unit Pemuatan</th>
                                <th>Periode</th>
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
	        dt = $("#tabel_sptb").DataTable({
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
	            ajax: "{{ route('pricelist-angkutan.data') }}",
	            columns: [
	                {data: 'pat.ket', name: 'pat.ket', defaultContent: '-'},
	                {data: 'angkutan', name: 'angkutan', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'pemuatan', name: 'pemuatan', defaultContent: '-', orderable: false, searchable: false},
	                {data: 'tahun', name: 'tahun', defaultContent: '-'},
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

	$(document).ready(function(){
		$('.delete-btn').on("click", function(e) { 
			console.log("aa");
			
		});
	});

	$(document).on('click', '.delete-btn', function(){
		console.log("aa1");
		swal({
				title: "Apakah anda yakin?",
				text: "Menghapus data",
				icon: "error",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: "POST",
						url: "{{ route('pricelist-angkutan.delete') }}",
						headers: {
							'X-CSRF-TOKEN': "{{csrf_token()}}"
						},
						data: {
								id : $(this).attr('data-id'),
							},
						success: function(result) {
							// swal("Menu Successfully Update");
							flasher.success("Data telah berhasil dihapus!");
							$('#tabel_sptb').DataTable().ajax.reload()
						},
						error: function(xhr, ajaxOptions, thrownError) {
							console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				} else {
					swal("Delete Canceled", {
						icon: "success",
					}); 
				}
			});
	});

</script>
@endsection