<style type="text/css">
    .tableContent {
        font-size: 12px;
        border-right:1px solid #000000;
        border-top: 1px solid #000000;
        padding-left: 5px;
        padding-right: 5px;
    }

    .subtitle {
        font-size: 13px;
        font-weight: bold;
    }

    .txt12 {
        font-size: 12px;
    }.txt13 {
        font-size: 13px;
    }

    .bdrLeft {
        border-left:1px solid #000000;
    }

    .bdrBtm {
        border-bottom:1px solid #000000;
    }

    .tengahBold {
        text-align: center;
        font-weight: bold;
    }
    .tengah {
        text-align: center;
    }

    .tebal {
        font-weight: bold;
    }

    .right {
        text-align: right;
    }

    .fontFams {
        font-family: "Times New Roman", Times, serif;
    }
</style>
<table cellspacing="0" cellpadding="0" class="fontFams">
    <tr>
        <td colspan="12" style="text-align: right;font-size: 11px;">Lampiran B.9</td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: right">
            &nbsp;
        </td>
        <td style="text-align: left;border: 1px solid #000000;padding-left: 5px;padding-right: 10px;font-size: 11px;">
            Form : WB-SCM-PS-02-
        </td>
        <td style="text-align: right;border: 1px solid #000000;padding-right: 5px;font-size: 11px;">
            Rev
        </td>
    </tr>
    <tr>
        <td colspan="12" style="text-align:center;vertical-align: middle; font-weight: bold;font-size: 22px;text-decoration: underline;">SURAT PERINTAH PELAKSANAAN PEKERJAAN (SP3)</td>
    </tr>
    <tr>
        <td colspan="12" style="text-align: center; vertical-align: middle;font-size: 14px;font-weight: bold;">Nomor : {{ $data->no_sp3 }}</td>
    </tr>
    <tr>
        <td colspan="12" style="text-align: center; vertical-align: middle;font-size: 14px;font-weight: bold;">Tanggal : {{ date('d.m.Y', strtotime($data->tgl_sp3)) }}</td>
    </tr>
    <tr>
        <td colspan="12" >&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td colspan="11" style="font-size: 12px;">
                Pekerjaan  Angkutan Produk Beton
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 12px;">
            Pesanan
        </td>
        <td style="text-align: center;">:</td>
        <td colspan="8">{{ !empty($data->npp)?strtoupper($data->npp->nama_pelanggan):'' }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 12px;">
            Lokasi
        </td>
        <td style="text-align: center;">:</td>
        <td colspan="8">{{ !empty($data->npp)?strtoupper($data->npp->nama_proyek):'' }}</td>
    </tr>
    
    <tr>
        <td colspan="12">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="12">&nbsp;</td>
    </tr>

    <tr>
        <td style="font-weight: bold;font-size: 13px;">1.&nbsp;</td>
        <td style="font-weight: bold;font-size: 13px;">Berdasarkan</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">1.1.</td>
        <td style="font-size: 13px;">Nomor Kontrak</td>
        <td style="font-size: 13px;text-align: center;">:</td>
        <td style="font-size: 13px;">{{ $data->no_sp3 }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">1.2.</td>
        <td style="font-size: 13px;">Berita Acara dan Negosiasi Harga</td>
        <td style="font-size: 13px;text-align: center;">:</td>
        <td style="font-size: 13px;">{{ $data->no_sp3 }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">1.3.</td>
        <td style="font-size: 13px;">Diperintahkan kepada</td>
        <td style="font-size: 13px;text-align: center;">:</td>
        <td style="font-size: 13px;">{{ !empty($data->vendor)?$data->vendor->nama:'' }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">&nbsp;</td>
        <td style="font-size: 13px;">Nama</td>
        <td style="font-size: 13px;text-align: center;">:</td>
        <td style="font-size: 13px;">{{ $data->app2_name }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">&nbsp;</td>
        <td style="font-size: 13px;">Jabatan</td>
        <td style="font-size: 13px;text-align: center;">:</td>
        <td style="font-size: 13px;">{{ $data->app2_jbt }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">&nbsp;</td>
        <td style="font-size: 13px;">Alamat</td>
        <td style="font-size: 13px;text-align: center;">:</td>
        <td style="font-size: 13px;vertical-align: top;">{{ !empty($data->vendor)?$data->vendor->alamat:'' }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">1.4.</td>
        <td style="font-size: 13px;">Untuk melaksanakan pekerjaan sebagai berikut :</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">1.4.1.</td>
        <td style="font-size: 13px;">{{ !empty($sbu)?$sbu->ket:'' }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">1.4.2.</td>
        <td style="font-size: 13px;">Nomor Pokok Pelanggan (NPP) Intern Wika Beton mengacu pada :</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">&nbsp;</td>
        <td style="font-size: 13px;">- NPP No : {{ $data->no_npp }}</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight: bold;font-size: 13px;">2.&nbsp;</td>
        <td style="font-weight: bold;font-size: 13px;">Volume dan Harga Pekerjaan</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td rowspan="2" style="vertical-align: middle;text-align: center;">No</td>
        <td colspan="2" style="text-align: center;">Uraian</td>
        <td colspan="2" style="text-align: center;">Volume</td>
        <td style="text-align: center;">Harga Satuan</td>
        <td style="text-align: center;">Jumlah Harga</td>
        <td style="text-align: center;vertical-align: middle;" rowspan="2">No.SPPRB</td>
        <td style="text-align: center;">Jarak</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>Asal Pabrik</td>
        <td>Tipe Produk</td>
        <td>(Btg)</td>
        <td>(Ton)</td>
        <td>(Rp)</td>
        <td>(Rp)</td>
        <td>(Nm)</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>{{ !empty($sbu)?$sbu->ket:'' }}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>

    @if (count($detail) > 0)
        <?php 
            $i = 1; 
            $total_vol_akhir = 0;
            $total_vol_ton_akhir = 0;
            $total_jml_harga = 0;
        ?>

        @foreach($detail as $row)
            <?php 
                $total_vol_akhir +=  $row->vol_akhir;
                $total_vol_ton_akhir += $row->vol_ton_akhir;
                $jml_harga = $row->vol_ton_akhir * $row->harsat_akhir;

                $total_jml_harga += $jml_harga;
            ?>

            <tr>
                <td>&nbsp;</td>
                <td style="text-align: center;">{{$i}}</td>
                <td>{{ !empty($row->pat)?$row->pat->ket:'' }}</td>
                <td>{{ !empty($row->produk)?$row->produk->tipe:'' }}</td>
                <td>{{ $row->vol_akhir }}</td>
                <td>{{ $row->vol_ton_akhir }}</td>
                <td>{{ number_format($row->harsat_akhir) }}</td>
                <td>{{ number_format($jml_harga) }}</td>
                <td>{{ !empty($vspprbRi)?$vspprbRi->no_spprb:'' }}</td>
                <td>{{ $row->jarak_km }}</td>
            </tr>
            <?php $i++; ?>
        @endforeach

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" style="text-align: center;">Jumlah</td>
            <td>{{ $total_vol_akhir }}</td>
            <td>{{ $total_vol_ton_akhir }}</td>
            <td colspan="4">&nbsp;</td>
        </tr>
       
        <tr>
            <td>&nbsp;</td>
            <td colspan="6" style="text-align: right;">Jumlah</td>
            <td>{{ number_format($total_jml_harga) }}</td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td colspan="5" style="text-align: right;">PPN</td>
            <td>0%</td>
            <td>0</td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td colspan="6" style="text-align: right;">Jumlah Setelah PPN</td>
            <td>{{ number_format($total_jml_harga) }}</td>
            <td></td>
            <td></td>
        </tr>
    @else
        <tr>
            <td>&nbsp;</td>
            <td colspan="9" style="font-size: 12px;text-align: center;">Data Kosong</td>
        </tr>
    @endif
    <tr>
        <td>&nbsp;</td>
        <td colspan="9" style="font-size: 12px;">
            <b>Terbilang : </b># <i>{{ !empty($total_jml_harga)?ucwords(terbilang($total_jml_harga)):'Nol' }} Rupiah</i>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td colspan="9" style="font-size: 13px;">
            Harga termasuk :
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;text-align: center;">-</td>
        <td colspan="8" style="font-size: 13px;">
            ........................(PPh 23)
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;text-align: center;">-</td>
        <td colspan="8" style="font-size: 13px;">
            ........................(Lashing, Unlashing, Dunnage, Material (Kayu ganjal, Kayu) Tumpuan)
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;text-align: center;">-</td>
        <td colspan="8" style="font-size: 13px;">
            ........................(Premi Kru Kapal)
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;text-align: center;">-</td>
        <td colspan="8" style="font-size: 13px;">
            ........................(Asuransi Kapal)
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;text-align: center;">-</td>
        <td colspan="8" style="font-size: 13px;">
            …...........................................................................................
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;text-align: center;">-</td>
        <td colspan="8" style="font-size: 13px;">
            …...........................................................................................
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td style="font-weight: bold;font-size: 13px;">3.&nbsp;</td>
        <td style="font-weight: bold;font-size: 13px;">Pelaksanaan</td>
    </tr>
    <?php 
    $date1 = new DateTime($data->jadwal1);
    $date2 = new DateTime($data->jadwal2);
    $interval = $date1->diff($date2);
    ?>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">3.1.</td>
        <td style="font-size: 13px;" colspan="10">
            Jangka Waktu Pelaksanaan {{$interval->days}} hari, mulai dari tanggal {{ date('d.m.Y', strtotime($data->jadwal1)) }} sampai dengan {{ date('d.m.Y', strtotime($data->jadwal2)) }} dan penyelesaiannya disesuaikan dengan jadwal kontrak<br>
            atau sesuai permintaan Pelaksana Utama PT. Wijaya Karya Beton, Tbk berdasarkan progress pembayaran, kebutuhan pelanggan maupun kesiapan site.
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight: bold;font-size: 13px;">4.&nbsp;</td>
        <td style="font-weight: bold;font-size: 13px;">Penanganan Barang / jasa</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">4.1.</td>
        <td style="font-size: 13px;" colspan="10">
            Proses penanganan barang/ jasa harus mengikuti ketentuan <span style="font-weight: bold;">Keselamatan, Kesehatan Kerja dan Lingkungan (K3L).</span>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">4.2.</td>
        <td style="font-size: 13px;" colspan="10">
            Barang/ Jasa yang berdampak pada <span style="font-weight: bold;">Keselamatan, Kesehatan Kerja dan Lingkungan (K3L)</span> harus menyertakan dokumen <span style="font-weight: bold;">Material Safety Data Sheet (MDS) atau yang setara</span>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">4.3.</td>
        <td style="font-size: 13px;" colspan="10">
            Vendor memastikan semua surat-surat dan perijinan terkait pelayaran masih berlaku dan disampaikan sebelum berlayar
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">4.4.</td>
        <td style="font-size: 13px;" colspan="10">
            Koordinasi dalam penanganan Barang/ Jasa dengan Pelaksana Utama/ Pelaksana {{ $data->unitkerja->ket ?? "Unknown"}} {{ $pics }} 
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight: bold;font-size: 13px;">5.&nbsp;</td>
        <td style="font-weight: bold;font-size: 13px;">Pembayaran</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.1.</td>
        <td style="font-size: 13px;" colspan="10">
            Pembayaran menggunakan SCF dengan jangka waktu 180 dan beban bunga SCF ditanggung Vendor
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.2.</td>
        <td style="font-size: 13px;" colspan="10">
            Pembayaran dilakukan secara bertahap sesuai opname yang ditagihkan setiap minggunya.
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.3.</td>
        <td style="font-size: 13px;" colspan="10">
            {{ !empty($data->vendor) ? Str::title($data->vendor->nama) : '' }} mengajukan tagihan kepada PT. Wijaya Karya Beton, Tbk dengan kelengkapan administrasi sebagai berikut:
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.3.1</td>
        <td style="font-size: 13px;" colspan="9">
            …....Kuitansi tagihan dibuat rangkap 2 dan 1 diantaranya bermaterai cukup
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.3.2</td>
        <td style="font-size: 13px;" colspan="9">
            …....Invoice
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.3.2</td>
        <td style="font-size: 13px;" colspan="9">
            …....Berita Acara Serah Terima Pekerjaan dan opname yang sudah ditandatangai kedua belah pihak dibuat rangkap 2
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.3.2</td>
        <td style="font-size: 13px;" colspan="9">
            …....e-SPtB yang telah ditandatangai Pelanggan
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.3.2</td>
        <td style="font-size: 13px;" colspan="9">
            …....Rekapitulasi Pembayaran rangkap 2
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">5.3.2</td>
        <td style="font-size: 13px;" colspan="9">
            …....Foto Copy Surat Perintah Pelaksanaan Pekerjaan (SP3) rangkap 2
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">…....</td>
        <td style="font-size: 13px;" colspan="9">
            …....................................................................................
        </td>
    </tr><tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">…....</td>
        <td style="font-size: 13px;" colspan="9">
            …....................................................................................
        </td>
    </tr><tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">…....</td>
        <td style="font-size: 13px;" colspan="9">
            …....................................................................................
        </td>
    </tr><tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">…....</td>
        <td style="font-size: 13px;" colspan="9">
            …....................................................................................
        </td>
    </tr><tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">…....</td>
        <td style="font-size: 13px;" colspan="9">
            …....................................................................................
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight: bold;font-size: 13px;">6.&nbsp;</td>
        <td style="font-weight: bold;font-size: 13px;">Sanksi dan Denda</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">6.1.</td>
        <td style="font-size: 13px;" colspan="10">
            PT Wijaya Karya Beton, Tbk {{ $data->unitkerja->ket ?? "Unknown"}} berhak membatalkan pekerjaan secara sepihak apabila Vendor dinilai tidak mampu melaksanakan pekerjaan sesuai Surat Perjanjian Pelaksanaan Pekerjaan ini.
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">6.2.</td>
        <td style="font-size: 13px;" colspan="10">
            Keterlambatan penyelesaian  pekerjaan sesuai item 3.1 dikenakan denda keterlambatan 0.5 % (setengah persen) per hari dan maksimal 5 % (lima persen) terhadap nilai pekerjaan.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight: bold;font-size: 13px;">7.&nbsp;</td>
        <td style="font-weight: bold;font-size: 13px;">Resiko Pelaksanaan </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">7.1.</td>
        <td style="font-size: 13px;" colspan="10">
            Segala resiko kerusakan armada angkutan, retribusi angkutan, perijinan dan segala macam biaya untuk kelancaran angkutan, sepenuhnya menjadi tanggung jawab Vendor.
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">7.2.</td>
        <td style="font-size: 13px;" colspan="10">
            Biaya yang timbul akibat kecelakan angkutan dan atau kerusakan barang yang diangkut menjadi tanggungan Vendor, tanpa adanya biaya tambahan apapun dari PT. Wijaya Karya Beton, Tbk.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight: bold;font-size: 13px;">8.&nbsp;</td>
        <td style="font-weight: bold;font-size: 13px;">Kerahasiaan Dokumen </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">8.1.</td>
        <td style="font-size: 13px;" colspan="10">
            PT Wijaya Karya Beton, Tbk dan Vendor harus:
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">8.1.1</td>
        <td style="font-size: 13px;" colspan="9">
            Menyatakan dan menjamin akan menjaga kerahasiaan terkait ketentuan-ketentuan yang tercantum dalam perjanjian ini dan tidak akan mempublikasikan dan/atau <br> menyebarluaskan termasuk setiap informasi atau dokumen apapun yang berkaitan dengan perjanjian ini tanpa persetujuan tertulis yang disepakati para pihak.
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">8.1.2</td>
        <td style="font-size: 13px;" colspan="9">
            Menggunakan informasi Rahasia tersebut secara ekslusif hanya untuk pelaksanaan pekerjaan ini.
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">8.1.3</td>
        <td style="font-size: 13px;" colspan="9">
            Melakukan tindakan apapun yang diperlukan untuk menjaga kerahasiaan Informasi Rahasia.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">8.2.</td>
        <td style="font-size: 13px;" colspan="10">
            Pemberitahuan, siaran pers atau komunikasi lain terkait perjanjian ini tidak dapat dibuat atau diijinkan tanpa persetujuan tertulis sebelumnya dari PT Wijaya Karya Beton, <br>
            Tbk dan Vendor, kecuali apabila pemberitahuan, siaran pers, atau komunikasi tersebut dibuat berdasarkan Undang-Undang atau perintah instansi pemerintah, yang harus <br>
            terlebih dahulu didiskusikan dan disetujui terlebih dahulu oleh PT Wijaya Karya Beton, Tbk dan Vendor.
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">8.3.</td>
        <td style="font-size: 13px;" colspan="10">
            Pihak yang membocorkan informasi rahasia (Pihak yang Merugikan) dengan ini menyatakan dan menjamin akan bertanggung jawab penuh dan akan mengganti atas setiap <br>
            kerugiaan baik materiil dan immateriil yang diderita oleh Pihak yang dibocorkan informasi rahasianya (Pihak yang Dirugikan) sebagaimana yang dimaksud dalam butir 8.1 <br>
            baik oleh pengurus, pegawai dan/atau afiliasi dari Pihak yang Merugikan serta membebaskan Pihak yang Dirugikan dalam gugatan apapun yang diajukan oleh pihak ketiga.
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="12" style="font-size: 13px;">Demikian Surat Perjanjian Pelaksanaan Pekerjaan ini dibuat untuk dilaksanakan sebagaimana mestinya.</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">Setuju melaksanakan,</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">PT. Wijaya Karya Beton, Tbk</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">{{ !empty($data->vendor) ? Str::title($data->vendor->nama) : '' }}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">{{ !empty($data->unitkerja) ? $data->unitkerja->ket : ''}}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr><tr>
        <td>&nbsp;</td>
    </tr><tr>
        <td>&nbsp;</td>
    </tr><tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;font-weight: bold;text-decoration:underline;">{{ Str::title($data->app2_name) }}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">{{ !empty($data->manajer) ? $data->manajer->first_name . ' ' . $data->manajer->last_name : '' }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">{{ !empty($data->app2_jbt) ? Str::title($data->app2_jbt) : '' }}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size: 13px;">{{ !empty($data->unitkerja) ? 'Manajer ' . $data->unitkerja->ket : ''}}</td>
    </tr>

</table>