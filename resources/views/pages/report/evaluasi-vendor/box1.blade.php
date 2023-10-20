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
            <div class="col-lg-6 custom-form tahun1 hidden">
                <label class="form-label col-sm-3 custom-label">Tahun</label>
                {!! Form::select('tahun1', $tahun, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'tahun1']) !!}
            </div>
            <div class="col-lg-6 custom-form mb-2 tahun2">
                <label class="form-label col-sm-3 custom-label">Tahun</label>
                {!! Form::select('tahun2', $tahun, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'tahun2']) !!}
            </div>
        </div>
        
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Tipe</label>
                {!! Form::select('tipe', $tipe, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'tipe']) !!}
            </div>
            <div class="col-lg-6 custom-form tahun1 hidden">
                <label class="form-label col-sm-3 custom-label">Minggu ke-</label>
                <div class="col-lg-4">
                    {!! Form::select('minggu1', $periode_minggu, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'minggu1']) !!}
                </div>
                <label class="form-label col-sm-1 custom-label">s/d</label>
                <div class="col-lg-4">
                    {!! Form::select('minggu2', $periode_minggu, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'minggu2']) !!}
                </div>
            </div>
            <div class="col-lg-6 custom-form mb-2 tahun2">
                <label class="form-label col-sm-3 custom-label">Cut Off</label>
                {!! Form::select('range', $range, null, ['class'=>'form-control form-select-solid col-sm-1', 'data-control'=>'select2', 'id'=>'range']) !!}
                {!! Form::select('month', $month, null, ['class'=>'form-control form-select-solid col-sm-2', 'data-control'=>'select2', 'id'=>'month']) !!}
            </div>
        </div>
    </div>

    <div class="card-footer" style="text-align: right;">
        <input type="button" class="btn btn-primary" id="filter" value="Filter">
    </div>
</div>