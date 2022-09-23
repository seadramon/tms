@extends('layout.layout2')
@section('css')
<style type="text/css">
</style>
@endsection

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
	<h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Create SPP</h1>
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
					<h3 class="card-title">SPP Form</h3>
				</div>

				{!! Form::open(['url' => route('spp.draft'), 'class' => 'form', 'id' => 'draft', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
					<div class="card-body">
						@if (count($errors) > 0)
							@foreach($errors->all() as $error)
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<strong>Error!</strong> {{ $error }}
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							@endforeach
						@endif
						<!-- ./notifikasi -->

						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Jenis</label>
									{!! Form::select('jenis', $jenis, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'jenis']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">NO SPP</label>
									{!! Form::text('no_spp', 'AUTO', ['class'=>'form-control form-control-solid', 'id'=>'no_spp_input', 'readonly']) !!}
								</div>	
							</div>

							<div class="col-lg-9">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">NPP</label>
									<!-- <select class="form-select select2spprb" data-control="select2" data-placeholder="Pilih SPPRB" data-allow-clear="true" name="no_spprb"></select> -->
									<select class="form-select search-npp" name="no_npp" id="no_npp"></select>
								</div>	
							</div>

							<div class="col-lg-3">
								<div class="form-group">
									<button type="submit" id="draft_submit" class="btn btn-primary mt-11" id="draft" value="Draft">Buat Draft</button>
								</div>
							</div>
						</div>
					</div>
				{!! Form::close() !!}

				{!! Form::open(['url' => route('spp.store'), 'class' => 'form', 'id' => 'fstore', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

					<div class="card-body" id="kt_block_ui_target">
						
					</div>
					<!-- end box 2 -->

					<div class="card-footer">
						<a href="{{ route('spp.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
						<input type="submit" class="btn btn-primary" id="kt_project_settings_submit" value="Simpan">
					</div>
				{!! Form::close() !!}
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