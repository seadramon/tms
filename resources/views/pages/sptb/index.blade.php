@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SPTB</h1>
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
                    <h3 class="card-title">List SPTB</h3>
                    <div class="card-toolbar">
						@if (in_array('create', json_decode(session('TMS_ACTION_MENU'))))
							<a href="{{route('sptb.create')}}" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah Data</a>
						@endif
                    </div>
                </div>

                <div class="card-body py-5">
                    <table id="tabel_sptb" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>NO SPTB</th>
                                <th>NO SPM</th>
                                <th>NO SPPRB</th>
                                <th>TGL</th>
                                <th>PELANGGAN</th>
                                <th>PROYEK</th>
                                <th>NOPOL</th>
                                <th>STATUS</th>
                                <th>OPTION</th>
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
<div class="modal fade" id="modal_armada" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal modal-dialog-centered">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="modal_armada_header">
                <!--begin::Modal title-->
                <h2>Form Penilaian Pelayanan</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Form-->
            <form method="post" id="modal_armada_form" class="form" action="{{ route('sptb.penilaian-pelayanan-simpan') }}">
                <input type="hidden" name="no_sptb" id="no_sptb">
                <!--begin::Modal body-->
                <div class="modal-body px-lg-10">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="modal_armada_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_armada_header" data-kt-scroll-wrappers="#modal_armada_scroll" data-kt-scroll-offset="300px">
                        <table style="width: 100%;" class="table table-bordered">
                            <thead>
                                <tr style="font-weight: bold">
                                    <th style="width: 20%; border: solid 1px black; text-align: center;">No</th>
                                    <th style="width: 50%; border: solid 1px black;">Kriteria</th>
                                    <th style="width: 30%; border: solid 1px black; text-align: center;">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
								<tr>
									<td style="width: 10%; border: solid 1px black; text-align: center;">1.</td>
									<td style="width: 35%; border: solid 1px black;">
										Tidak ada keluhan ada ada keluhan ditanggapi dengan cepat dan benar
									</td>
									<td style="width: 10%; border: solid 1px black; text-align: center;">
										<input class="form-check-input criteria-radio" name="layanan" type="radio" data-type="yes" value="90" id="flexRadioDefault"/>
									</td>
								</tr>
								<tr>
									<td style="width: 10%; border: solid 1px black; text-align: center;">2.</td>
									<td style="width: 35%; border: solid 1px black;">
										Ada keluhan ditanggapi dan ditindaklanjuti vendor secara tepat tapi lambat
									</td>
									<td style="width: 10%; border: solid 1px black; text-align: center;">
										<input class="form-check-input criteria-radio" name="layanan" type="radio" data-type="yes" value="70" id="flexRadioDefault"/>
									</td>
								</tr>
								<tr>
									<td style="width: 10%; border: solid 1px black; text-align: center;">3.</td>
									<td style="width: 35%; border: solid 1px black;">
										Ada keluhan tapi tidak ditanggapi vendor
									</td>
									<td style="width: 10%; border: solid 1px black; text-align: center;">
										<input class="form-check-input criteria-radio" name="layanan" type="radio" data-type="yes" value="50" id="flexRadioDefault"/>
									</td>
								</tr>
								<tr>
									<td style="width: 10%; border: solid 1px black; text-align: center;"></td>
									<td style="width: 35%; border: solid 1px black;">
									</td>
									<td style="width: 10%; border: solid 1px black; text-align: center;">
									</td>
								</tr>
                            </tbody>
                        </table>
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->

                <!--begin::Modal footer-->
                <div class="modal-footer flex-right">
                    <button type="submit" id="modal_armada_submit" class="btn btn-primary">
                        <span class="indicator-label">Penilaian Pelayanan</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
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
	            ajax: "{{ route('sptb.data') }}",
	            columns: [
	                {data: 'no_sptb', name: 'no_sptb', defaultContent: '-'},
	                {data: 'no_spm', name: 'no_spm', defaultContent: '-'},
	                {data: 'no_spprb', name: 'no_spprb', defaultContent: '-'},
	                {data: 'tgl_berangkat', name: 'tgl_berangkat', defaultContent: '-'},
	                {data: 'npp.nama_pelanggan', name: 'npp.nama_pelanggan', defaultContent: '-'},
	                {data: 'npp.nama_proyek', name: 'npp.nama_proyek', defaultContent: '-'},
	                {data: 'no_pol', name: 'no_pol', defaultContent: '-'},
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

	$('body').on('click', '.set-konfirmasi', function () {
		var id = $(this).data("id");

		Swal.fire({
	        html: `Apakah anda yakin melakukan konfirmasi sptb?`,
	        icon: "info",
	        buttonsStyling: false,
	        showCancelButton: true,
	        confirmButtonText: "Ya",
	        cancelButtonText: 'Tidak',
	        customClass: {
	            confirmButton: "btn btn-primary",
	            cancelButton: 'btn btn-danger'
	        }
	    }).then((result) => {
			if (result.isConfirmed) {
				// ajax
		    	$.ajax({
		    		type:"post",
		    		url: "{{ route('sptb.set-konfirmasi') }}",
		    		data: {id : id, _token: "{{ csrf_token() }}"},
		    		beforeSend: function(){
				    	document.body.style.cursor='wait';
				   	},
		    		success: function(res){
		    			if (res.status == 'success') {
		    				Swal.fire('Data SPTB berhasil dikonfirmasi', '', 'success');
		    			} else {
		    				Swal.fire('Data SPTB gagal dikonfirmasi', '', 'error');
		    			}
		    		},
		    		complete: function(){
				    	document.body.style.cursor='default';
				   	}
		    	});
		  	}
		});
	});
	$(document).on("click", ".penilaian-pelayanan", function () {
        var sptb = $(this).data('sptb');
		$("#no_sptb").val(sptb);
		$('#modal_armada').modal('toggle');
    });

    $("#modal_armada_form").submit(function(event) {
        event.preventDefault();

        $("#modal_armada_submit").attr("data-kt-indicator", "on");

        let data = $(this).serialize();
        let url = $(this).attr('action');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            type:"post",
            url: url,
            data: data,
            success: function(result) {
                if(result.success){
                    $("#modal_armada_submit").removeAttr("data-kt-indicator");
                    $('#modal_armada').modal('toggle');
                    flasher.success("Penilaian Pelayanan berhasil!");
                    $('#tabel_sptb').DataTable().ajax.url("{{ route('sptb.data') }}").load();
                }else{
                    flasher.error(result.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#modal_armada_submit").removeAttr("data-kt-indicator");

                $('#modal_armada').modal('toggle');
                flasher.error("Konfirmasi gagal!");
            }
        })
    });
</script>
@endsection