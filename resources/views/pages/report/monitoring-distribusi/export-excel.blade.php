<table>
    <thead>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>PT WIJAYA KARYA BETON</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">PPB Bogor</th>
        </tr>
        <tr>
            <th rowspan="2" colspan="11" style="text-align: center; vertical-align: middle; font-weight: bold;">MONITORING DISTRIBUSI</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <td rowspan="2" colspan="11" style="text-align: center; vertical-align: middle;">Periode : {{ $minggu1 }} s/d {{ $minggu2 }}</td>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold; vertical-align: middle;" rowspan="2">TANGGAL</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold; vertical-align: middle;" rowspan="2">NO SPtB</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold;" colspan="2">SUB ANGKUTAN</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold; vertical-align: middle;" rowspan="2">NPP</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold; vertical-align: middle;" rowspan="2">NOMOR SPPrB</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold; vertical-align: middle;" rowspan="2">NAMA PELANGGAN</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold; vertical-align: middle;" rowspan="2">PROYEK</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold;" colspan="3">PRODUK</th>
        </tr>
        <tr>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold;">NAMA</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold;">NOPOL</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold;">KODE</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold;">TIPE</th>
            <th style="border: 1px solid #000000; text-align: center; font-weight: bold;">VOLUME</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $data)
            <tr>
                <td style="border: 1px solid #000000; width: 200%; text-align: center;">{{ date('d-m-Y', strtotime($data->tgl_sptb)) }}</td>
                <td style="border: 1px solid #000000; width: 300%;">{{ $data->no_sptb }}</td>
                <td style="border: 1px solid #000000; width: 200%;">{{ $data->angkutan }}</td>
                <td style="border: 1px solid #000000; width: 200%;">{{ $data->no_pol }}</td>
                <td style="border: 1px solid #000000; width: 200%;">{{ $data->no_npp }}</td>
                <td style="border: 1px solid #000000; width: 350%;">{{ $data->no_spprb }}</td>
                <td style="border: 1px solid #000000; width: 350%;">{{ $data->nama_pelanggan }}</td>
                <td style="border: 1px solid #000000; width: 650%;">{{ $data->nama_proyek }}</td>
                <td style="border: 1px solid #000000; width: 200%;">{{ $data->kd_produk }}</td>
                <td style="border: 1px solid #000000; width: 200%;">{{ $data->tipe }}</td>
                <td style="border: 1px solid #000000; width: 100%;">{{ $data->vol }}</td>
            </tr>
        @endforeach
    </tbody>
</table>