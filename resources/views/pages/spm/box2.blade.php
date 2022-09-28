<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Detail SPP</h3>
    </div>

    <div class="card-body">
        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" vertical-align="center">Type</th>
                    <th colspan="2" class="text-center">SPP</th>
                    <th colspan="2" class="text-center">SPP Terdistribusi</th>
                    <th colspan="3" class="text-center">Volume Sisa</th>
                </tr>
                <tr class="table table-striped gy-7 gs-7">
                    <th class="text-center">Vol (Btg)</th>
                    <th class="text-center">Vol (Ton)</th>
                    <th class="text-center">Vol (Btg)</th>
                    <th class="text-center">Vol (Ton)</th>
                    <th class="text-center">Vol (Btg)</th>
                    <th class="text-center">Vol (Ton)</th>
                    <th class="text-center">%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail_spp as $row)
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                    <td class="text-center">{{ $row->type_produk }}</td>
                    <td class="text-center">{{ $row->spp_vol_btg }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center">{{ $row->sppdis_vol_btg }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center">{{ ($row->spp_vol_btg - $row->sppdis_vol_btg) }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center">%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">No. NPP</label>
                <input class="form-control" type="text" readonly  value="{{ $no_npp }}" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">No. SPPrB</label>
                <input class="form-control" type="text" readonly value="{{ $no_spprb }}" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">Pelanggan</label>
                <input class="form-control" type="text" readonly  value="{{ $pelanggan }}" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Nama Proyek</label>
                <input class="form-control" type="text" readonly value="{{ $nama_proyek }}" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-8 required ">Perusahaan / Pemilik Angkutan</label>
                <select class="form-control form-select-solid" data-control="select2" data-placeholder="Pilih Perusahaan / Pemilik Angkutan" name="vendor" id="vendor">
                    <option></option>
                    @foreach ($vendor_angkutan as $row)
                        <option value="{{ $row->vendor_id }}">{{ $row->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Tujuan</label>
                <input class="form-control" type="text" readonly value="{{ $tujuan->infoPasar->region->kabupaten_name }} - {{ $tujuan->infoPasar->region->kecamatan_name }}" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">Jarak</label>
                <input class="form-control" type="text" readonly  value="{{ $jarak->jarak_km ?? 0 }}" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Kondisi Penyerahan</label>
                <input class="form-control" type="text" readonly value="{{ $kp }}" />
            </div>
        </div>

        <div class="form-group row mt-5">
            <table class="table">
                <thead>
                    <tr class="fw-bold text-gray-800 border border-gray-400">
                        <th class="text-center" width="30%">Tipe Produk</th>
                        <th class="text-center">Volume</th>
                        <th class="text-center">Jumlah Segmen</th>
                        <th class="text-center">Volume SPPB</th>
                        <th class="text-center">Volume SPM</th>
                        <th class="text-center">Volume Titipan</th>
                        <th class="text-center" width="30%">Keterangan</th>
                        <th class="text-center" >opsi</th>
                    </tr>
                </thead>
                <tbody >
                    <tr class="fw-semibold text-gray-800 border border-gray-400">
                        <td style="padding-left: 10px;">
                            <select class="form-control form-select-solid ml-2" data-control="select2" data-placeholder="Pilih Tipe Produk"
                                    name="tipe_produk_select[]" id="tipe_produk_select">
                                <option></option>
                                @foreach($detail_spp as $row)
                                    <option value="{{ $row->kode_produk }}">{{ $row->type_produk }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control" step="any" id="volume-show" onkeyup="validate_vol()" />
                        </td>
                        <td class="text-center">
                            <label class="text-center" id="segmen-show"></label>
                        </td>
                        <td  class="text-center">
                            <label class="text-center" id="volsppb-show"></label>
                        </td>
                        <td  class="text-center">
                            <label class="text-center" id="volspm-show"></label>
                        </td>
                        <td  class="text-center">0</td>
                        <td  class="text-center">
                            <input type="text" class="form-control" id="keterangan-show"/>
                        </td>
                        <td style="padding-right: 10px;">
                            <a href="javascript:void(0)" class="btn btn-icon btn-success" id="add_muat"><i class="fa fa-plus"></i></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot id="body_muat" style="border-bottom: 1px solid black;">

                </tfoot>
            </table>
        </div>

    </div>

    <div class="card-footer" style="text-align: right;">
        <input type="button" class="btn btn-primary" id="buat_draft" value="Submit">
    </div>
</div>
