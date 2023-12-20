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

        <div class="form-group col-lg-3">
            <label class="form-label">Tanggal Mulai Berlaku</label>
            <div class="col-lg-12">
                <div class="input-group date">
                    {!! Form::text("tgl_mulai[]", $awal ?? null, ["class"=>"form-control datepicker", "disabled"]) !!}
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
                    {!! Form::text("tgl_selesai[]", $akhir ?? null, ["class"=>"form-control datepicker", "disabled"]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text" style="display: block">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-6" style="text-align: right;">
            <button type="button" class="btn btn-light-dark add_harsat mt-8" id="add_harsat_1" data-id="1">
                <i class="la la-plus"></i>Tambah Harga Satuan
            </button>
        </div>
    </div>
    <div id="container_harsat" data-id="">
        <label class="form-label">Harga Satuan</label>

        <table class="table table-row-bordered text-center">
            <thead>
                <tr>
                    <th>Kondisi Penyerahan</th>
                    <th>Unit Asal</th>
                    <th>Pelabuhan Asal</th>
                    <th>Pelabuhan Tujuan</th>
                    <th>Site</th>
                    <th>Harsat Final</th>
                    <th>Satuan</th>
                </tr>
            </thead>

            <tbody class="tbody-harsat">
            </tbody>
        </table>                    
    </div>
</div>