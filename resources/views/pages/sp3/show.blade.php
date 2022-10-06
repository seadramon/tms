@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SP3</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12 mb-md-5 mb-xl-10">     
            {!! Form::model($data, ['route' => ['sp3.update', str_replace('/', '|', $data->no_sp3)], 'class' => 'form', 'method' => 'PUT']) !!}

            <div id="box1" style="margin-bottom: 20px">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">SP3 / SPK</h3>
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
            </div>

            <div id="box2">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pesanan NPP</h3>
                    </div>
                
                    <div class="card-body">
                        <table class="table table-row-bordered text-center">
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
                        </table>
                        <div class="hover-scroll-overlay-y h-400px">
                            <table id="tabel_detail_pesanan" class="table table-row-bordered text-center">
                                <tbody>
                                    @foreach($detailPesanan as $pesanan)
                                        @php
                                            $pesananVolBtg  = $pesanan->vol_konfirmasi ?? 0;
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                
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
                                        {!! Form::text('tgl_sp3', null, ['class'=>'form-control', 'id'=>'tgl_sp3', 'readonly']) !!}
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
                                {!! Form::select('no_ban', $ban, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_ban', 'readonly']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">No Induk Kontrak</label>
                                {!! Form::select('no_kontrak_induk', $kontrak, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_kontrak_induk', 'readonly']) !!}
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
                                <select class="form-control" name="pic[]" id="pic" multiple required>
                                    @foreach ($listPic as $pic)
                                        <option value="{{ $pic->employee_id }}" selected>{{ $pic->employee_id }} - {{ $pic->employee?->first_name }} {{ $pic->employee?->last_name }}</option>
                                    @endforeach
                                </select>
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
                                {!! Form::text('rit', 0, ['class'=>'form-control decimal', 'id' => $sat_harsat != 'volume' ? 'est-rit' : 'rit', 'required']) !!}
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
                            $readonly = $sat_harsat != 'volume';
                        @endphp
                        <br><br>
                        <h3>Detail Pekerjaan</h3>
                        <table class="table table-row-bordered text-center">
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
                                    <th style="width: 15%;">Harsat {{ $sat_harsat == 'volume' ? '[Btg/Ton]' : 'Rit' }}</th>
                                    <th style="width: 10%;">Jumlah</th>
                                    <th style="width: 3%;"></th>
                                </tr>
                            </thead>
                        </table>
                        <div class="hover-scroll-overlay-y">
                            <table id="tabel_detail_pekerjaan" class="table table-row-bordered text-center">
                                <tbody>
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
                                                {!! Form::text('tipe[]', $pekerjaan->produk?->tipe, ['class'=>'form-control', 'id'=>'tipe_' . $key, 'row-id'=>$key]) !!}
                                            </td>
                                            <td style="width: 12%;">
                                                {!! Form::text('jarak_pekerjaan[]', number_format($pekerjaan->jarak_km), ['class'=>'form-control jarak_pekerjaan decimal', 'id'=>'jarak_pekerjaan_' . $key, 'row-id'=>$key]) !!}
                                            </td>
                                            <td style="width: 13%;">
                                                {!! Form::text('vol_btg[]', number_format($vol_btg), ['class'=>'form-control vol_btg decimal', 'readonly' => $readonly, 'id'=>'vol_btg_' . $key, 'row-id'=>$key]) !!}
                                                <input type="hidden" id="vol_btg_max_{{ $key }}" value="9999">
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
                                                &nbsp;
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                    {!! Form::text('total', $total, ['class'=>'form-control decimal', 'id'=>'total', 'readonly']) !!}
                                </td>
                                <td style="width: 3%"></td>
                            </tr>
                        </table>
                
                        <br><br>
                
                        <h3>Material Tambahan</h3>
                        <div id="material_tambahan">
                            <div class="form-group">
                                <div data-repeater-list="material_tambahan">
                                    @if(blank($materialTambahan))
                                        <div data-repeater-item>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Material</label>
                                                    {!! Form::text('material', null, ['class'=>'form-control', 'required', 'readonly']) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Spesifikasi</label>
                                                    {!! Form::text('spesifikasi', null, ['class'=>'form-control', 'required', 'readonly']) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Volume</label>
                                                    {!! Form::number('volume', null, ['class'=>'form-control', 'required', 'readonly']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @foreach ($materialTambahan as $material)
                                            <div data-repeater-item>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Material</label>
                                                        {!! Form::text('material', $material->material, ['class'=>'form-control', 'required', 'readonly']) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Spesifikasi</label>
                                                        {!! Form::text('spesifikasi', $material->spesifikasi, ['class'=>'form-control', 'required', 'readonly']) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Volume</label>
                                                        {!! Form::number('volume', $material->volume, ['class'=>'form-control', 'required', 'readonly']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                
                        <br><br>
                
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="5" class="col-md-12" readonly>{{ $data->keterangan }}</textarea>
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
                    </div>
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<!--end::Content container-->
@endsection

@section('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
@endsection