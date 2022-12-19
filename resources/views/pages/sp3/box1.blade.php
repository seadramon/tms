<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Tambah Baru SP3 / SPK</h3>
    </div>

    <div class="card-body">    
        <div class="alert alert-danger alert-dismissible fade" id="alert-box1" role="alert">
            NPP, Vendor, dan Pekerjaan harus diisi!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">NPP</label>
                <select class="form-control search-npp" name="no_npp" id="no_npp">
                    @if ($npp)
                        <option value="{{$npp->no_npp}}">{{$npp->no_npp}} | {{$npp->nama_proyek}}</option>
                    @endif
                </select>
            </div>
            
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">No. SP3 / SPK</label>
                {!! Form::text('no_sp3', 'AUTO', ['class'=>'form-control', 'id'=>'no_sp3', 'disabled']) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Vendor</label>
                {!! Form::select('vendor_id', $vendor, $vendor_id, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'vendor_id']) !!}
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Pekerjaan</label>
                {!! Form::select('kd_jpekerjaan', $jenisPekerjaan, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kd_jpekerjaan']) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Satuan HarSat</label>
                {!! Form::select('sat_harsat', $sat_harsat, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'sat_harsat']) !!}
            </div>
        </div>
    </div>

    <div class="card-footer" style="text-align: right;">
        <input type="button" class="btn btn-primary" id="buat_draft" value="Buat Draft">
    </div>
</div>