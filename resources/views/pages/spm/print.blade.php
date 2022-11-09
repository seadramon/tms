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
        <img alt="Logo" src="data:image/png;base64, {{ $logo }}" class="logo" />
        <h4 class="no-line-height">PT. WIJAYA KARYA BETON, Tbk.</h4>
        <p>{{ $spmh->pat?->ket }}</p>

        <h3 class="text-center">SURAT PERMINTAAN MUAT (PRODUK BETON)</h3>

        <table class="table-title">
            <tbody>
                <tr>
                    <td>NOMOR SPM</td>
                    <td class="text-center">:</td>
                    <td>{{ $spmh->no_spm }}</td>
                </tr>
                <tr>
                    <td>NOMOR SPPrB</td>
                    <td class="text-center">:</td>
                    <td>{{ $spmh->sppbh?->no_spprb }}</td>
                </tr>
                <tr>
                    <td>NOMOR SPP</td>
                    <td class="text-center">:</td>
                    <td>{{ $spmh->no_sppb }}</td>
                </tr>
            </tbody>
        </table>

        <div class="text-margin">
            <p>Kepada {{ $spmh->pat?->ket }}</p>
            <p>Harap dimuat pada kendaraan : {{ $spmh->vendor->nama }} dengan No. Polisi : {{ strtoupper($spmh->no_pol) }}</p>
        </div>

        <table>
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Tipe Produk</th>
                    <th>Jumlah</th>
                    <th>SP3</th>
                    <th>Tujuan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_spmd = $spmh->spmd->count();
                @endphp
                @foreach($spmh->spmd as $key => $spmd)
                    @php
                        $total = ($total ?? 0) + ($spmd->vol ?? 0);
                    @endphp

                    <tr>
                        <td style="width: 5%;" class="text-center">{{ $key+1 }}</td>
                        @if ($key == 0)
                            <td style="width: 20%;" rowspan="{{$total_spmd}}">{{ $sbu->ket ?? "Unknown" }}</td>
                            
                        @endif
                        <td style="width: 20%;" class="text-center">{{ $spmd->produk?->tipe }}</td>
                        <td style="width: 10%;" class="text-right">{{ $spmd->vol }}</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 30%;">
                            {{ $spmh->sppbh?->npp?->nama_pelanggan }};<br>
                            {{ $spmh->sppbh?->npp?->nama_proyek }};<br>
                            {{ $spmh->sppbh?->tujuan }}
                        </td>
                        <td style="width: 20%;" class="text-center">{{ $spmh->no_sppb }}</td>
                    </tr>
                @endforeach
            
                <tr class="row-total">
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                    <td>Total</td>
                    <td class="text-right">{{ $total }}</td>
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                    <td class="no-border"></td>
                </tr>
            </tbody>
        </table> 

        <table class="table-sign text-center">
            <tbody>
                <tr>
                    <td class="header-sign"><br>Pengemudi,</td>
                    <td class="header-sign">{{ $npp->kota }}, {{ date('d/m/Y') }}<br>Administrasi Distribusi,</td>
                </tr>
                <tr>
                    <td>{{ $spmh->app2_name }}</td>
                    <td>
                        {{ $spmh->approval->full_name }}
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>