<div class="card-header">
	<h3 class="card-title">Rute SPPrB</h3>
</div>

<div class="card-body">
	<table class="table table-row-bordered text-left">
		<thead>
			<tr>
				<th>NO SPPRB</th>
				<th>PPB</th>
				<th>TIPE</th>
				<th>KD PRODUK</th>
				<th>JD MULAI PROD</th>
				<th>JD MULAI DIST</th>
				<th>VOLUM BTG</th>
			</tr>
		</thead>
		<tbody>
			@if (count($spprb) > 0)
				@foreach($spprb as $row)
					<tr>
						<td>{{ $row->spprblast }}</td>
						<td>{{ !empty($row->pat)?$row->pat->ket:'' }}</td>
						<td>{{ !empty($row->produk)?$row->produk->tipe:'' }}</td>
						<td>{{ $row->kd_produk }}</td>
						<td>{{ !empty($row->jadwal1)?date('d-m-Y', strtotime($row->jadwal1)):'' }}</td>
						<td>{{ !empty($row->jadwal2)?date('d-m-Y', strtotime($row->jadwal2)):'' }}</td>
						<td>{{ $row->vol_spprb }}</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td colspan="7" style="text-align: center;color: grey;">Data tidak ditemukan</td>
				</tr>
			@endif
		</tbody>
	</table>
</div>

<div class="card-footer">
</div>