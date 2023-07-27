<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Detail Pesanan NPP</h3>
    </div>

    <div class="card-body">
        {{-- <table class="table table-row-bordered text-center">
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
        </table> --}}
        <div class="hover-scroll-overlay-y h-400px">
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
                    @foreach($detailPesanan as $key => $pesanan)
                        @php
                            $pesananVolBtg  = $pesanan->vSpprbRi->vol_spprb ?? 0;
                            $pesananVolTon  = ((float)$pesananVolBtg * (float)($pesanan->produk?->vol_m3 ?? 0) * 2.5) ?? 0;
                            $sp3dVolBtg     = ($sp3D[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_akhir; }) : 0;
                            $sp3dVolTon     = ($sp3D[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_ton_akhir; }) : 0;
                            $sisaVolBtg     = $pesananVolBtg - $sp3dVolBtg;
                            $sisaVolTon     = $pesananVolTon - $sp3dVolTon;
                        @endphp
                        
                        <tr>
                            <td style="text-align: left">{{ $pesanan->produk->tipe }} {{$pesanan->kd_produk_konfirmasi}}</td>
                            
                            <td>{{ nominal($pesananVolBtg) }}</td>
                            <td>{{ nominal($pesananVolTon) }}</td>
                            <td>{{ nominal($sp3dVolBtg) }}</td>
                            <td>{{ nominal($sp3dVolTon) }}</td>
                            <td>{{ nominal($sisaVolBtg) }}</td>
                            <td>{{ nominal($sisaVolTon) }}</td>
                        </tr>

                        <input type="hidden" id="pesanan_vol_btg_max_{{ $key }}" row-id={{ $key }} value="{{ (float)$pesanan->vol_konfirmasi ?? 0 }}">
                        <input type="hidden" class="pesanan_kd_produk" row-id={{ $key }} value="{{ $pesanan->produk?->kd_produk }}">
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="separator separator-dashed border-primary my-10"></div>

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
                        {!! Form::text('tgl_sp3', $data->tgl_sp3 ? date('d-m-Y', strtotime($data->tgl_sp3)) : null, ['class'=>'form-control datepicker', 'id'=>'tgl_sp3']) !!}
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
                {!! Form::text('vendor', $trader->pimpinan_nama ?? "", ['class'=>'form-control', 'id'=>'vendor', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Jabatan</label>
                {!! Form::text('jabatan', $trader->pimpinan_jabatan ?? "", ['class'=>'form-control', 'id'=>'jabatan', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">PIC</label>
                <select class="form-control search-pic" name="pic[]" id="pic" multiple required>
                    @foreach ($listPic as $pic)
                        <option value="{{ $pic->employee_id }}" selected>{{ $pic->employee_id }} - {{ $pic->employee?->first_name }} {{ $pic->employee?->last_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- <div class="form-group col-lg-6">
                <label class="form-label">Spesifikasi</label>
                {!! Form::select('kd_material', $kd_material, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kd_material']) !!}
            </div> --}}
            <div class="form-group col-lg-6">
                <label class="form-label">&nbsp;</label>
            </div>

            <div class="form-group col-lg-3">
                <label class="form-label">Tanggal Penyerahan</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('jadwal1', $data->jadwal1 ? date('d-m-Y', strtotime($data->jadwal1)) : null, ['class'=>'form-control datepicker', 'id'=>'jadwal1']) !!}
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
                        {!! Form::text('jadwal2', $data->jadwal2 ? date('d-m-Y', strtotime($data->jadwal2)) : null, ['class'=>'form-control datepicker', 'id'=>'jadwal2']) !!}
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
                <label class="form-label">Estimasi Total Ritase</label>
                {!! Form::text('rit', 0, ['class'=>'form-control decimal', 'id' => $sat_harsat != 'tonase' ? 'est-rit' : 'rit', 'required']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Jarak</label>
                {!! Form::number('jarak_pesanan', $data->jarak_km, ['class'=>'form-control', 'id'=>'jarak_pesanan', 'required']) !!}
            </div>
            @if ($sat_harsat == 'ritase')
            <div class="form-group col-lg-6">
                <label class="form-label">Harga Satuan Ritase</label>
                {!! Form::text('harga_satuan_ritase', 0, ['class'=>'form-control decimal', 'id'=>'harga_satuan_ritase']) !!}
            </div>
            @endif
        </div>
        @php
            $readonly = $sat_harsat != 'tonase';
        @endphp
        @if ($isAmandemen)
            <div class="separator separator-dashed border-primary my-10"></div>
            <h3>Volume Distribusi</h3>
            <div class="hover-scroll-overlay-y h-400px">
                <table id="tabel_detail_pesanan" class="table table-row-bordered text-center">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle; text-align: left">Nama / Tipe Produk</th>
                            <th>Volume SP3</th>
                            <th>Volume Distribusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sp3d_ = $detailPekerjaan->groupBy('kd_produk')
                        @endphp
                        @foreach($detailPesanan as $pesanan)
                            @php
                                $volsp3_    = ($sp3d_[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3d_[$pesanan->kd_produk_konfirmasi]->sum('vol_akhir') : 0;
                                $distribusi = ($sptbd[$pesanan->kd_produk_konfirmasi] ?? null) ? $sptbd[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->vol; }) : 0;
                            @endphp
                            
                            <tr>
                                <td style="text-align: left">{{ $pesanan->produk->tipe }} / {{$pesanan->kd_produk_konfirmasi}}</td>
                                
                                <td>{{ nominal($volsp3_) }}</td>
                                <td>{{ nominal($distribusi) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <div class="separator separator-dashed border-primary my-10"></div>
        <h3>Detail Pekerjaan</h3>
        {{-- <table class="table table-row-bordered text-center">
            <thead>
                <tr>
                    <th style="width: 10%;">Unit</th>
                    <th style="width: 14%;">Tipe</th>
                    <th style="width: 12%;">Jarak (KM)</th>
                    <th style="width: 13%;">Vol (Btg)</th>
                    <th style="width: 13%;">Vol (Ton)</th>
                    @if ($sat_harsat != 'ritase')
                        <th style="width: 10%;">Satuan</th>
                    @endif
                    <th style="width: 15%;">Harsat {{ $sat_harsat == 'tonase' ? '[Btg/Ton]' : 'Rit' }}</th>
                    <th style="width: 10%;">Jumlah</th>
                    <th style="width: 3%;"></th>
                </tr>
            </thead>
        </table> --}}
        <div class="hover-scroll-overlay-y h-400px">
            <table id="tabel_detail_pekerjaan" class="table table-row-bordered text-center">
                <thead>
                    <tr>
                        <th style="width: 10%;">Unit</th>
                        <th style="width: 14%;">Tipe</th>
                        <th style="width: 12%;">Jarak (KM)</th>
                        <th style="width: 13%;">Vol (Btg)</th>
                        <th style="width: 13%;">Vol (Ton)</th>
                        @if ($sat_harsat != 'ritase')
                            <th style="width: 10%;">Satuan</th>
                        @endif
                        <th style="width: 15%;">Harsat {{ $sat_harsat == 'tonase' ? '[Btg/Ton]' : 'Rit' }}</th>
                        <th style="width: 10%;">Jumlah</th>
                        <th style="width: 3%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $produk = $detailPesanan->mapWithKeys(function($item){
                            return [$item->produk->kd_produk => $item->produk->tipe];
                        })->all();
                    @endphp

                    @include('pages.sp3.row-to-clone')

                    @php
                        $subtotal = 0;
                    @endphp

                    @foreach($detailPekerjaan as $key => $pekerjaan)
                        @php
                            $vol_btg = $pekerjaan->vol_awal;
                            $vol_ton = $pekerjaan->vol_ton_awal;
                            $harsat = $pekerjaan->harsat_awal;

                            if($pekerjaan->sat_harsat == 'btg'){
                                $jumlah = $harsat * $vol_btg;
                            }else{
                                $jumlah = $harsat * $vol_ton;
                            }

                            $subtotal += $jumlah;
                        @endphp

                        <tr class="detail_pekerjaan" id="detail_pekerjaan_{{ $key }}" row-id={{ $key }}>
                            <td style="width: 10%;">
                                {!! Form::select('unit[]', $unit, $pekerjaan->pat_to, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'unit_' . $key, 'row-id'=>$key]) !!}
                            </td>
                            <td style="width: 14%;">
                                {!! Form::hidden('kd_produk[]', $pekerjaan->kd_produk, []) !!}
                                {!! Form::select('tipe[]', $produk, null, ['class'=>'form-control form-select-solid tipe', 'data-control'=>'select2', 'id'=>'tipe_' . $key, 'row-id'=>$key]) !!}
                            </td>
                            <td style="width: 12%;">
                                {!! Form::text('jarak_pekerjaan[]', number_format($pekerjaan->jarak_km), ['class'=>'form-control jarak_pekerjaan decimal', 'id'=>'jarak_pekerjaan_' . $key, 'row-id'=>$key]) !!}
                            </td>
                            <td style="width: 13%;">
                                {!! Form::text('vol_btg[]', number_format($vol_btg), ['class'=>'form-control vol_btg decimal', 'readonly' => $readonly, 'id'=>'vol_btg_' . $key, 'row-id'=>$key]) !!}
                                <input type="hidden" id="vol_btg_max_{{ $key }}" row-id={{ $key }} value="0">
                            </td>
                            <td style="width: 13%;">
                                {!! Form::text('vol_ton[]', number_format($vol_ton), ['class'=>'form-control vol_ton decimal', 'readonly' => $readonly, 'id'=>'vol_ton_' . $key, 'row-id'=>$key]) !!}
                            </td>
                            @if ($sat_harsat != 'ritase')
                                <td style="width: 10%;">
                                    {!! Form::select('satuan[]', $satuan, $pekerjaan->sat_harsat, ['class'=>'form-control form-select-solid satuan', 'data-control'=>'select2', 'id'=>'satuan_' . $key, 'row-id'=>$key]) !!}
                                </td>
                            @endif
                            <td style="width: 15%;">
                                {!! Form::text('harsat[]', number_format($harsat), ['class'=>'form-control harsat decimal', 'id'=>'harsat_' . $key, 'row-id'=>$key]) !!}
                            </td>
                            <td style="width: 10%;">
                                {!! Form::text('jumlah[]', number_format($jumlah), ['class'=>'form-control jumlah decimal', 'id'=>'jumlah_' . $key, 'row-id'=>$key, 'readonly']) !!}
                            </td>
                            <td style="vertical-align: middle; padding-left: 0px; width: 3%;">
                                <button class="btn btn-danger btn-sm delete_pekerjaan" id="delete_pekerjaan_{{ $key }}" row-id={{ $key }} style="padding: 5px 6px;">
                                    <span class="bi bi-trash"></span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-group" style="margin-top: 20px">
            <button type="button" class="btn btn-light-primary" id="add-detail">
                <i class="la la-plus"></i>Tambah
            </button>
        </div>

        <table class="table table-row-bordered text-center">
            @php
                $total = $subtotal + ($subtotal * $data->ppn) + ($subtotal * $data->pph/100);
                $formatPph = $data->pph_id . '|' . $data->pph;
            @endphp

            <tr>
                <th style="text-align: right; width: 72%">Subtotal</th>
                <td style="width: 25%">
                    {!! Form::text('subtotal', number_format($subtotal), ['class'=>'form-control decimal', 'id'=>'subtotal', 'readonly']) !!}
                </td>
                <td style="width: 3%"></td>
            </tr>
            <tr>
                <th style="text-align: right; width: 72%">PPN</th>
                <td style="width: 25%">
                    {!! Form::select('ppn', $ppn, $data->ppn*100, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'ppn']) !!}
                </td>
                <td style="width: 3%"></td>
            </tr>
            <tr>
                <th style="text-align: right; width: 72%">PPH</th>
                <td style="width: 25%">
                    {!! Form::select('pph', $pph, $formatPph, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'pph']) !!}
                </td>
                <td style="width: 3%"></td>
            </tr>
            <tr>
                <th style="text-align: right; width: 72%">Total</th>
                <td style="width: 25%">
                    {!! Form::text('total', number_format($total), ['class'=>'form-control decimal', 'id'=>'total', 'readonly']) !!}
                </td>
                <td style="width: 3%"></td>
            </tr>
        </table>

        <div class="separator separator-dashed border-primary my-10"></div>

        <h3>Material Tambahan</h3>
        <div id="material_tambahan">
            <div class="form-group">
                <div data-repeater-list="material_tambahan">
                    @if(blank($materialTambahan))
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
                    @else
                        @foreach ($materialTambahan as $material)
                            <div data-repeater-item>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label class="form-label">Material</label>
                                        {!! Form::text('material', $material->material, ['class'=>'form-control', 'required']) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Spesifikasi</label>
                                        {!! Form::text('spesifikasi', $material->spesifikasi, ['class'=>'form-control', 'required']) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Volume</label>
                                        {!! Form::number('volume', $material->volume, ['class'=>'form-control', 'required']) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:;" data-repeater-delete class="btn btn-md btn-light-danger mt-md-8">
                                            <i class="la la-trash-o"></i>Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        
            <div class="form-group" style="margin-top: 20px">
                <div class="col-md-3">
                    <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                        <i class="la la-plus"></i>Tambah
                    </a>
                </div>
            </div>
        </div>

        <div class="separator separator-dashed border-primary my-10"></div>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="5" class="col-md-12">{{ $data->keterangan }}</textarea>
        </div>

        <div class="separator separator-dashed border-primary my-10"></div>

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
                            {!! Form::number('dokumen_asli[]', $data->dokumen[0]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[0]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>Packing List</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[1]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[1]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Faktur Pajak</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[2]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[2]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BAPB</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[3]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[3]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>SP3 / SPK</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[4]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[4]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BA Pemeriksaan / Opname</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[5]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[5]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Surat Jalan / SPtB</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[6]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[6]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BA Pembayaran</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[7]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[7]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Rekap Surat Jalan / SPtB</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[8]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[8]->copy ?? null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>Lembar Kendali Pembayaran</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', $data->dokumen[9]->asli ?? null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', $data->dokumen[9]->copy ?? null, ['class'=>'form-control']) !!}
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