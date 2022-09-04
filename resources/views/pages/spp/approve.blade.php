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

				{!! Form::model($data, ['url' => route('spp-approve.store'), 'class' => 'form', 'id' => 'approve', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

					{!! Form::hidden('no_sppb', null, ['class'=>'form-control form-control-solid', 'id'=>'no_sppb', 'readonly']) !!}
					{!! Form::hidden('approvalNum', $approval, ['class'=>'form-control form-control-solid', 'id'=>'approvalNum']) !!}
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
									{!! Form::text('jenis', $jenis, ['class'=>'form-control form-control-solid', 'id'=>'no_npp_input', 'disabled']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">NO NPP</label>
									{!! Form::text('no_npp', $data->spprb->no_npp, ['class'=>'form-control form-control-solid', 'id'=>'no_npp_input', 'disabled']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">No SPPRB</label>
									{!! Form::text('no_spprb', null, ['class'=>'form-control form-control-solid', 'id'=>'no_spprb', 'disabled']) !!}
								</div>	
							</div>
						</div>
					</div>

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
											@if (count($tblPesanan) > 0)
												@foreach($tblPesanan as $row)
													<tr>
														<td>{{$row->tipe}}</td>
														<?php 
															$arrVol = [];
															$volm3 = !empty($row->vol_m3)?$row->vol_m3:1;
															$pesananVolBtg = $row->vol_spprb;
															$pesananVolTon = $row->vol_spprb * $volm3 * 2.5;
															$sppSebelumVolBtg = $row->vol;
															$sppSebelumVolTon = $row->vol * $volm3 * 2.5;
															$sisaBtg = $pesananVolBtg - $sppSebelumVolBtg;
															$sisaTon = $pesananVolTon - $sppSebelumVolTon;
															if ($pesananVolBtg > 0) {
															    $persen = $sisaBtg / $pesananVolBtg;
															}

															$arrVol[$row->kd_produk] = $sppSebelumVolBtg;
														?>
														<td>{{ $pesananVolBtg }}</td>
														<td>{{ $pesananVolTon }}</td>
														<td>{{ $sppSebelumVolBtg }}</td>
														<td>{{ $sppSebelumVolTon }}</td>
														<td>{{ $sisaBtg }}</td>
														<td>{{ $sisaTon }}</td>
														<td>{{ round($persen, 2) }}</td>
													</tr>
												@endforeach
											@else
												<tr>
													<td colspan="8">Data Kosong</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>

							<div class="col-lg-12">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Proyek</label>
									{!! Form::text('nama_proyek', $npp->nama_proyek, ['class'=>'form-control  form-control-solid', 'id'=>'nama_proyek', 'disabled']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Pelanggan</label>
									{!! Form::text('nama_pelanggan', $npp->nama_pelanggan, ['class'=>'form-control form-control-solid', 'id'=>'nama_pelanggan', 'disabled']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Lokasi Muat</label>
									{!! Form::text('pat', $pat->ket, ['class'=>'form-control form-control-solid', 'id'=>'pat', 'disabled']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Tujuan</label>
									{!! Form::text('tujuan', $npp->kab.', '.$npp->kec, ['class'=>'form-control form-control-solid', 'id'=>'tujuan', 'disabled']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">NPP</label>
									{!! Form::text('no_npp', $npp->no_npp, ['class'=>'form-control form-control-solid', 'id'=>'no_npp', 'disabled']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Estimasi Total Ritase</label>
									{!! Form::text('rit', null, ['class'=>'form-control form-control-solid', 'id'=>'rit', 'disabled']) !!}
								</div>	
							</div>

							<div class="col-lg-6">
								<div class="form-group">
									<label class="fs-6 fw-bold mt-2 mb-3">Jarak (KM)</label>
									{!! Form::text('jarak_km', null, ['class'=>'form-control form-control-solid', 'id'=>'jarak_km', 'disabled']) !!}
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
												<th>%</th>
												@if ($approval == 'first')
													<th>Vol Approval 1</th>
												@elseif ($approval == 'second')
													<th>Vol Approval 1</th>
													<th>Vol Approval 2</th>
												@elseif ($approval == 'third')
													<th>Vol Approval 1</th>
													<th>Vol Approval 2</th>
													<th>Vol Approval 3</th>
												@endif
											</tr>
										</thead>
										<tbody id="rencanaProd">
											<?php $i = 1; ?>
											{{ $data->no_sppb }}
											@if (!empty($data->detail))
												@foreach($data->detail as $detail)
												<tr>
													<td>{{ $i }}</td>
													<td>{{ $detail->produk->tipe }}</td>
													<td>{{ $detail->kd_produk }}</td>
													<td>{{ $detail->vol }}</td>
													<td>{{ $detail->vol + $arrVol[$row->kd_produk] }}</td>
													<td>{{ 'AUTO' }}</td>
													@if ($approval == 'first')
														<td>
															{!! Form::number("rencana[$detail->kd_produk][app1_vol]", null, ['class'=>'form-control']) !!}
														</td>
													@elseif ($approval == 'second')
														<td style="text-align: right;">{{ $detail->app1_vol }}</td>
														<td>
															{!! Form::number("rencana[$detail->kd_produk][app2_vol]", null, ['class'=>'form-control']) !!}
														</td>
													@elseif ($approval == 'third')
														<td style="text-align: right;">{{ $detail->app1_vol }}</td>
														<td style="text-align: right;">{{ $detail->app2_vol }}</td>
														<td>
															{!! Form::number("rencana[$detail->kd_produk][app3_vol]", null, ['class'=>'form-control']) !!}
														</td>
													@endif
												</tr>
												<?php $i++; ?>
												@endforeach
											@else
												<tr>
													<td colspan="8">Data Kosong</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>

							<div class="col-lg-12 mb-3">
								<h3 class="card-title">Catatan Pembayaran</h3>
							</div>

							@if ($approval == 'first')
								@include('pages.spp.first')
							@elseif ($approval == 'second')
								@include('pages.spp.second')
							@elseif ($approval == 'third')
								@include('pages.spp.third')
							@endif
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