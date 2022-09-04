<div class="col-lg-4 mb-2">
	1. Uang Muka	
</div>
<div class="col-lg-2 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="uang_muka" {{ ($data->chk_kontrak == '2')?'checked':'' }} type="radio" value="2" id="flexRadioDefault" disabled />
	    <label class="form-check-label" for="flexRadioDefault">
	        Sudah dibayar
	    </label>
	</div>
</div>
<div class="col-lg-2 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="uang_muka" {{ ($data->chk_kontrak == '0')?'checked':'' }} type="radio" value="0" id="flexRadioDefault" disabled />
	    <label class="form-check-label" for="flexRadioDefault">
	        Belum dibayar
	    </label>
	</div>
</div>
<div class="col-lg-4 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="uang_muka" {{ ($data->chk_tanpa_dp == '1')?'checked':'' }} type="radio" value="1" id="flexRadioDefault" disabled />
	    <label class="form-check-label" for="flexRadioDefault">
	        Tanpa Uang Muka
	    </label>
	</div>
</div>

<div class="col-lg-4 mb-2">
	2. Progress Produksi	
</div>
<div class="col-lg-2 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="progres_prod" {{ ($data->chk_produksi == '1')?'checked':'' }} type="radio" value="1" id="flexRadioDefault" disabled />
	    <label class="form-check-label" for="flexRadioDefault">
	        Sudah dibayar
	    </label>
	</div>
</div>
<div class="col-lg-6 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="progres_prod" {{ ($data->chk_produksi == '0')?'checked':'' }} type="radio" value="0" id="flexRadioDefault" disabled />
	    <label class="form-check-label" for="flexRadioDefault">
	        Belum dibayar
	    </label>
	</div>
</div>

<div class="col-lg-4 mb-2">
	3. Progress Distribusi	
</div>
<div class="col-lg-2 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="progres_distribusi" {{ ($data->chk_distribusi == '1')?'checked':'' }} type="radio" value="1" id="flexRadioDefault" disabled />
	    <label class="form-check-label" for="flexRadioDefault">
	        Sudah dibayar
	    </label>
	</div>
</div>
<div class="col-lg-6 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="progres_distribusi" {{ ($data->chk_distribusi == '0')?'checked':'' }} type="radio" value="0" id="flexRadioDefault" disabled />
	    <label class="form-check-label" for="flexRadioDefault">
	        Belum dibayar
	    </label>
	</div>
</div>


<div class="col-lg-6">
	<div class="form-group">
		<label class="fs-6 fw-bold mt-2 mb-3">Rencana Pengiriman</label>
		<?php $range = date('Y-m-d', strtotime($data->jadwal1)).' - '.date('Y-m-d', strtotime($data->jadwal2)); ?>
		<input type="text" disabled value="{{ $range }}" class="form-control form-control-solid">
	</div>	
</div>

<div class="col-lg-12">
	<div class="form-group">
		<label class="fs-6 fw-bold mt-2 mb-3">Keterangan Pelaksana</label>
		{!! Form::textarea('catatan', null, ['class'=>'form-control form-control-solid', 'id'=>'catatan', 'rows' => '5', 'disabled']) !!}
	</div>	
</div>

<div class="col-lg-12">
	<div class="form-group">
		<label class="fs-6 fw-bold mt-2 mb-3">Keterangan Approval KSDM</label>
		{!! Form::textarea('catatan_app1', null, ['class'=>'form-control form-control-solid', 'id'=>'catatan_app1', 'rows' => '5', 'disabled']) !!}
	</div>	
</div>

<div class="col-lg-12">
	<div class="form-group">
		<label class="fs-6 fw-bold mt-2 mb-3">Keterangan Approval PEO</label>
		{!! Form::textarea('catatan_app2', null, ['class'=>'form-control form-control-solid', 'id'=>'catatan_app2', 'rows' => '5', 'disabled']) !!}
	</div>	
</div>

<div class="col-lg-12">
	<div class="form-group">
		<label class="fs-6 fw-bold mt-2 mb-3">Keterangan Approval MWP</label>
		{!! Form::textarea('catatan_app3', null, ['class'=>'form-control', 'id'=>'catatan_app3', 'rows' => '5']) !!}
	</div>	
</div>