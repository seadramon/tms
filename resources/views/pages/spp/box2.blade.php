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
				<tbody>
					@if (count($tblPesanan) > 0)
						@foreach($tblPesanan as $row)
							<?php 
								$volm3 = !empty($row->vol_m3)?$row->vol_m3:1;
								$pesananVolBtg = $row->vol_spprb;
								$pesananVolTon = $row->vol_spprb * $volm3 * 2.5;
								$sppSebelumVolBtg = $row->vol;
								$sppSebelumVolTon = $row->vol * $volm3 * 2.5;
								$sisaBtg = $pesananVolBtg - $sppSebelumVolBtg;
								$sisaTon = $pesananVolTon - $sppSebelumVolTon;
								if ($pesananVolBtg > 0) {
								    $persen = $sisaBtg / $pesananVolBtg * 100;
								}
							?>
							<tr>
								<td>{{ $row->tipe }}</td>
								<td>{{ nominal($pesananVolBtg) }}</td>
								<td>{{ nominal($pesananVolTon) }}</td>
								<td>{{ nominal($sppSebelumVolBtg) }}</td>
								<td>{{ nominal($sppSebelumVolTon) }}</td>
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
			{!! Form::text('nama_proyek', $npp->nama_proyek, ['class'=>'form-control', 'id'=>'nama_proyek']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Pelanggan</label>
			{!! Form::text('nama_pelanggan', $npp->nama_pelanggan, ['class'=>'form-control', 'id'=>'nama_pelanggan']) !!}
		</div>	
	</div>

	<div class="col-lg-6 hide">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Lokasi Muat</label>
			{!! Form::text('pat', $pat->ket, ['class'=>'form-control', 'id'=>'pat']) !!}
			{!! Form::hidden('pat_singkatan', $pat->singkatan, ['class'=>'form-control', 'id'=>'pat_singkatan']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Tujuan</label>
			{!! Form::text('tujuan', $npp->kab.', '.$npp->kec, ['class'=>'form-control', 'id'=>'tujuan']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">NPP</label>
			{!! Form::text('no_npp', $npp->no_npp, ['class'=>'form-control', 'id'=>'no_npp']) !!}
			{!! Form::hidden('no_spprb', $noSpprb, ['class'=>'form-control', 'id'=>'no_spprb']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Estimasi Total Ritase</label>
			{!! Form::text('rit', null, ['class'=>'form-control', 'id'=>'rit']) !!}
		</div>	
	</div>

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Jarak (KM)</label>
			{!! Form::text('jarak_km', null, ['class'=>'form-control', 'id'=>'jarak_km']) !!}
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
				<tbody>
					@if (count($tblPesanan) > 0)
						<?php $i = 1; ?>
						@foreach($tblPesanan as $row)
							<tr>
								<td>{{ $i }}</td>							
								<td>{{ $row->tipe }}</td>							
								<td>
									<input type="text" name="rencana[{{$i}}][kd_produk]" value="{{$row->kd_produk}}" class="form-control" readonly>
								</td>							
								<td>
									<input type="text" name="rencana[{{$i}}][saat_ini]" data-sblmbtg="{{$row->vol}}" data-urutan="{{$i}}" class="form-control saat-ini decimal" onkeyup="sdSaatIni({{$row->vol}}, {{$i}})" id="id-saatini-{{$i}}">
								</td>				
								<td>
									<input type="number" name="rencana[{{$i}}][sd_saat_ini]" id="id-sdsaatini-{{$i}}" class="form-control" readonly>
								</td>			
								<td>
									<input type="text" name="rencana[{{$i}}][ket]" class="form-control">
								</td>							
								<td>
									<input class="form-check-input" name="rencana[{{$i}}][segmental]" type="checkbox" value="1" id="flexCheckDefault"/>
								</td>							
								<td>
									<input type="text" name="rencana[{{$i}}][jml_segmen]" class="form-control decimal">
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

	<div class="col-lg-6">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Rencana Pengiriman</label>
			{!! Form::text('jadwal', null, ['class'=>'form-control', 'id'=>'daterange']) !!}
		</div>	
	</div>

	<div class="col-lg-12">
		<div class="form-group">
			<label class="fs-6 fw-bold mt-2 mb-3">Keterangan</label>
			{!! Form::textarea('catatan', null, ['class'=>'form-control', 'id'=>'daterange', 'rows' => '5']) !!}
		</div>	
	</div>
</div>

<script type="text/javascript">
	$("#daterange").daterangepicker({
		locale: {
            format: 'DD-MM-YYYY'
		}
	});
	
	function sdSaatIni(vol, urutan) {
	console.log(vol);
	console.log(urutan);

	let saatIni = $("#id-saatini-" + urutan).val();
	let hitungan = parseInt(vol) + parseInt(saatIni);
	
	$("#id-sdsaatini-" + urutan).val(hitungan);
}
</script>