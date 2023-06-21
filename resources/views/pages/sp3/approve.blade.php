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
            {!! Form::open(['url' => route('sp3.store-approve'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

            <div id="box1" style="margin-bottom: 20px">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Approve SP3 / SPK</h3>
                    </div>
                
                    <div class="card-body">
                        {!! Form::hidden('no_sp3', $data->no_sp3) !!}
                        {!! Form::hidden('type', $type) !!}

                        <div class="form-group row">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 custom-label">NPP</label>
                                {!! Form::text('no_npp', $data->npp?->no_npp . ' | ' . $data->npp?->nama_proyek, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                            
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 custom-label">No. SP3 / SPK</label>
                                {!! Form::text('no_sp3_disabled', $data->no_sp3, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                        </div>
                
                        <div class="form-group row">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 custom-label">Vendor</label>
                                {!! Form::text('vendor', $data->vendor?->nama, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 custom-label">Pekerjaan</label>
                                {!! Form::text('kd_pekerjaan', $data->jenisPekerjaan?->ket, ['class'=>'form-control', 'disabled']) !!}
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
                        <div class="hover-scroll-overlay-y h-400px px-5">
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
                                <tbody class="">
                                    @foreach($detailPesanan as $pesanan)
                                        @php
                                            $pesananVolBtg  = $pesanan->vSpprbRi->vol_spprb ?? 0;
                                            $pesananVolTon  = ((float)$pesananVolBtg * (float)($pesanan->produk?->vol_m3 ?? 0) * 2.5) ?? 0;
                                            $sp3dVolBtg     = in_array($pesanan->kd_produk_konfirmasi, $sp3D->toArray()) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_akhir; }) : 0;
                                            $sp3dVolTon     = in_array($pesanan->kd_produk_konfirmasi, $sp3D->toArray()) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_ton_akhir; }) : 0;
                                            $sisaVolBtg     = $pesananVolBtg - $sp3dVolBtg;
                                            $sisaVolTon     = $pesananVolTon - $sp3dVolTon;
                                        @endphp
                                        
                                        <tr>
                                            <td class="text-left">{{ $pesanan->produk->tipe }} {{$pesanan->kd_produk_konfirmasi}}</td>
                                            <td class="text-left">{{ nominal($pesananVolBtg) }}</td>
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
                
                        <div class="separator separator-dashed border-primary my-10"></div>
                
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label class="form-label">Proyek</label>
                                {!! Form::text('proyek', $data->npp?->nama_proyek, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">Pelanggan</label>
                                {!! Form::text('pelanggan', $data->npp?->nama_pelanggan, ['class'=>'form-control', 'disabled']) !!}
                
                            </div>
                            
                            <div class="form-group col-lg-3">
                                <label class="form-label">Tujuan</label>
                                {!! Form::text('region', $data->npp?->infoPasar?->region?->kabupaten_name . ', ' . $data->npp?->infoPasar?->region?->kecamatan_name, ['class'=>'form-control', 'disabled']) !!}
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="form-label">Tanggal</label>
                                {!! Form::text('tgl_sp3', $data->tgl_sp3 ? date('d-m-Y', strtotime($data->tgl_sp3)) : null, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">No BAN</label>
                                {!! Form::text('no_ban', $data->no_ban, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">No Induk Kontrak</label>
                                {!! Form::text('no_kontrak_induk', $data->no_kontrak_induk, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">Nama Pihak Kedua / Vendor</label>
                                {!! Form::text('vendor', $data->vendor?->nama, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">Jabatan</label>
                                {!! Form::text('jabatan', 'TBC', ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">PIC</label>
                                {!! Form::text('pic', implode(',', $listPic), ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">Spesifikasi</label>
                                {!! Form::text('spesifikasi', 'TBC', ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-3">
                                <label class="form-label">Tanggal Penyerahan</label>
                                {!! Form::text('jadwal1', $data->jadwal1 ? date('d-m-Y', strtotime($data->jadwal1)) : null, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-3">
                                <label class="form-label">&nbsp;</label>
                                {!! Form::text('jadwal2', $data->jadwal2 ? date('d-m-Y', strtotime($data->jadwal2)) : null, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">Kondisi Penyerahan</label>
                                {!! Form::text('kondisi_penyerahan', $kondisiPenyerahanDipilih, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">Estimasi Total Ritase</label>
                                {!! Form::text('rit', $data->rit, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                
                            <div class="form-group col-lg-6">
                                <label class="form-label">Jarak</label>
                                {!! Form::text('jarak_pesanan', $data->jarak_km, ['class'=>'form-control', 'disabled']) !!}
                            </div>
                        </div>
                        
                        <div class="separator separator-dashed border-primary my-10"></div>
                
                        <div class="hover-scroll-overlay-y h-400px px-5">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data->sp3D as $sp3D)
                                        @php 
                                            $jumlah = $sp3D->sat_harsat == 'btg' ? ($sp3D->harsat_awal * $sp3D->vol_awal) : ($sp3D->harsat_awal * $sp3D->vol_ton_awal);
                                            $subtotal = ($subtotal ?? 0) + $jumlah;
                                        @endphp

                                        <tr class="detail_pekerjaan">
                                            <td style="width: 7%;">
                                                {!! Form::text('unit[]', $sp3D->pat_to, ['class'=>'form-control', 'disabled']) !!}
                                            </td>
                                            <td style="width: 12%;">
                                                {!! Form::text('kd_produk[]', $sp3D->kd_produk, ['class'=>'form-control', 'disabled']) !!}
                                            </td>
                                            <td style="width: 7%;">
                                                {!! Form::text('jarak[]', $sp3D->jarak_km, ['class'=>'form-control', 'disabled']) !!}
                                            </td>
                                            <td style="width: 10%;">
                                                {!! Form::text('vol_btg[]', $sp3D->vol_awal, ['class'=>'form-control decimal', 'disabled']) !!}
                                            </td>
                                            <td style="width: 10%;">
                                                {!! Form::text('vol_ton[]', $sp3D->vol_ton_awal, ['class'=>'form-control decimal', 'disabled']) !!}
                                            </td>
                                            <td style="width: 7%;">
                                                {!! Form::text('satuan[]', $sp3D->sat_harsat, ['class'=>'form-control', 'disabled']) !!}
                                            </td>
                                            <td style="width: 12%;">
                                                {!! Form::text('harsat[]', $sp3D->harsat_awal, ['class'=>'form-control decimal', 'disabled']) !!}
                                            </td>
                                            <td style="width: 12%;">
                                                {!! Form::text('jumlah[]', $jumlah, ['class'=>'form-control decimal', 'disabled']) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-5">
                            <table id="tabel_detail_pekerjaan" class="table table-row-bordered text-center">
                                @php
                                    $total = $subtotal + ($subtotal * $data->ppn) + ($subtotal * $data->pph/100);
                                    $formatPph = $data->pph_id . '|' . $data->pph;
                                @endphp

                                <tr>
                                    <td colspan="7" style="text-align: right; width: 70%">Subtotal</td>
                                    <td style="width: 30%;">
                                        {!! Form::text('subtotal', $subtotal, ['class'=>'form-control decimal', 'disabled']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" style="text-align: right; width: 70%">PPN</td>
                                    <td style="width: 30%;">
                                        {!! Form::select('ppn', $ppn, $data->ppn*100, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'ppn', 'disabled']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="7" style="text-align: right; width: 70%">PPH</th>
                                    <td style="width: 30%;">
                                        {!! Form::select('pph', $pph, $formatPph, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'pph', 'disabled']) !!}
                                    </td>
                                    <td style="width: 3%"></td>
                                </tr>
                                <tr>
                                    <td colspan="7" style="text-align: right; width: 70%">Total</td>
                                    <td style="width: 30%;">
                                        {!! Form::text('total', $total, ['class'=>'form-control decimal text-right', 'disabled']) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                
                        <div class="separator separator-dashed border-primary my-10"></div>
                
                        <div id="material_tambahan">
                            <div class="form-group">
                                @foreach($data->sp3D2 as $sp3D2)
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label class="form-label">Material</label>
                                            {!! Form::text('material', $sp3D2->material, ['class'=>'form-control', 'disabled']) !!}
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Spesifikasi</label>
                                            {!! Form::text('spesifikasi', $sp3D2->spesifikasi, ['class'=>'form-control', 'disabled']) !!}
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Volume</label>
                                            {!! Form::text('volume', $sp3D2->volume, ['class'=>'form-control', 'disabled']) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                
                        <div class="separator separator-dashed border-primary my-10"></div>
                
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea id="keterangan" rows="5" class="col-md-12" disabled>{{ $data->keterangan }}</textarea>
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
                                            {!! Form::text('invoice_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('invoice_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        
                                        <th>&nbsp;</th>
                
                                        <th>Packing List</th>
                                        <td>
                                            {!! Form::text('packing_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('packing_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                    </tr>
                
                                    <tr>
                                        <th>Faktur Pajak</th>
                                        <td>
                                            {!! Form::text('pajak_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('pajak_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        
                                        <th>&nbsp;</th>
                
                                        <th>BAPB</th>
                                        <td>
                                            {!! Form::text('bapb_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('bapb_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                    </tr>
                
                                    <tr>
                                        <th>SP3 / SPK</th>
                                        <td>
                                            {!! Form::text('sp3_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('sp3_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        
                                        <th>&nbsp;</th>
                
                                        <th>BA Pemeriksaan / Opname</th>
                                        <td>
                                            {!! Form::text('pemeriksaan_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('pemeriksaan_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th>Surat Jalan / SPtB</th>
                                        <td>
                                            {!! Form::text('surat_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('surat_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        
                                        <th>&nbsp;</th>
                
                                        <th>BA Pembayaran</th>
                                        <td>
                                            {!! Form::text('pembayaran_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('pembayaran_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                    </tr>
                
                                    <tr>
                                        <th>Rekap Surat Jalan / SPtB</th>
                                        <td>
                                            {!! Form::text('rekap_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('rekap_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        
                                        <th>&nbsp;</th>
                
                                        <th>Lembar Kendali Pembayaran</th>
                                        <td>
                                            {!! Form::text('lembar_asli', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('lembar_copy', null, ['class'=>'form-control', 'disabled']) !!}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                    <div class="card-footer" style="text-align: right;">
                        <a href="{{ route('sp3.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                        <input type="submit" class="btn btn-success" value="Approve">
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
    
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $(".decimal").trigger('keyup');
    })
</script>
@endsection