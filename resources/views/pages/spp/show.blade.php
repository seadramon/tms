@extends('layout.layout2')
@section('css')
<style type="text/css">
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
</script>
@endsection