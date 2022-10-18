<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Tambah Baru SP3 / SPK</h3>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">NPP</label>
                {!! Form::text('no_npp_text', $data->npp?->no_npp . ' | ' . $data->npp?->nama_proyek, ['class'=>'form-control', 'id'=>'no_npp', 'disabled']) !!}
                {!! Form::hidden('no_npp', $data->no_npp, []) !!}
            </div>
            
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">No. SP3 / SPK</label>
                {!! Form::text('no_sp3_text', $data->no_sp3, ['class'=>'form-control', 'id'=>'no_sp3', 'disabled']) !!}
                {!! Form::hidden('no_sp3', $data->no_sp3, []) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Vendor</label>
                {!! Form::text('vendor_id_text', $data->vendor?->nama, ['class'=>'form-control', 'id'=>'vendor_id', 'disabled']) !!}
                {!! Form::hidden('vendor_id', $data->vendor_id, []) !!}
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Pekerjaan</label>
                {!! Form::text('kd_jpekerjaan_text', $data->jenisPekerjaan?->ket, ['class'=>'form-control', 'id'=>'kd_jpekerjaan', 'disabled']) !!}
                {!! Form::hidden('kd_jpekerjaan', $data->kd_jpekerjaan, []) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Satuan HarSat</label>
                {!! Form::text('sat_harsat_text', ucfirst($data->satuan_harsat), ['class'=>'form-control', 'id'=>'sat_harsat', 'disabled']) !!}
                {!! Form::hidden('sat_harsat', $data->satuan_harsat, []) !!}
            </div>
        </div>
    </div>
</div>