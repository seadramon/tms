<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Pemenuhan Armada</h3>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Unit Kerja</label>
                {!! Form::select('kd_pat', $kd_pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'kd_pat']) !!}
            </div>
            
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Pekerjaan</label>
                {!! Form::select('pekerjaan', $pekerjaan, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'pekerjaan']) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Vendor</label>
                {!! Form::select('vendor_id', $vendor_id, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'vendor_id']) !!}
            </div>
            
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Periode</label>
                <div class="col-lg-9">
                    <div class="input-group date">
                        {!! Form::text('periode', null, ['class'=>'form-control datepicker', 'id'=>'periode']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" style="display: block">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Tipe</label>
                {!! Form::select('tipe', $tipe, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'tipe']) !!}
            </div>
        </div>
    </div>

    <div class="card-footer" style="text-align: right;">
        <input type="button" class="btn btn-primary" id="filter" value="Filter">
    </div>
</div>