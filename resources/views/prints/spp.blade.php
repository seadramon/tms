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
            @page { margin: 250px 25px 60px 25px; }
            header { position: fixed; top: -240px; left: 0px; right: 0px; height: 50px; }
            /* footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; } */
    
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
                    <td>:&nbsp;{{$npp->no_konfirmasi}}</td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>:&nbsp;{{$npp->kec}}, {{$npp->kab}}</td>
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
                        <th>SATUAN</th>
                        <th>VOLUME<br>PESANAN</th>
                        <th>VOLUME<br>PENGAJUAN<br>PELAKSANA</th>
                        <th>VOLUME<br>APP<br>MKSDM</th>
                        <th>VOLUME<br>APP<br>MPEO</th>
                        <th>VOLUME<br>APP<br>MWP</th>
                        <th>VOLUME<br>SPPB<br>LALU</th>
                        <th>SAAT<br>INI</th>
                        <th>S/D SAAT<br>INI</th>
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
                            <td style="text-align: center;">{{ $i }}</td>
                            <td>{{ $detail->master_produk->nama_sub_sbu . '/' . $detail->produk->tipe }}</td>
                            <td style="text-align: center;">{{ strtoupper($detail->produk->satuan) }}</td>
                            <td style="text-align: center;">{{ isset($dataPesanan[$kdProduk])?$dataPesanan[$kdProduk]['pesananVolBtg']:0 }}</td>
                            <td style="text-align: center;">{{ $detail->vol }}</td>
                            <td style="text-align: center;">{{ !empty($detail->app1_vol)?$detail->app1_vol:'-' }}</td>
                            <td style="text-align: center;">{{ $volMpeo }}</td>
                            <td style="text-align: center;">{{ $volMwp }}</td>
                            <td style="text-align: center;">{{ $volLalu }}</td>
                            <td style="text-align: center;">
                                <?php
                                $volSaatIni = 0;
                                if ($volMwp > 0) {
                                    $volSaatIni = $volMpeo / $volMwp;
                                }
                                ?>
                                {{ round($volSaatIni,2) }}
                            </td>
                            <td style="text-align: center;">{{ round($volLalu + $volSaatIni,2) }}</td>
                            {{-- <td>{{ $detail->ket }}</td> --}}
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
                    <td>Catatan Pelaksana</td>
                    <td>:&nbsp;{{ $data->catatan }}</td>
                </tr>
                <tr>
                    <td>-</td>
                    <td>Catatan KSDM</td>
                    <td>:&nbsp;{{ $data->catatan_app1 }}</td>
                </tr>
                <tr>
                    <td>-</td>
                    <td>Catatan PEO</td>
                    <td>:&nbsp;{{ $data->catatan_app2 }}</td>
                </tr>
            </table>
            
            <table width="100%">
                <tr>
                    <td width="25%">&nbsp;</td>
                    <td width="25%"><br>Menyetujui</td>
                    <td width="25%">&nbsp;</td>
                    <td width="25%">
                        {{$npp->kota}}, {{ date('d/m/Y', strtotime($data->created_date))}}<br>
                        Pemohon,
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <br>
                    </td>
                </tr>
                <tr>
                    @php
                        $add = 0;
                    @endphp
                    @if (!empty($data->app3_empid))
                        <td>
                            <img alt="Logo" src="data:image/png;base64, {{ $data->personal3->signature_base_64 }}" class="logo" height="50px;"/><br>
                            {{ $data->personal3->full_name }}<br>
                            {{ $data->personal3->jabatan->ket }}
                        </td>
                    @else
                        @php
                            $add++;
                        @endphp
                    @endif
                    @if (!empty($data->app2_empid))
                        <td>
                            <img alt="Logo" src="data:image/png;base64, {{ $data->personal2->signature_base_64 }}" class="logo" height="50px;"/><br>
                            {{ $data->personal2->full_name }}<br>
                            {{ $data->personal2->jabatan->ket }}
                        </td>
                    @else
                        @php
                            $add++;
                        @endphp
                    @endif
                    @if (!empty($data->app_empid))
                        <td>
                            <img alt="Logo" src="data:image/png;base64, {{ $data->personal->signature_base_64 }}" class="logo" height="50px;"/><br>
                            {{ $data->personal->full_name }}<br>
                            {{ $data->personal->jabatan->ket }}
                        </td>
                    @else
                        @php
                            $add++;
                        @endphp
                    @endif
                    @for ($i = 0; $i < $add; $i++)
                        <td></td>
                    @endfor
                    <td>
                        <img alt="Logo" src="data:image/png;base64, {{ $data->createdby->signature_base_64 }}" class="logo" height="50px;"/><br>
                        {{ $data->createdby->full_name }}<br>
                        {{ $data->createdby->jabatan->ket }}
                    </td>
                </tr>
            </table>
        </main>

        {{-- <footer>on each page</footer> --}}
    </body>
</html>