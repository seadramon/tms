<html>
    <head>
        <style>
            @page { margin: 20px; }
            body {
                font-size: 11px;
                margin: 0px;
            }

            .logo {
                width: 30%;
            }

            table {
                table-layout: auto; 
                width:100%;
                border-collapse: collapse;
            }

            table tr td {
                padding: 5px 5px;
            }

            tr, th, td {
                border: 1px solid;
            }

            .table-title {
                width: 40%;
                margin: auto;
            }

            .table-title tr, .table-title tr td {
                border: none;
                padding: 0px 5px !important;
            }

            .no-border {
                border: none;
            }

            .row-total {
                border-left: none;
                border-bottom: none;
                border-right: none;
            }

            .text-center {
                text-align: center;
            }

            .text-right {
                text-align: right;
            }

            .text-margin {
                margin: 10px 0px;
            }

            p {
                line-height: 5px;
            }

            .no-line-height {
                line-height: 0px;
            }

            .table-sign {
                width: 80%;
                margin: auto;
            }

            .table-sign tr, .table-sign tr td {
                border: none;
            }

            .header-sign {
                width: 50%;
                padding-bottom: 50px;
            }
        </style>
    </head>

    <body>
        <img alt="Logo" style="height: 30px;width: auto;" src="{{public_path('assets/media/logos/wikabeton2.jpg')}}" class="logo" />
        <h4 class="no-line-height">PT. WIJAYA KARYA BETON, Tbk.</h4>
        <p>{{ $lokasi->ket }}</p><br>

        <h3 class="text-center" style="margin-top: -10px;">MONITORING DISTRIBUSI</h3>
        <p class="text-center">Periode {{ $minggu1.' s/d '.$minggu2 }}</p>
        <br>
        
        <table>
            <thead class="text-center">
                <tr>
                    <th style="width: 6%;" rowspan="2">Tanggal</th>
                    <th style="width: 10%;" rowspan="2">NO SPtB</th>
                    <th style="width: 14%;" colspan="2">SUB ANGKUTAN</th>
                    <th style="width: 7%;" rowspan="2">NPP</th>
                    <th style="width: 12%;" rowspan="2">NOMOR SPPrB</th>
                    <th style="width: 12%;" rowspan="2">NAMA PELANGGAN</th>
                    <th style="width: 12%;" rowspan="2">PROYEK</th>
                    <th style="width: 19%;" colspan="3">PRODUK</th>
                    <th style="width: 8%;" rowspan="2">STATUS</th>
                </tr>
                <tr>
                    <th style="width: 7%">NAMA</th>
                    <th style="width: 7%">NOPOL</th>
                    <th style="width: 7%">KODE</th>
                    <th style="width: 7%">TIPE</th>
                    <th style="width: 5%">VOL</th>
                </tr>
            </thead>
            <tbody>
                @if (count($datas) > 0)
                    @foreach($datas as $data)
                        <tr style="font-size: 11px;">
                            <td style="text-align: left;">{{ date('d-m-Y', strtotime($data->tgl_sptb)) }}</td>
                            <td style="text-align: center;">{{ $data->no_sptb }}</td>
                            <td style="text-align: left;">{{ $data->angkutan }}</td>
                            <td style="text-align: center;">{{ $data->no_pol }}</td>
                            <td style="text-align: center;">{{ $data->no_npp }}</td>
                            <td style="text-align: left;">{{ $data->no_spprb }}</td>
                            <td>{{ $data->nama_pelanggan }}</td>
                            <td>{{ $data->nama_proyek }}</td>
                            <td>{{ $data->kd_produk }}</td>
                            <td>{{ $data->tipe }}</td>
                            <td style="text-align: center;">{{ $data->vol }}</td>
                            <td style="text-align: center;">{{ $data->app_pelanggan == '1' ? 'Received' : 'On Progress' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="12" class="text-center">Data Kosong</td>
                    </tr>
                @endif
                <!-- <tr class="row-total">
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                    <td>Total</td>
                    <td class="text-right">{{ '$total' }}</td>
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                </tr> -->
            </tbody>
        </table> 
    </body>
</html>