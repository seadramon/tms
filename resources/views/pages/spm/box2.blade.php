<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Detail SPP</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered gy-7 gs-7">
            <thead>
                <tr>
                    <th rowspan="2">Type</th>
                    <th colspan="2" class="text-center">SPP</th>
                    <th colspan="2" class="text-center">SPP Terdistribusi</th>
                    <th colspan="3" class="text-center">Volume Sisa</th>
                </tr>
                <tr class="table table-striped gy-7 gs-7">
                    <th>Vol (Btg)</th>
                    <th>Vol (Ton)</th>
                    <th>Vol (Btg)</th>
                    <th>Vol (Ton)</th>
                    <th>Vol (Btg)</th>
                    <th>Vol (Ton)</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail_spp as $row)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">No. NPP</label>
                <input class="form-control" type="text" readonly  value="" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">No. SPPrB</label>
                <input class="form-control" type="text" readonly value="" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">Pelanggan</label>
                <input class="form-control" type="text" readonly  value="" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Nama Proyek</label>
                <input class="form-control" type="text" readonly value="" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-8 required ">Perusahaan / Pemilik Angkutan</label>
                <input class="form-control" type="text" readonly  value="" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Tujuan</label>
                <input class="form-control" type="text" readonly value="" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">Jarak</label>
                <input class="form-control" type="text" readonly  value="" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Kondisi Penyerahan</label>
                <input class="form-control" type="text" readonly value="" />
            </div>
        </div>

    </div>

    <div class="card-footer" style="text-align: right;">
        <input type="button" class="btn btn-primary" id="buat_draft" value="Submit">
    </div>
</div>