<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Monitoring Proyek Berjalan</h3>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <div class="col-lg-4 custom-form">
                <label class="form-label col-sm-3 custom-label">Unit Kerja</label>
                {!! Form::select('kd_pat', $kd_pat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'kd_pat']) !!}
            </div>
            
            <div class="col-lg-8 custom-form">
                <label class="form-label col-sm-3 custom-label">PPB</label>
                {!! Form::select('ppb_muat', $ppb_muat, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'ppb_muat']) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-4 custom-form">
                <label class="form-label col-sm-3 custom-label">Tahun</label>
                {!! Form::select('tahun', $tahun, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'tahun']) !!}
            </div>
            <div class="col-lg-8 custom-form">
                <label class="form-label col-sm-3 custom-label">Minggu ke-</label>
                <div class="col-lg-4">
                    {!! Form::select('minggu1', $periode_minggu, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'minggu1']) !!}
                </div>
                <label class="form-label col-sm-1 custom-label">s/d</label>
                <div class="col-lg-4">
                    {!! Form::select('minggu2', $periode_minggu, null, ['class'=>'form-control form-select-solid col-sm-3', 'data-control'=>'select2', 'id'=>'minggu2']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer" style="text-align: right;">
        <input type="button" class="btn btn-primary" id="filter" value="Filter">
    </div>
</div>