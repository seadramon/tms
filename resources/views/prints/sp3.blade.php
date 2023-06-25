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

            .tebal {
                font-weight: bold;
            }

            .right {
                text-align: right;
            }

            table.content {
                table-layout: auto; 
                width:100%;
                border-collapse: collapse;
            }

            .content table, .content th, .content td {
                border: 1px solid;
            }
            @page { margin: 160px 25px 60px 25px; }
            header { position: fixed; top: -150px; left: 0px; right: 0px; height: 50px; }

            table td, table td * {
                vertical-align: top;
            }
        </style>
    </head>

    <body>
        <header>
            <div style="text-align:right;margin-bottom: 3px;font-size: 9px;">
                Lampiran B.9<br>
                Form :WB-SCM-PS-02-F09  Rev : 00<br>
            </div>

            <div style="text-align: center;font-size:15px;margin-bottom:12px;font-weight: bold;">
                <b><u>SURAT PERINTAH PELAKSANAAN PEKERJAAN (SP3)</u></b><br>
                <div style="font-size: 11px;">
                    NOMOR     :  {{ $data->no_sp3 }}<br>
                    Tanggal : {{ date('d.m.Y', strtotime($data->tgl_sp3)) }}
                </div>
            </div>

            <div style="border-style: solid;padding:0 6 5 6;">
                <b>Pekerjaan Angkutan Produk Beton</b>
                <table>
                    <tr>
                        <td>Pesanan</td>
                        <td>:</td>
                        <td>{{ !empty($data->npp)?strtoupper($data->npp->nama_pelanggan):'' }}</td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td>{{ !empty($data->npp)?strtoupper($data->npp->nama_proyek):'' }}</td>
                    </tr>
                </table>
            </div>
            <hr style="border: 1px solid black;margin-bottom: 25px;">
        </header>

        <main>
            <b>1. Berdasarkan</b>
            <table style="margin-left: 7px;margin-bottom: 5px;">
                <tr>
                    <td>1.1.</td>
                    <td>Perjanjian Induk</td>
                    <td>:</td>
                    <td>{{ $data->no_sp3 }}</td>
                </tr>
                <tr>
                    <td>1.2.</td>
                    <td>Berita Acara dan Negosiasi Harga</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>1.3.</td>
                    <td>Diperintahkan kepada</td>
                    <td>:</td>
                    <td>{{ !empty($data->vendor)?$data->vendor->nama:'' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $data->app2_name }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $data->app2_jbt }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td style="vertical-align: top;">{{ !empty($data->vendor)?$data->vendor->alamat:'' }}</td>
                </tr>
                <tr>
                    <td>1.4.</td>
                    <td colspan="3">Untuk melaksanakan pekerjaan sebagai berikut : </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4">1.4.1. {{ !empty($sbu)?$sbu->ket:'' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4">1.4.2. Nomor Pokok Pelanggan (NPP) Intern Wika Beton mengacu pada :</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- NPP No : {{ $data->no_npp }}</td>
                </tr>
            </table>

            <b>2. Volume dan Harga Pekerjaan</b><br>
            <table class="content" border="1" width="100%" style="margin-top: 2px;margin-bottom: 3px;">
                <thead style="text-align: center">
                    <tr>
                        <th rowspan="2">No.</th>
                        <th colspan="2">Uraian</th>
                        <th colspan="2">Volume</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah Harga</th>
                        <th rowspan="2">No.SPPRB</th>
                        <th>Jarak</th>
                    </tr>
                    <tr>
                        <th>Pabrik</th>
                        <th>Tipe Produk</th>
                        <th>(Btg)</th>
                        <th>(Ton)</th>
                        <th>(Rp)</th>
                        <th>(Rp)</th>
                        <th>(Km)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_jml_harga = 0;
                    ?>
                    @if (count($detail) > 0)
                        <tr>
                            <td></td>
                            <td>{{ !empty($sbu)?$sbu->ket:'' }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php 
                        $i = 1; 
                        $total_vol_akhir = 0;
                        $total_vol_ton_akhir = 0;
                        ?>

                        @foreach($detail as $row)
                            <?php 
                                $total_vol_akhir +=  $row->vol_akhir;
                                $total_vol_ton_akhir += $row->vol_ton_akhir;
                                $jml_harga = $row->vol_ton_akhir * $row->harsat_akhir;

                                $total_jml_harga += $jml_harga;
                            ?>
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ !empty($row->pat)?$row->pat->ket:'' }}</td>
                                <td>{{ !empty($row->produk)?$row->produk->tipe:'' }}</td>
                                <td class="right">{{ $row->vol_akhir }}</td>
                                <td class="right">{{ $row->vol_ton_akhir }}</td>
                                <td class="right">{{ number_format($row->harsat_akhir) }}</td>
                                <td class="right">{{ number_format($jml_harga) }}</td>
                                <td>{{ !empty($vspprbRi)?$vspprbRi->no_spprb:'' }}</td>
                                <td class="right">{{ $row->jarak_km }}</td>   
                            </tr>
                            <?php $i++; ?>
                        @endforeach

                        <tr>
                            <td></td>
                            <td colspan="2">Jumlah</td>
                            <td class="right">{{ $total_vol_akhir }}</td>
                            <td class="right">{{ $total_vol_ton_akhir }}</td>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="right">Jumlah</td>
                            <td class="right">{{ number_format($total_jml_harga) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="right" colspan="5">PPN</td>
                            <td>0%</td>
                            <td class="right">0</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="right" colspan="6">Total Harga</td>
                            <td class="right">{{ number_format($total_jml_harga) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="9" style="text-align: center;">Data Kosong</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div style="margin-bottom: 5px;">
                <b>Terbilang : </b># <i>{{ !empty($total_jml_harga)?ucwords(terbilang($total_jml_harga)):'Nol' }} Rupiah</i>
            </div>

            <div>
                <b>3. Jangka Waktu Pelaksanaan</b>
                <table style="margin-left: 7px;margin-bottom: 5px;">
                    <tr>
                        <td>3.1.</td>
                        <td>
                            Jangka waktu pelaksanaan tanggal {{ date('d.m.Y', strtotime($data->jadwal1)) }} - {{ date('d.m.Y', strtotime($data->jadwal2)) }} dan penyelesaiannya disesuaikan dengan jadwal kontrak atau sesuai permintaan Pelaksana Utama  PT. Wika Beton, Tbk berdasarkan progres pembayaran, kebutuhan pelanggan dan kesiapan di site.
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <b>4.   Penanganan Barang / jasa</b>
                <table style="margin-left: 7px;margin-bottom: 5px;">
                    <tr>
                        <td>4.1.</td>
                        <td>
                            Proses penanganan barang / jasa harus mengikuti ketentuan SMK3.
                        </td>
                    </tr>
                    <tr>
                        <td>4.2.</td>
                        <td>
                            Barang / Jasa yang berdampak pada SMK3 harus menyertakan dokumen Material Safety Data Sheet (MSDS) atau yang setara.
                        </td>
                    </tr>
                    <tr>
                        <td>4.3.</td>
                        <td>
                            Proses penanganan barang/jasa agar berhubungan & berkoordinasi dengan Pelaksana Utama Wilayah Penjualan I {{ $pics }}
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <b>5. Pembayaran</b>
                <table style="margin-left: 7px;margin-bottom: 5px;">
                    <tr>
                        <td>5.1.</td>
                        <td>
                            Harga sudah termasuk PPh.
                        </td>
                    </tr>
                    <tr>
                        <td>5.2.</td>
                        <td>
                            Harga satuan belum termasuk PPN. Untuk Vendor Angkutan yang tidak dikenakan PPN, harus melampirkan Surat Pernyataan Bebas PPN.
                        </td>
                    </tr>
                    <tr>
                        <td>5.3.</td>
                        <td>
                            Pembayaran berdasarkan progres pengiriman dengan Fasilitas SCF usance 180 hari, beban bunga SCF ditanggung oleh Vendor.
                        </td>
                    </tr>
                    <tr>
                        <td>5.4.</td>
                        <td>
                            Dokumen tagihan harus melampirkan:<br>
                            - Kwitansi Pembayaran/Invoice, Copy SP3, Berita Acara Opname  Pekerjaan, Lembar Kendali, Rekapitulasi SPTB dan  Copy SPTB warna hijau.
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <b>6. Sanksi dan Denda</b>
                <table style="margin-left: 7px;margin-bottom: 5px;">
                    <tr>
                        <td>6.1.</td>
                        <td>
                            PT Wijaya Karya Beton, Tbk Wilayah Penjualan I berhak membatalkan pekerjaan secara sepihak apabila Vendor dinilai tidak mampu melaksanakan pekerjaan sesuai Surat Perjanjian Pelaksanaan Pekerjaan ini.
                        </td>
                    </tr>
                    <tr>
                        <td>6.2.</td>
                        <td>
                            Keterlambatan penyelesaian pekerjaan sesuai item 3.1 dikenakan denda keterlambatan 0.5 %  (setengah persen  ) per  hari dan maksimal 5 % (lima persen) terhadap nilai pekerjaan.
                        </td>
                    </tr>
                </table>
            </div>

            <div style="page-break-after: always;">
                <b>7. Resiko Pelaksanaan (Khusus Vendor Angkutan)</b>
                <table style="margin-left: 7px;margin-bottom: 21px;">
                    <tr>
                        <td>7.1.</td>
                        <td>
                            Segala resiko kerusakan armada angkutan, retribusi angkutan, perijinan dan segala macam biaya untuk kelancaran angkutan, sepenuhnya menjadi tanggung jawab Vendor.
                        </td>
                    </tr>
                    <tr>
                        <td>7.2.</td>
                        <td>
                            Biaya yang timbul akibat kecelakaan angkutan dan atau kerusakan barang yang diangkut menjadi tanggung jawab Vendor, tanpa adanya biaya tambahan apapun dari PT Wijaya Karya Beton, Tbk.
                        </td>
                    </tr>
                    <tr>
                        <td>7.3.</td>
                        <td>
                            Armada yang di gunakan adalah kendaraan umum dengan menggunakan tanda nomor kendaraan dasar kuning dan tulisan hitam, apabila mengabaikan hal ini akan menjadi resiko Vendor. 
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <b>8. Pasal Perjanjian</b>
                <table style="margin-left: 7px;margin-bottom: 10px;">
                    <tr>
                        <td>8.1.</td>
                        <td>
                            PT. Wijaya Karya Beton, Tbk Wilayah Penjualan I dan Vendor wajib tunduk pada semua pasal sesuai lampiran dibalik perjanjian ini
                        </td>
                    </tr>
                </table>
            </div>
            <div style="margin-bottom: 30px">Demikian Surat Perjanjian Pelaksanaan Pekerjaan ini dibuat untuk dilaksanakan sebagaimana mestinya.</div>

            <table width="100%">
                <tr>
                    <td width="70%" style="padding-left: 35px;">
                        Setuju melaksanakan,<br>
                        {{ !empty($data->vendor)?$data->vendor->nama:'' }}
                        {{ !empty($data->app2_jbt)?$data->app2_jbt:'' }}
                    </td>
                    <td width="30%">
                        PT. Wijaya Karya Beton, Tbk<br>
                        {{ !empty($data->unitkerja)?'Manajer '.$data->unitkerja->ket:''}}
                    </td>
                </tr>
                <tr>
                    <td style="height: 100px"></td>
                    <td style="height: 100px"></td>
                </tr>
                <tr>
                    <td style="padding-left: 35px;" class="tebal">
                        {{ $data->app2_name }}
                    </td>
                    <td class="tebal">
                        {{ !empty($data->manajer)?$data->manajer->first_name.' '.$data->manajer->last_name:'' }}
                    </td>
                </tr>
            </table>
        </main>
    </body>
</html>