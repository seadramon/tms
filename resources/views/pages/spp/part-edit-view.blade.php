<div class="row">
	<div class="col-lg-12">
		<h3 class="card-title">Detail Pesanan NPP</h3>

		<div class="hover-scroll-overlay-y h-400px">
            <table id="tabel_detail_pesanan" class="table table-row-bordered text-center">
				<thead>
					<tr>
						<th rowspan="2" style="vertical-align: middle; text-align: left">Nama/Tipe Produk</th>
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
				<tbody>
					@if (count($tblPesanan) > 0)
						@foreach($tblPesanan as $pesanan)
							<?php 
								$volm3 = !empty($pesanan->vol_m3)?$pesanan->vol_m3:1;
								$pesananVolBtg  = $pesanan->vSpprbRi->vol_spprb ?? 0;
                            	$pesananVolTon  = ((float)$pesananVolBtg * (float)($pesanan->produk?->vol_m3 ?? 0) * 2.5) ?? 0;
								$sppVolBtg = ($sp3D[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_akhir; }) : 0;
								$sppVolTon = ($sp3D[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_ton_akhir; }) : 0;
								$sisaBtg = $pesananVolBtg - $sppVolBtg;
								$sisaTon = $pesananVolTon - $sppVolTon;
								$persen = 0;
								if ($pesananVolBtg > 0) {
								    $persen = $sisaBtg / $pesananVolBtg * 100;
								}
								$dtlRencana[$pesanan->kd_produk_konfirmasi] = [
									'sisaBtg' => $sisaBtg,
									'sppVolBtg' => $sppVolBtg
								];
							?>
							<tr>
								<td style="text-align: left">{{ $pesanan->produk->tipe }} {{$pesanan->kd_produk_konfirmasi}}</td>
								<td>{{ nominal($pesananVolBtg) }}</td>
								<td>{{ nominal($pesananVolTon) }}</td>
								<td>{{ nominal($sppVolBtg) }}</td>
								<td>{{ nominal($sppVolTon) }}</td>
								<td>{{ nominal($sisaBtg) }}</td>
								<td>{{ nominal($sisaTon) }}</td>
								<td>{{ round($persen, 2) }}</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="8">Data tidak ditemukan</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-lg-12">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Proyek</label>
			{!! Form::text('nama_proyek', !empty($data->npp)?$data->npp->nama_proyek:null, ['class'=>'form-control form-control-solid', 'id'=>'nama_proyek', 'readonly']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Pelanggan</label>
			{!! Form::text('nama_pelanggan', !empty($data->npp)?$data->npp->nama_pelanggan:null, ['class'=>'form-control form-control-solid', 'id'=>'nama_pelanggan', 'readonly']) !!}
		</div>	
	</div>

	<div class="col-lg-6 hide">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Lokasi Muat</label>
			{!! Form::text('pat', implode('|', $lokasi_muat ?? []), ['class'=>'form-control form-control-solid', 'id'=>'pat', 'readonly']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Tujuan</label>
			{!! Form::text('tujuan', null, ['class'=>'form-control form-control-solid', 'id'=>'tujuan', 'readonly']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">NPP</label>
			{!! Form::text('no_npp', null, ['class'=>'form-control form-control-solid', 'id'=>'no_npp', 'readonly']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Estimasi Total Ritase</label>
			{!! Form::text('rit', null, ['class'=>'form-control form-control-solid', 'id'=>'rit', 'readonly']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Jarak (KM)</label>
			{!! Form::text('jarak_km', null, ['class'=>'form-control form-control-solid', 'id'=>'jarak_km', 'readonly']) !!}
		</div>	
	</div>
<?php //dd($dtlRencana); ?>
		<!-- Detail Pekerjaan -->
	<div class="col-lg-12 mt-10">
		<h3 class="card-title">Detail Rencana Produk</h3>
		<div class="hover-scroll-overlay-y h-400px">
			<table id="tabel_detail_pekerjaan" class="table table-row-bordered text-center">
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
						<th>KSDM Vol</th>
						<th>PEO Vol</th>
						<th>MUnit Vol</th>
					</tr>
				</thead>
				<tbody>
					@if (count($data->detail) > 0 && count($tblPesanan) > 0)
						<?php $i = 1; ?>
						@foreach($data->detail as $pesanan)
							<tr>
								<td>{{ $i }}</td>							
								<td>{{ $pesanan->produk->tipe }} {{$pesanan->kd_produk}}</td>
								<td>
									{{$pesanan->kd_produk}}
								</td>							
								<td>
									{{ $pesanan->vol }}
								</td>				
								<td>
									{{ isset($dtlRencana[$pesanan->kd_produk])?$dtlRencana[$pesanan->kd_produk]['sppVolBtg']:0 + $pesanan->vol }}
								</td>	
								<td>
									{{ $pesanan->ket }}
								</td>							
								<td>
									<input class="form-check-input" name="rencana[{{$i}}][segmental]" type="checkbox" value="{{ !empty($pesanan->segmental)?1:0 }}" {{!empty($pesanan->segmental)?"checked":""}} id="flexCheckDefault"  disabled />
								</td>							
								<td>
									{{ $pesanan->jml_segmen }}
								</td>
								<td>
									{{ $pesanan->app1_vol }}
								</td>
								<td>
									{{ $pesanan->app2_vol }}
								</td>
								<td>
									{{ $pesanan->app3_vol }}
								</td>							
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
	<!-- end:Detail Pekerjaan -->
	<div class="col-lg-12 mb-3">
		<h3 class="card-title">Catatan Pembayaran</h3>
	</div>
	<div class="col-lg-12 mb-3">
		<div class="row">
			<div class="col-lg-2 mb-2">
				1. Uang Muka	
			</div>
			<div class="col-lg-2 mb-2">
				@if ($data->chk_tanpa_dp > 0)
					{{ 'Tanpa Uang Muka' }}
				@elseif($data->chk_kontrak > 0)
					{{ 'Sudah dibayar' }}
				@else
					{{ 'Belum dibayar' }}
				@endif
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2 mb-2">
				2. Progress Produksi
			</div>
			<div class="col-lg-2 mb-2">
				{{ ($data->chk_produksi > 0)?"Sudah dibayar":"Belum dibayar"}}
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2 mb-2">
				3. Progress Distribusi		
			</div>
			<div class="col-lg-2 mb-2">
				{{ ($data->chk_distribusi > 0)?"Sudah dibayar":"Belum dibayar"}}
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2 mb-2">
				3. Progress Distribusi		
			</div>
			<div class="col-lg-2 mb-2">
				{{ ($data->chk_distribusi > 0)?"Sudah dibayar":"Belum dibayar"}}
			</div>
		</div>
	</div>
	
	
	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Rencana Pengiriman</label>
			{!! Form::text('jadwal', date('d-m-Y', strtotime($start)) . ' - ' . date('d-m-Y', strtotime($end)), ['class'=>'form-control form-control-solid', 'id'=>'daterange', 'disabled']) !!}
		</div>	
	</div>

	<div class="col-lg-12">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Catatan Pelaksana</label>
			{!! Form::textarea('catatan', null, ['class'=>'form-control form-control-solid', 'id'=>'daterange', 'rows' => '5', 'readonly']) !!}
		</div>	
	</div>
	<div class="col-lg-12">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Catatan KSDM</label>
			{!! Form::textarea('catatan_app1', null, ['class'=>'form-control form-control-solid', 'id'=>'catatan', 'rows' => '5', 'readonly']) !!}
		</div>	
	</div>
	<div class="col-lg-12">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Catatan PEO</label>
			{!! Form::textarea('catatan_app2', null, ['class'=>'form-control form-control-solid', 'id'=>'catatan', 'rows' => '5', 'readonly']) !!}
		</div>	
	</div>
</div>