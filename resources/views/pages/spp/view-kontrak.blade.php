<div class="col-12">
	@if (!empty($kontrak))
		@if (!empty($kontrak->dok))
			<object data="data:application/pdf;base64,<?php echo base64_encode($kontrak->dok) ?>" type="application/pdf" style="height:40em;width:100%"></object>
		@else
			<embed src="{{ $kontrak->path_file }}" style="height:40em;width:100%">
		@endif
	@else
		<p style="text-align: center;color: grey;">Data tidak ditemukan</p>
	@endif
</div>