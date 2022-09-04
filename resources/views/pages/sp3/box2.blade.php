<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Detail Pesanan NPP</h3>
    </div>

    <div class="card-body">
        <table id="tabel_detail_pesanan" class="table table-row-bordered text-center">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle; text-align: left">Nama / Tipe Produk</th>
                    <th colspan="2">Pesanan</th>
                    <th colspan="2">Total SP3/SPK Sebelumnya</th>
                    <th colspan="2">Volume Sisa</th>
                </tr>
                
                <tr>
                    <th>Vol (Btg)</th>
                    <th>Vol (Ton)</th>
                    <th>Vol (Btg)</th>
                    <th>Vol (Ton)</th>
                    <th>Vol (Btg)</th>
                    <th>Vol (Ton)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailPesanan as $pesanan)
                    @php
                        $pesananVolBtg  = $pesanan->vol_konfirmasi ?? 0;
                        $pesananVolTon  = ((float)$pesananVolBtg * (float)($pesanan->produk?->vol_m3 ?? 0) * 2.5) ?? 0;
                        $sp3dVolBtg     = $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_akhir; });
                        $sp3dVolTon     = $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_ton_akhir; });
                        $sisaVolBtg     = $pesananVolBtg - $sp3dVolBtg;
                        $sisaVolTon     = $pesananVolTon - $sp3dVolTon;
                    @endphp
                    
                    <tr>
                        <td style="text-align: left">{{ $pesanan->produk->tipe }} {{$pesanan->kd_produk_konfirmasi}}</td>
                        {{-- <td>{{ $pesanan->kd_produk_konfirmasi }} {{$pesanan->produk->vol_m3 ?? 0}}</td> --}}
                        <td>{{ $pesananVolBtg }}</td>
                        <td>{{ $pesananVolTon }}</td>
                        <td>{{ $sp3dVolBtg }}</td>
                        <td>{{ $sp3dVolTon }}</td>
                        <td>{{ $sisaVolBtg }}</td>
                        <td>{{ $sisaVolTon }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br><br>

        <div class="row">
            <div class="form-group col-lg-12">
                <label class="form-label">Proyek</label>
                {!! Form::text('proyek', $npp->nama_proyek, ['class'=>'form-control', 'id'=>'proyek', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Pelanggan</label>
                {!! Form::text('pelanggan', $npp->nama_pelanggan, ['class'=>'form-control', 'id'=>'pelanggan', 'readonly']) !!}

            </div>
            
            <div class="form-group col-lg-3">
                <label class="form-label">Tujuan</label>
                {!! Form::text('region', $npp->infoPasar?->region?->kabupaten_name . ', ' . $npp->infoPasar?->region?->kecamatan_name, ['class'=>'form-control', 'id'=>'region', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-3">
                <label class="form-label">Tanggal</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('tgl_sp3', null, ['class'=>'form-control datepicker', 'id'=>'tgl_sp3']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" style="display: block">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">No BAN</label>
                {!! Form::select('no_ban', $ban, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_ban']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">No Induk Kontrak</label>
                {!! Form::select('no_kontrak_induk', $kontrak, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_kontrak_induk']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Nama Pihak Kedua / Vendor</label>
                {!! Form::text('vendor', $vendor->nama, ['class'=>'form-control', 'id'=>'vendor', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Jabatan</label>
                {!! Form::text('jabatan', 'TBC', ['class'=>'form-control', 'id'=>'jabatan', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">PIC</label>
                <select class="form-control search-pic" name="pic" id="pic" multiple required></select>
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Spesifikasi</label>
                {!! Form::text('spesifikasi', 'TBC', ['class'=>'form-control', 'id'=>'spesifikasi', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-3">
                <label class="form-label">Tanggal Penyerahan</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('jadwal1', null, ['class'=>'form-control datepicker', 'id'=>'jadwal1']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" style="display: block">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-3">
                <label class="form-label">&nbsp;</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('jadwal2', null, ['class'=>'form-control datepicker', 'id'=>'jadwal2']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" style="display: block">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Kondisi Penyerahan</label>
                {!! Form::text('kondisi_penyerahan', $kondisiPenyerahanDipilih, ['class'=>'form-control', 'id'=>'kondisi_penyerahan', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Estimasi Total Ritasi</label>
                {!! Form::number('rit', 0, ['class'=>'form-control', 'id'=>'rit', 'required']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Jarak</label>
                {!! Form::number('jarak_pesanan', $jarak, ['class'=>'form-control', 'id'=>'jarak_pesanan', 'required']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Harga Satuan Ritase</label>
                {!! Form::number('harga_satuan_ritase', 0, ['class'=>'form-control', 'id'=>'harga_satuan_ritase']) !!}
            </div>
        </div>
        
        <br><br>

        <table id="tabel_detail_pekerjaan" class="table table-row-bordered text-center">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Tipe</th>
                    <th>Jarak (KM)</th>
                    <th>Vol (Btg)</th>
                    <th>Vol (Ton)</th>
                    <th>Satuan</th>
                    <th>Harsat / [Btg/Ton]</th>
                    <th>Jumlah</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailPesanan as $key => $pesanan)
                    <tr class="detail_pekerjaan" id="detail_pekerjaan_{{ $key }}">
                        <td>
                            {!! Form::select('unit[]', $unit, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'unit_' . $key, 'row-id'=>$key, 'required']) !!}
                        </td>
                        <td>
                            {!! Form::hidden('kd_produk[]', $pesanan->produk->kd_produk, []) !!}
                            {!! Form::text('tipe[]', $pesanan->produk->tipe, ['class'=>'form-control', 'id'=>'tipe_' . $key, 'row-id'=>$key, 'required']) !!}
                        </td>
                        <td style="width: 9%;">
                            {!! Form::number('jarak_pekerjaan[]', $jarak, ['class'=>'form-control jarak_pekerjaan', 'id'=>'jarak_pekerjaan_' . $key, 'row-id'=>$key, 'required']) !!}
                        </td>
                        <td style="width: 10%;">
                            {!! Form::number('vol_btg[]', null, ['class'=>'form-control vol_btg', 'id'=>'vol_btg_' . $key, 'max'=>(float)$pesanan->vol_konfirmasi ?? 0, 'row-id'=>$key, 'required']) !!}
                            <input type="hidden" id="vol_btg_max_{{ $key }}" value="{{ (float)$pesanan->vol_konfirmasi ?? 0 }}">
                        </td>
                        <td style="width: 10%;">
                            {!! Form::number('vol_ton[]', null, ['class'=>'form-control vol_ton', 'id'=>'vol_ton_' . $key, 'row-id'=>$key, 'required']) !!}
                        </td>
                        <td>
                            {!! Form::select('satuan[]', $satuan, null, ['class'=>'form-control form-select-solid satuan', 'data-control'=>'select2', 'id'=>'satuan_' . $key, 'row-id'=>$key, 'required']) !!}
                        </td>
                        <td style="width: 10%;">
                            {!! Form::number('harsat[]', null, ['class'=>'form-control harsat', 'id'=>'harsat_' . $key, 'row-id'=>$key, 'required']) !!}
                        </td>
                        <td style="width: 10%;">
                            {!! Form::text('jumlah[]', null, ['class'=>'form-control', 'id'=>'jumlah_' . $key, 'row-id'=>$key, 'readonly']) !!}
                        </td>
                        <td style="vertical-align: middle; padding-left: 0px;">
                            <button class="btn btn-danger btn-sm delete_pekerjaan" id="delete_pekerjaan_{{ $key }}" row-id={{ $key }} style="padding: 5px 6px;">
                                <span class="bi bi-trash"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <th colspan="7" style="text-align: right;">Subtotal</th>
                    <td>
                        {!! Form::text('subtotal', null, ['class'=>'form-control', 'id'=>'subtotal', 'readonly']) !!}
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th colspan="7" style="text-align: right;">PPN</th>
                    <td>
                        {!! Form::select('ppn', $ppn, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'ppn']) !!}
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th colspan="7" style="text-align: right;">PPH</th>
                    <td>
                        {!! Form::select('pph', $ppn, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'pph']) !!}
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th colspan="7" style="text-align: right;">Total</th>
                    <td>
                        {!! Form::text('total', null, ['class'=>'form-control', 'id'=>'total', 'readonly']) !!}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <br><br>

        <div id="material_tambahan">
            <div class="form-group">
                <div data-repeater-list="material_tambahan">
                    <div data-repeater-item>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="form-label">Material</label>
                                {!! Form::text('material', null, ['class'=>'form-control', 'required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Spesifikasi</label>
                                {!! Form::text('spesifikasi', null, ['class'=>'form-control', 'required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Volume</label>
                                {!! Form::number('volume', null, ['class'=>'form-control', 'required']) !!}
                            </div>
                            <div class="col-md-3">
                                <a href="javascript:;" data-repeater-delete class="btn btn-md btn-light-danger mt-md-8">
                                    <i class="la la-trash-o"></i>Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="form-group" style="margin-top: 20px">
                <div class="col-md-3">
                    <a href="javascript:;" data-repeater-create class="btn btn-light-primary" id="button-add">
                        <i class="la la-plus"></i>Tambah
                    </a>
                </div>
            </div>
        </div>

        <br><br>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="5" class="col-md-12"></textarea>
        </div>

        <br><br>

        <div class="form-group">
            <label class="form-label">Dokumen Tagihan harus melampirkan :</label>
            
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Asli</th>
                        <th>Copy</th>
                        <th>&nbsp;</th>
                        <th></th>
                        <th>Asli</th>
                        <th>Copy</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Faktur / Invoice / Kwitansi</th>
                        <td>
                            {!! Form::number('invoice_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('invoice_copy', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>Packing List</th>
                        <td>
                            {!! Form::number('packing_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('packing_copy', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Faktur Pajak</th>
                        <td>
                            {!! Form::number('pajak_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('pajak_copy', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BAPB</th>
                        <td>
                            {!! Form::number('bapb_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('bapb_copy', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>SP3 / SPK</th>
                        <td>
                            {!! Form::number('sp3_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('sp3_copy', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BA Pemeriksaan / Opname</th>
                        <td>
                            {!! Form::number('pemeriksaan_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('pemeriksaan_copy', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Surat Jalan / SPHB</th>
                        <td>
                            {!! Form::number('surat_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('surat_copy', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BA Pembayaran</th>
                        <td>
                            {!! Form::number('pembayaran_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('pembayaran_copy', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Rekap Surat Jalan / SPHB</th>
                        <td>
                            {!! Form::number('rekap_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('rekap_copy', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>Lembar Kendali Pembayaran</th>
                        <td>
                            {!! Form::number('lembar_asli', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('lembar_copy', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer" style="text-align: right;">
        <a href="{{ route('sp3.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
        <input type="submit" class="btn btn-success" value="Simpan">
    </div>
</div>