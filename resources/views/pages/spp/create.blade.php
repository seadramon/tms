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
									{!! Form::text('no_npp', 'AUTO', ['class'=>'form-control form-control-solid', 'id'=>'no_npp_input', 'readonly']) !!}
								</div>	
							</div>

							<div class="col-lg-9">
								<div class="form-group">
									040/PI/SPPRB/III/WP-I/21P01
									<label class="fs-6 fw-bold mt-2 mb-3">No SPPRB</label>
									<select class="form-select select2spprb" data-control="select2" data-placeholder="Pilih SPPRB" data-allow-clear="true" name="no_spprb"></select>
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
						<div class="row">
							<div class="col-lg-12">
								<h3 class="card-title">Detail Pesanan NPP</h3>
								<div class="table-responsive">
									<table id="kt_datatable_example_2" class="table table-row-bordered  gy-5">
										<thead>
											<tr class="fw-bolder fs-6 text-gray-800">
												<th rowspan="2">Nama/Tipe Produk</th>
												<th colspan="2">Pesanan</th>
												<th colspan="2">SPP Sebelumnya</th>
												<th colspan="3">Volume Sisa</th>
											</tr>
											<tr>
												<th>Vol(Btg)</th>
												<th>Vol(Ton)</th>
												<th>Vol(Btg)</th>
												<th>Vol(Ton)</th>
												<th>Vol(Btg)</th>
												<th>Vol(Ton)</th>
												<th>%</th>
											</tr>
										</thead>
										<tbody id="dtlPesanan">
											<tr>
												<td>60 A3 B 14 7 B</td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div class="col-lg-12">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Proyek</label>
									{!! Form::text('nama_proyek', '', ['class'=>'form-control', 'id'=>'nama_proyek']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Pelanggan</label>
									{!! Form::text('nama_pelanggan', '', ['class'=>'form-control', 'id'=>'nama_pelanggan']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Lokasi Muat</label>
									{!! Form::text('pat', '', ['class'=>'form-control', 'id'=>'pat']) !!}
									{!! Form::hidden('pat_singkatan', '', ['class'=>'form-control', 'id'=>'pat_singkatan']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Tujuan</label>
									{!! Form::text('tujuan', '', ['class'=>'form-control', 'id'=>'tujuan']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">NPP</label>
									{!! Form::text('no_npp', '', ['class'=>'form-control', 'id'=>'no_npp']) !!}
									{!! Form::hidden('no_spprb', '', ['class'=>'form-control', 'id'=>'no_spprb']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Estimasi Total Ritase</label>
									{!! Form::text('rit', '', ['class'=>'form-control', 'id'=>'rit']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Jarak (KM)</label>
									{!! Form::text('jarak_km', '', ['class'=>'form-control', 'id'=>'jarak_km']) !!}
								</div>	
							</div>

							<div class="col-lg-12 mt-10">
								<h3 class="card-title">Detail Rencana Produk</h3>
								<div class="table-responsive">
									<table class="table table-row-bordered gy-5">
										<thead>
											<tr class="fw-bolder fs-6 text-gray-800">
												<th>No</th>
												<th>Nama Produk</th>
												<th>Kode Produk</th>
												<th>Saat Ini</th>
												<th>S.d Saat ini</th>
												<th>Keterangan</th>
												<th>Segmen</th>
												<th>Jumlah Segmen</th>
											</tr>
										</thead>
										<tbody id="rencanaProd">
											<tr>
												<td colspan="8">Data Kosong</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Rencana Pengiriman</label>
									{!! Form::text('jadwal', '', ['class'=>'form-control', 'id'=>'daterange']) !!}
								</div>	
							</div>

							<div class="col-lg-12">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Keterangan</label>
									{!! Form::textarea('catatan', '', ['class'=>'form-control', 'id'=>'daterange', 'rows' => '5']) !!}
								</div>	
							</div>
						</div>
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

function sdSaatIni(vol, urutan) {
	console.log(vol);
	console.log(urutan);

	let saatIni = $("#id-saatini-" + urutan).val();
	let hitungan = parseInt(vol) + parseInt(saatIni);
	
	$("#id-sdsaatini-" + urutan).val(hitungan);
}

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
				console.log(res.npp);
				$("#dtlPesanan").html("");
				$("#dtlPesanan").html(res.tblPesanan);

				$("#nama_proyek").val(res.npp.nama_proyek);
				$("#nama_pelanggan").val(res.npp.nama_pelanggan);
				$("#tujuan").val(res.npp.kab + ', ' + res.npp.kec);
				$("#no_spprb").val(res.noSpprb);
				$("#no_npp").val(res.npp.no_npp);
				$("#no_npp_input").val(res.npp.no_npp);
				$("#pat").val(res.pat.ket);
				$("#pat_singkatan").val(res.pat.singkatan);
				$("#jarak_km").val(res.jarak);

				$("#rencanaProd").html("");
				$("#rencanaProd").html(res.rencanaProd);

				blockUI.release();
			},
			error: function (err) {
				$("#draft_submit").removeAttr("data-kt-indicator");
				if (err.status == 422) {
					flasher.error(err.responseJSON.message);
				} else {
					flasher.error("Data gagal ditambahkan");
				}

				blockUI.release();
			}
		})
	});
});
</script>
@endsection