<html>
    <head>
        <style>
            body {
                font-size: 11px;
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
        <img alt="Logo" style="height:50px;width:25%;" src="{{public_path('assets/media/logos/wikabeton2.jpg')}}" class="logo" />
        <h4 class="no-line-height">PT. WIJAYA KARYA BETON, Tbk.</h4>
        <p>{{ $lokasi->ket }}</p><br>

        <h3 class="text-center">MONITORING DISTRIBUSI</h3>
        <p class="text-center">Periode {{ $minggu1.' s/d '.$minggu2 }}</p>
        <br>
        
        <table>
            <thead class="text-center">
                <tr>
                    <th rowspan="2">Tanggal</th>
                    <th colspan="2">NO SPtB</th>
                    <th rowspan="2">SUB ANGKUTAN</th>
                    <th rowspan="2">NPP</th>
                    <th rowspan="2">NOMOR SPPrB</th>
                    <th rowspan="2">NAMA PELANGGAN</th>
                    <th rowspan="2">PROYEK</th>
                    <th colspan="3">PRODUK</th>
                </tr>
                <tr>
                    <th>NAMA</th>
                    <th>NOPOL</th>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>VOLUME</th>
                </tr>
            </thead>
            <tbody>
                @if (count($datas) > 0)
                    @foreach($datas as $data)
                        <td>{{ date('d-m-Y', strtotime($data->tgl_sptb)) }}</td>
                        <td>{{ $data->no_sptb }}</td>
                        <td>{{ $data->angkutan }}</td>
                        <td>{{ $data->no_pol }}</td>
                        <td>{{ $data->no_npp }}</td>
                        <td>{{ $data->no_spprb }}</td>
                        <td>{{ $data->nama_pelanggan }}</td>
                        <td>{{ $data->nama_proyek }}</td>
                        <td>{{ $data->kd_produk }}</td>
                        <td>{{ $data->tipe }}</td>
                        <td>{{ $data->vol }}</td>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11" class="text-center">Data Kosong</td>
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