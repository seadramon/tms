<html>
    <head>
        <style>
            body {
                font-size: 11px;
            }

            .tengah {
                text-align: center;
                font-weight: bold;
            }

            table.content {
                table-layout: auto; 
                width:100%;
                border-collapse: collapse;
            }

            .content table, .content th, .content td {
                border: 1px solid;
            }
        </style>
    </head>

    <body>
        <header>
            <div style="text-align:right;">
                Lampiran B.7<br>
                Form :WB-MNS-PS-04-F07  Rev : 00<br>
            </div>

            <div style="text-align:left;">
                <b>PT. WIJAYA KARYA BETON, Tbk</b><br>
                {{!empty($data->npp->pat)?$data->npp->pat->ket:""}}
            </div>

            <div style="text-align: center;font-size:14px;margin-bottom:10px;">
                <b><u>SURAT PERMINTAAN PENGIRIMAN (SPP)</u></b><br>
                NOMOR     :  {{ $data->no_sppb }}
            </div>

            <table style="margin-bottom:10px;">
                <tr>
                    <td colspan="2">Sesuai permintaan dari :</td>
                </tr>
                <tr>
                    <td>Nama Pelanggan</td>
                    <td>:&nbsp;{{ !empty($data->npp)?$data->npp->nama_pelanggan:"" }}</td>
                </tr>
                <tr>
                    <td>Nama Proyek</td>
                    <td>:&nbsp;{{ !empty($data->npp)?$data->npp->nama_proyek:"" }}</td>
                </tr>
                <tr>
                    <td>No. NPP</td>
                    <td>:&nbsp;{{ $data->no_npp }}</td>
                </tr>
                <tr>
                    <td>No. PO/SPK/Konfirmasi Pesanan/Surat Perjanjian</td>
                    <td>:&nbsp;</td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>:&nbsp;{{!empty($data->npp->pat)?$data->npp->pat->ket:""}}</td>
                </tr>
            </table>
        
                Mohon dapat dikirimkan barang-barang sebagai berikut :
        </header>

        <main>
            <table class="content">
                <thead style="text-align: center">
                    <tr>
                        <th>NO</th>
                        <th>NAMA/TIPE<br>BARANG</th>
                        <th>VOLUME<br>PESANAN</th>
                        <th>VOLUME<br>PENGAJUAN<br>PELAKSANA</th>
                        <th>VOLUME<br>APP<br>MKSDM</th>
                        <th>VOLUME<br>APP MPEO</th>
                        <th>VOLUME<br>APP MWP</th>
                        <th>VOLUME<br>SPPB<br>LALU</th>
                        <th>SAAT INI</th>
                        <th>S/D SAAT<br>INI</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($data->detail) > 0)
                    <?php $i = 1; ?>
                    @foreach($data->detail as $detail)
                        <?php
                        $kdProduk = $data->kd_produk;
                        $volLalu = isset($dataPesanan[$kdProduk])?$dataPesanan[$kdProduk]['sppVolBtg']:0;
                        $volMpeo = !empty($detail->app2_vol)?$detail->app2_vol:0;
                        $volMwp = !empty($detail->app3_vol)?$detail->app3_vol:0;
                        ?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $detail->produk->tipe . ' ('.$detail->kd_produk.')' }}</td>
                            <td>{{ isset($dataPesanan[$kdProduk])?$dataPesanan[$kdProduk]['pesananVolBtg']:0 }}</td>
                            <td>{{ $detail->vol }}</td>
                            <td>{{ !empty($detail->app1_vol)?$detail->app1_vol:'-' }}</td>
                            <td>{{ $volMpeo }}</td>
                            <td>{{ $volMwp }}</td>
                            <td>{{ $volLalu }}</td>
                            <td>
                                <?php
                                $volSaatIni = 0;
                                if ($volMwp > 0) {
                                    $volSaatIni = $volMpeo / $volMwp;
                                }
                                ?>
                                {{ $volSaatIni }}
                            </td>
                            <td>{{ $volLalu + $volSaatIni }}</td>
                            <td>{{ $detail->ket }}</td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11" style="text-align:center">
                            Data Kosong
                        </td>
                    </tr>
                @endif
                </tbody>
            </table> 
        </main>

        <table>
            <tr>
                <td>-</td>
                <td>Jadwal Pengiriman</td>
                <td>
                    :{{ !empty($data->jadwal1)?date('d M Y', strtotime($data->jadwal1)):'' }} s.d {{ !empty($data->jadwal2)?date('d M Y', strtotime($data->jadwal2)):'' }}
                </td>
            </tr>
            <tr>
                <td>-</td>
                <td>Progres Pembayaran</td>
                <td>:&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td>1.Uang Muka</td>
                <td>:
                    @if ($data->chk_tanpa_dp > 0)
                        {{ 'Tanpa Uang Muka' }}
                    @elseif($data->chk_kontrak > 0)
                        {{ 'Sudah dibayar' }}
                    @else
                        {{ 'Belum dibayar' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td>2.Progres Produksi</td>
                <td>: {{ ($data->chk_produksi > 0)?"Sudah dibayar":"Belum dibayar"}}</td>
            </tr>
            <tr>
                <td></td>
                <td>3.Progres Distribusi</td>
                <td>: {{ ($data->chk_distribusi > 0)?"Sudah dibayar":"Belum dibayar"}}</td>
            </tr>
            <tr>
                <td>-</td>
                <td>Catatan</td>
                <td>:{{ $data->catatan }}</td>
            </tr>
            <tr>
                <td>-</td>
                <td>Catatan KSDM</td>
                <td>:{{ $data->catatan_app1 }}</td>
            </tr>
            <tr>
                <td>-</td>
                <td>Catatan PEO</td>
                <td>:{{ $data->catatan_app2 }}</td>
            </tr>
        </table>
        
        <table width="100%">
            <tr>
                <td width="25%">&nbsp;</td>
                <td width="25%">Menyetujui</td>
                <td width="25%">&nbsp;</td>
                <td width="25%">
                    JAKARTA, 01/09/2022<br>
                    Pemohon,
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <br><br><br><br><br><br>
                </td>
            </tr>
            <tr>
                <td>
                    {{ !empty($data->app_empid)?$data->personal->full_name:'' }}<br>
                    {{ !empty($data->app_jbt)?$data->personal->jabatan->ket:'' }}
                </td>
                <td>
                    {{ !empty($data->app2_empid)?$data->personal2->full_name:'' }}<br>
                    {{ !empty($data->app2_jbt)?$data->personal2->jabatan->ket:'' }}
                </td>
                <td>
                    {{ !empty($data->app3_empid)?$data->personal3->full_name:'' }}<br>
                    {{ !empty($data->app3_jbt)?$data->personal3->jabatan->ket:'' }}
                </td>
                <td>
                    {{ $data->created_by }}<br>
                    PELAKSANA UTAMA
                </td>
            </tr>
        </table>
    </body>
</html>