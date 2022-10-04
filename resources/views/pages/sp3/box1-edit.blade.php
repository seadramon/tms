<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Tambah Baru SP3 / SPK</h3>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">NPP</label>
                {!! Form::text('no_npp', $data->npp?->no_npp . ' | ' . $data->npp?->nama_proyek, ['class'=>'form-control', 'id'=>'no_npp', 'readonly']) !!}
            </div>
            
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">No. SP3 / SPK</label>
                {!! Form::text('no_sp3', 'AUTO', ['class'=>'form-control', 'id'=>'no_sp3', 'readonly']) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Vendor</label>
                {!! Form::text('vendor_id', $data->vendor?->nama, ['class'=>'form-control', 'id'=>'vendor_id', 'readonly']) !!}
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Pekerjaan</label>
                {!! Form::text('kd_jpekerjaan', $data->jenisPekerjaan?->ket, ['class'=>'form-control', 'id'=>'kd_jpekerjaan', 'readonly']) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Satuan HarSat</label>
                {!! Form::text('sat_harsat', ucfirst($data->satuan_harsat), ['class'=>'form-control', 'id'=>'sat_harsat', 'readonly']) !!}
            </div>
        </div>
    </div>
</div>