<div class="col-12">
	<table class="table table-row-bordered text-left">
		<thead>
			<tr>
				<th>NO KONTRAK</th>
				<th>NAMA VENDOR</th>
				<th>VOLUME BTG</th>
				<th>VOLUME TON</th>
				<th>STATUS</th>
			</tr>
		</thead>
		<tbody>
			@if (count($angkutan) > 0)
				@foreach($angkutan as $row)
					<tr>
						<td>{{ $row->no_sp3 }}</td>
						<td>{{ $row->vendorname }}</td>
						<td>{{ $row->volakhir }}</td>
						<td>{{ $row->voltonakhir }}</td>
						<td>
							@switch(true)
							    @case($row->st_wf == 0)
							        <i class="fa fa-square" style="color:yellow; font-size:20px;"></i>
							        @break
							    @case($row->st_wf == 1 && $row->app1 == 0)
							        <i class="fa fa-square" style="color:orange; font-size:20px;"></i>
							        @break
							 	@case($row->st_wf == 1 && $row->app1 == 1)
							 		<i class="fa fa-square" style="color:green; font-size:20px;"></i>
							        @break
							@endswitch
							@if ($row->app2 == 1)
								<i class="fa fa-square" style="color:green; font-size:20px;"></i>
							@else
								<i class="fa fa-square" style="color:grey; font-size:20px;"></i>
							@endif
						</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td colspan="5" style="text-align: center;color: grey;">Data tidak ditemukan</td>
				</tr>
			@endif
		</tbody>
	</table>
</div>