<div class="col-lg-4 mb-2">
	1. Uang Muka	
</div>
<div class="col-lg-2 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="uang_muka" type="radio" value="2" id="flexRadioDefault"/>
	    <label class="form-check-label" for="flexRadioDefault">
	        Sudah dibayar
	    </label>
	</div>
</div>
<div class="col-lg-2 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="uang_muka" type="radio" value="0" id="flexRadioDefault"/>
	    <label class="form-check-label" for="flexRadioDefault">
	        Belum dibayar
	    </label>
	</div>
</div>
<div class="col-lg-4 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="uang_muka" type="radio" value="1" id="flexRadioDefault"/>
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
	    <input class="form-check-input" name="progres_prod" type="radio" value="1" id="flexRadioDefault"/>
	    <label class="form-check-label" for="flexRadioDefault">
	        Sudah dibayar
	    </label>
	</div>
</div>
<div class="col-lg-6 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="progres_prod" type="radio" value="0" id="flexRadioDefault"/>
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
	    <input class="form-check-input" name="progres_distribusi" type="radio" value="1" id="flexRadioDefault"/>
	    <label class="form-check-label" for="flexRadioDefault">
	        Sudah dibayar
	    </label>
	</div>
</div>
<div class="col-lg-6 mb-2">
	<div class="form-check form-check-custom form-check-solid">
	    <input class="form-check-input" name="progres_distribusi" type="radio" value="0" id="flexRadioDefault"/>
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
		{!! Form::textarea('catatan_app1', null, ['class'=>'form-control', 'id'=>'catatan', 'rows' => '5']) !!}
	</div>	
</div>