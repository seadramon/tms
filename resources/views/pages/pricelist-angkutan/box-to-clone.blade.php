<div id="box_to_clone" class="box" style="display: none;">
    <div class="separator separator-dashed border-primary my-10"></div>
    <div class="row mb-5">
        <div class="form-group col-lg-6">
            <input type="hidden" name="index_" id="index_1" value="0">
        </div>
        <div class="form-group col-lg-6" style="text-align: right;">
            <button type="button" class="btn btn-light-danger btn_hapus mt-8" id="btn_hapus_1" data-id="1">
                <i class="la la-trash"></i>Hapus
            </button>
        </div>
        <div class="form-group col-lg-6">
            <label class="form-label">Jenis Angkutan</label>
            {!! Form::select('kd_material[]', $kd_material, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'disabled']) !!}
        </div>

        <div class="form-group col-lg-6">
            <label class="form-label">Jenis Pemuatan</label>
            {!! Form::select('jenis_muat[]', $jenis_muat, null, ['class'=>'form-control form-select-solid jenis_muat', 'data-control'=>'select2', 'disabled']) !!}
        </div>

        <div class="form-group col-lg-3">
            <label class="form-label">Tanggal Mulai Berlaku</label>
            <div class="col-lg-12">
                <div class="input-group date">
                    {!! Form::text('tgl_mulai[]', $awal ?? null, ['class'=>'form-control datepicker', 'disabled']) !!}
                    <div class="input-group-append">
                        <span class="input-group-text" style="display: block">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-lg-3">
            <label class="form-label">Tanggal Selesai Berlaku</label>
            <div class="col-lg-12">
                <div class="input-group date">
                    {!! Form::text('tgl_selesai[]', $akhir ?? null, ['class'=>'form-control datepicker', 'disabled']) !!}
                    <div class="input-group-append">
                        <span class="input-group-text" style="display: block">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-lg-6">
            <label class="form-label">Lokasi Pemuatan</label>
            <select class="form-control form-select-solid" data-control="select2" name="kd_muat[]" disabled>
                <option value="">Pilih Jenis Pemuatan terlebih dahulu!</option>
            </select>
        </div>

        <div class="form-group col-lg-4">
            <label class="form-label">Upload File Excel (Harga Satuan)</label>
            {!! Form::file('file_excel[]', ['class'=>'form-control', 'disabled', "accept" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"]) !!}
        </div>

        <div class="form-group col-lg-2">
            <label class="form-label">&nbsp;</label>
            <button type="button" class="btn btn-success form-control upload_excel">
                Upload Excel
            </button>
        </div>
        <div class="form-group col-lg-6">
            <label class="form-label">Vendor</label>
            {!! Form::select('vendor_', $vendor, null, ['class'=>'form-control form-select-solid vendor', 'data-control'=>'select2', 'id'=>'vendor_1', 'data-id'=>'1', 'multiple' => true]) !!}
        </div>
    </div>
    <div id="container_harsat" data-id="">
                                
    </div>
</div>