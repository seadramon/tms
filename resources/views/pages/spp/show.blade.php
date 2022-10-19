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
			<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
			    <li class="nav-item">
			        <a class="nav-link active" data-bs-toggle="tab" href="#spp">SPP</a>
			    </li>
			    <li class="nav-item">
			        <a class="nav-link" data-bs-toggle="tab" href="#rute_pengiriman">Rute Pengiriman</a>
			    </li>
			    <li class="nav-item">
			        <a class="nav-link" data-bs-toggle="tab" href="#kontrak">Kontrak</a>
			    </li>
			    <li class="nav-item">
			        <a class="nav-link" data-bs-toggle="tab" href="#spprb">SPPrB</a>
			    </li>
			    <li class="nav-item">
			        <a class="nav-link" data-bs-toggle="tab" href="#angkutan">SP3/SPK VDR ANGKUTAN</a>
			    </li>
			</ul>

			<div class="card shadow-sm">
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