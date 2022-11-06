
{!! Form::model($data, ['route' => ['spp.update', $spp], 'class' => 'form', 'method' => 'PUT']) !!}

	{!! Form::hidden('tipe', $tipe, ['class'=>'form-control', 'id'=>'tipe']) !!}
	<div class="col-12">
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
					{!! Form::text('no_spp', $noSppb, ['class'=>'form-control form-control-solid', 'id'=>'no_spp_input', 'readonly']) !!}
				</div>	
			</div>

			<div class="col-lg-6">
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

	<div class="col-12 mt-5">
		@include('pages.spp.part-edit-view')
	</div>
	<!-- end box 2 -->

	{{-- <div class="card-footer">
		<a href="{{ route('spp.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
		<input type="submit" class="btn btn-primary" id="kt_project_settings_submit" value="Simpan">
	</div> --}}
{!! Form::close() !!}