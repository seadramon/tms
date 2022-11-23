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
					<h3 class="card-title">SPP Form {{ ucwords($tipe) }}</h3>
				</div>

				{!! Form::model($data, ['route' => ['spp.update', $spp], 'class' => 'form', 'method' => 'PUT']) !!}

					{!! Form::hidden('tipe', $tipe, ['class'=>'form-control', 'id'=>'tipe']) !!}
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
									{!! Form::text('jns_sppb', 'AUTO', ['class'=>'form-control form-control-solid', 'id'=>'jns_sppb', 'readonly']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">NO SPP</label>
									{!! Form::text('no_spp', 'AUTO', ['class'=>'form-control form-control-solid', 'id'=>'no_spp_input', 'readonly']) !!}
								</div>	
							</div>

							<div class="col-lg-9">
								<?php 
								$nama_proyek = !empty($npp->nama_proyek)?$npp->nama_proyek:'';
								?>
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">NPP</label>
									<input type="text" class="form-control form-control-solid" name="captionnpp" value="{{ $data->no_npp.' | '.$nama_proyek }}" readonly="">
								</div>	
							</div>

							<div class="col-lg-3">
								&nbsp;
							</div>
						</div>
					</div>

					<div class="card-body" id="kt_block_ui_target">
						@include('pages.spp.part-edit')
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
	$("#daterange").daterangepicker({
		locale: {
            format: 'DD-MM-YYYY'
		}
	});
	

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
function sdSaatIni(vol, urutan) {
	let saatIni = $("#id-saatini-" + urutan).val();
	let hitungan = parseInt(vol) + parseInt(saatIni);

	$("#id-sdsaatini-" + urutan).val(hitungan);
}
</script>
@endsection