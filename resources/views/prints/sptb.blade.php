<html>
    <head>
        <style>
            body {
                font-size: 11px;
                font-family: arial;
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
            hr.new1 {
              border-top: 1px dotted black;
            }
        </style>
    </head>

    <body>
        <header>
            <div style="text-align:right;font-weight:bold;">
                Lampiran B.8<br>
                Form :WB-MNS-PS-04-F08  Rev : 00<br>
            </div>

            <table width="100%">
                <tr>
                    <td style="text-align:left;" width="75%" valign="top">
                        <b>PT. WIJAYA KARYA BETON, Tbk</b><br>
                        {{ !empty($ppb)?$ppb:""}}<br>
                        {{!empty($data->npp->pat)?$data->npp->pat->ket:""}}<br>                        
                    </td>
                    <td width="25%" style="font-size:10px">
                        <table>
                            <tr>
                                <td>Lembar : </td>
                                <td>1. Administrasi Produksi</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>2. Administrasi Distribusi</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>3. Pelaksana Utama</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>4. Keamanan Pabrik</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>5. QC Pabrik</td>
                            </tr><tr>
                                <td>&nbsp;</td>
                                <td>6. Perusahaan Angkutan</td>
                            </tr><tr>
                                <td>&nbsp;</td>
                                <td>7. Pelanggan</td>
                            </tr>
                        </table>
                    </td>
                </tr>    
            </table>

            <div style="text-align: center;font-size:14px;margin-bottom:10px;">
                <b><u>SURAT PENGANTAR BARANG (SPtB)</u></b><br>
                NOMOR     :  {{ $data->no_sptb }}
            </div>
        </header>

        <main>
            <table width="100%">
                <tr>
                    <td>
                        Pelanggan / <i>Customer</i>
                    </td>
                    <td>:</td>
                    <td>
                        {{ !empty($data->npp)?$data->npp->nama_pelanggan:"" }}
                    </td>

                    <td>
                        SPPrB No.
                    </td>
                    <td>:</td>
                    <td>{{ $data->no_spprb }}</td>
                </tr>
                <tr>
                    <td>
                        Proyek / <i>Project</i>
                    </td>
                    <td>:</td>
                    <td>{{ !empty($data->npp)?$data->npp->nama_proyek:"" }}</td>

                    <td>
                        NPP No.
                    </td>
                    <td>:</td>
                    <td>{{ $data->no_npp }}</td>
                </tr>
                <tr>
                    <td>
                        Lokasi<br><i>Location</i>
                    </td>
                    <td>:</td>
                    <td>{{ $data->tujuan }}</td>

                    <td>
                        Tgl / <i>Date</i>
                    </td>
                    <td>:</td>
                    <td>{{ date('d F Y', strtotime($data->tgl_berangkat)) }}</td>
                </tr>
                <tr>
                    <td>
                        Kontrak / <i>Contract No</i>
                    </td>
                    <td>:</td>
                    <td>{{ !empty($data->monOp)?$data->monOp->no_konfirmasi:"" }}</td>

                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>
                        Perusahaan Pemilik Angkutan<br><i>Name of carrier</i>
                    </td>
                    <td valign="top">:</td>
                    <td valign="top">{{ $data->angkutan }}</td>

                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        No. Polisi Kendaraan<br><i>Police Vehicle No.</i>
                    </td>
                    <td valign="top">:</td>
                    <td valign="top">{{ $data->no_pol }}</td>

                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
            </table>


            <table class="content" style="margin-top:5px;">
                <thead style="text-align: center">
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">Nama Produk<br><i>Tipe</i></th>
                        <th rowspan="2">Sat<br><i>Unit</i></th>
                        <th rowspan="2">Vol</th>
                        <th colspan="2">Produk/<br><i>Product *)</i></th>
                        <th colspan="2">Kondisi/<br><i>Condition *)</i></th>
                    </tr>
                    <tr>
                        <th>Tgl / <i>Date</i></th>
                        <th>Nomor / <i>Number</i></th>
                        <th>Baik / <br><i>Good</i></th>
                        <th>Cacat / <br><i>Defect</i></th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    <?php 
                        $i = 1; 
                        $totalVol = 0;
                        $totalGood = 0;
                        $totalDefect = 0;
                    ?>
                    @if (!empty($data->sptbd))
                        @foreach($data->sptbd as $detail)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $detail->produk->tipe }}</td>
                                <td>{{ $detail->produk->satuan }}</td>
                                <td>{{ $detail->vol }}</td>
                                <td>
                                    @foreach ($sptbd2[$detail->kd_produk] as $d2)
                                        {{ date('d-m-Y', strtotime($d2->tgl_produksi)) }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($sptbd2[$detail->kd_produk] as $d2)
                                        {{ $d2->stockid }}<br>
                                    @endforeach
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php 
                            $i++; 
                            $totalVol += $detail->vol;
                            ?>
                        @endforeach
                        <tr>
                            <td colspan="3">Total</td>
                            <td>{{ $totalVol }}</td>
                            <td>&nbsp;</td>
                            <td>Total</td>
                            <td>{{ $totalGood }}</td>
                            <td>{{ $totalDefect }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8">&nbsp;</td>
                        </tr>
                    @endif
                </tbody>
            </table> 

            <table width="100%" style="margin-top: 8px;">
                <tr>
                    <td>
                        <u>Pelanggan / <i>Customer</i></u><br>
                        Kondisi barang saat diterima di lapangan :<br>
                        Goods condition<br>
                        - Baik / <i>Good</i>&nbsp;&nbsp;&nbsp;:<br>
                        - Cacat / <i>Defect</i>&nbsp;&nbsp;&nbsp;:
                    </td>
                    <td>
                        Diperiksa oleh / <br>
                        <i>Check by</i><br><br><br><br>

                        &nbsp;
                        <hr class="new1">
                        QC Stock Yard
                    </td>
                    <td>
                        Dibuat oleh / <br>
                        <i>Made by</i> <br>
                        <img alt="Logo" src="data:image/png;base64, {{ $data->admProduksi->signature_base_64 }}" class="logo" height="50px;"/><br>
                        {{ !empty($data->admProduksi)?$data->admProduksi->full_name:"-" }}
                        <hr class="new1">
                        Adm Produksi
                    </td>
                </tr>
            </table>

            <table width="100%" style="margin-top: 8px;margin-bottom: 8px;">
                <tr>
                    <td>
                        <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(150)->generate($data->no_sptb)) }} ">
                    </td>
                    <td>
                        Diterima oleh / <i>Accept by</i><br>
                        Tgl / <i>Date</i>: {{ !empty($data->tgl_sampai)?date('d-m-Y', strtotime($data->tgl_sampai)):"" }}<br>

                        <img alt="Logo" src="{{ full_url_from_path($data->penerima_ttd ?? 'penerima_ttd.jpg') }}" class="logo" height="50px;"/><br>
                        {{ !empty($data->penerima_nama)?$data->penerima_nama:"-" }}
                        <hr class="new1">
                        Jabatan / <i>Position</i>
                    </td>
                    <td>
                        Angkutan / <br>
                        <i>Carrier</i><br><br><br><br>

                        {{ !empty($data->nama_driver)?$data->nama_driver:"-" }}
                        <hr class="new1">
                        Pengemudi / <i>Driver</i>
                    </td>
                    <td>
                        Dikirim oleh / <br>
                        <i>Deliver by</i><br>
                        @if (!empty($data->spmh->admDistribusi))
                            <img alt="Logo" src="data:image/png;base64, {{ $data->spmh->admDistribusi->signature_base_64 }}" class="logo" height="50px;"/><br>
                        @endif
                        {{ !empty($data->spmh->admDistribusi)?$data->spmh->admDistribusi->full_name:"-" }}
                        <hr class="new1">
                        Adm Distribusi
                    </td>
                </tr>
            </table>
            <div>
                Keterangan / Remark : <br>
                {{ $data->ket }}
            </div>
        </main>

        {{-- <footer>on each page</footer> --}}
    </body>
</html>