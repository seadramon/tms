<!DOCTYPE html>
<html>
<head>
	<title></title>
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
    .title{text-align: center;font-size: 13px;font-weight: bold;}
    .page_break { page-break-before: always; }
    body {
    	font-size: 12px;
    	font-family: Times New Roman
    }
    header{
        position: fixed;
        left: 0px;
        right: 0px;
        /*height: 150px;*/
        margin-top: -150px;
    }
    .footer{
        position: fixed;
        left: 0px;
        right: 0px;
        height: 150px;
        bottom: 0px;
        margin-bottom: -150px;
    }
</style>
</head>
<body>	
	<main>
		<table cellspacing="0" cellpadding="0">
		    <tr>
		        <td colspan="15" style="text-align: right;">Lampiran B.9</td>
		    </tr>
		    <tr>
		        <td colspan="11" style="text-align: right">
		            &nbsp;
		        </td>
		        <td colspan="2" style="text-align: left;border: 1px solid #000000;padding-left: 5px;padding-right: 10px;">
		            Form : WB-SCM-PS-02-
		        </td>
		        <td colspan="2" style="text-align: right;border: 1px solid #000000;padding-right: 5px;">
		            Rev
		        </td>
		    </tr>
		    <tr>
		    	<td colspan="15">&nbsp;</td>
		    </tr>
		    <tr>
		        <td colspan="15" style="text-align:center;vertical-align: middle; font-weight: bold;font-size: 13px;">
		        SURAT PERJANJIAN PELAKSANAAN KERJA<br>
		        ANTARA<br>
		        PT WIJAYA KARYA BETON TBK<br>
		        DENGAN<br>
		        {{ $data->vendor->nama }}
		    	</td>
		    </tr>
		    <tr>
		        <td colspan="15" style="text-align: center; vertical-align: middle;font-weight: bold;">&nbsp;</td>
		    </tr>

		    <tr>
		        <td colspan="15" style="text-align: center; vertical-align: middle;font-weight: bold;font-size: 13px;">Perihal<br>
		        {{ $data->jenisPekerjaan->ket }} untuk {{ $npp->nama_proyek }}
		        </td>
		    </tr>
		    <tr>
		        <td colspan="15" style="text-align: center; vertical-align: middle;font-size: 13px;">{{ $data->no_spk }}</td>
		    </tr>
		    <tr>
		        <td colspan="15" >&nbsp;</td>
		    </tr>
		    <tr>
		        <td colspan="15" style="vertical-align: top;">
		            Pada hari ini, {{ getDay($data->tgl_spk) }} tanggal {{fullDateHumanizeId($data->tgl_spk)}},  yang bertanda tangan dibawah ini  :
		        </td>
		    </tr>
		    <tr>
		        <td colspan="15" >&nbsp;</td>
		    </tr>

		    <tr>
		    	<td width="3%" style="vertical-align: top;">I.</td>
		        <td width="22%" colspan="5" style="font-weight:bold;vertical-align: top;">PT. Wijaya Karya Beton Tbk</td>
		        <td width="5%" style="vertical-align: top;text-align: right;padding-right: 10px;">:</td>
		        <td width="70%" colspan="8" style="vertical-align: top;">
		            Suatu Perseroan Terbatas yang tunduk pada hukum  Negara Republik Indonesia, berkedudukan di Jakarta Timur dan beralamat di Gedung WIKA Tower 1, Jln. D.I. Panjaitan Kav. 9, Jati Negara, Jakarta Timur, Indonesia, 13340,  didirikan berdasarkan Hukum Negara Republik Indonesia, berdasarkan Anggaran Dasar PT Wijaya Karya Beton Tbk., No. 44 tertanggal 11 Maret 1997, yang dibuat dihadapan Achmad Bajumi, S.H., pengganti dari Imas Fatimah, S.H., Notaris di Jakarta, yang telah beberapa kali diubah dan terakhir kali diubah dengan Akta Perubahan Anggaran Dasar No. 09 tanggal 08 Juni 2023 dibuat dihadapan Ir. Nanette Cahyanie Handari Adi Warsito S.H., Notaris di Jakarta Selatan dan telah memperoleh persetujuan Kementerian Hukum dan HAM RI No. AHU-0032467.AH.01.02.TAHUN 2023 tanggal 12 Juni 2023, dalam hal ini diwakili oleh {{ $data->pihak1 }} selaku {{ $data->pihak1_jabatan }} PT. Wijaya Karya Beton Tbk, bertindak untuk dan atas nama PT Wijaya Karya Beton Tbk. Selanjutnya dalam <strong>Perjanjian</strong> ini disebut <strong>“PIHAK KESATU”</strong><br><br>
		        	&nbsp;Dan
		        	<br><br>
		        </td>
		    </tr>

		    <tr>
		    	<td width="3%" style="vertical-align: top;">II.</td>
		        <td width="22%" colspan="5" style="font-weight:bold;vertical-align: top;">{{ $data->vendor->nama }}</td>
		        <td width="5%" style="vertical-align: top;text-align: right;padding-right: 10px;">:</td>
		        <td width="70%" colspan="8" style="vertical-align: top;">
		            Suatu [Jenis Perseroan] yang tunduk pada hukum Negara Republik Indonesia, berkedudukan di {{ !empty($data->vendor->kota)?ucwords(strtolower($data->vendor->kota)):"" }} dan beralamat di {{ !empty($data->vendor->kota)?ucwords(strtolower($data->vendor->alamat)):"" }} didirikan berdasarkan [akta pendirian beserta SK Kemenkumham] yang telah beberapa kali diubah dan terakhir kali diubah dengan [akta perubahan anggaran dasar-(jika ada) beserta SK Kemenkumham], dalam hal ini diwakili oleh {{ $data->pihak2 }} selaku {{ $data->pihak2_jabatan }} {{ $data->vendor->nama }}. Selanjutnya dalam Perjanjian disebut <strong>"PIHAK KEDUA"</strong>.

		        </td>
		    </tr>

		</table><br><br><br><br><br>
		<p>
		<strong>PIHAK KESATU</strong> dan <strong>PIHAK KEDUA</strong> secara bersama-sama selanjutnya disebut <strong>“PARA PIHAK”</strong>.
		</p>

		<div class="footer">
			<table cellspacing="0" cellpadding="2" width="100%" style="font-size: 12px;">
				<tr>
					<td width="18%" colspan="3" style="border: 1px solid #000000;text-align: center;">PIHAK KEDUA</td>
					<td width="64%">&nbsp;</td>
					<td width="18%" colspan="3" style="border: 1px solid #000000;text-align: center;">PIHAK PERTAMA</td>
				</tr>
				<tr>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

					<td width="64%">&nbsp;</td>

					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

				</tr>
			</table>
		</div>
		<div class="page_break"></div>

		<p>
			<b>PARA PIHAK</b> menerangkan terlebih dahulu bahwa <b>PARA PIHAK</b> telah membuat dan menandatangani Berita Acara Negosiasi {{$data->no_ban}} {{fullDateHumanizeId($data->tgl_ban)}} tentang {{ $data->jenisPekerjaan->ket }} untuk {{ $npp->nama_proyek }}
		</p><br>

		<p>
			Sehubungan dengan hal tersebut diatas, <b>PARA PIHAK</b> sepakat untuk membuat Perjanjian Kerja, yang selanjutnya disebut <b>“Perjanjian”</b>, dengan ketentuan-ketentuan dan syarat-syarat sebagai berikut  :
		</p><br><br>

		<div class="title">
			PASAL 1 <br>
			MAKSUD DAN TUJUAN
		</div>
		<p>
			<b>PIHAK KESATU</b> menyatakan setuju untuk menyerahkan {{ $data->jenisPekerjaan->ket }} untuk {{ $npp->nama_proyek }} kepada <b>PIHAK KEDUA</b> dan <b>PIHAK KEDUA</b> menyatakan telah setuju dan mengikatkan diri untuk pekerjaan tersebut kepada <b>PIHAK KESATU</b> berdasarkan ketentuan-ketentuan dan syarat yang tercantum dalam <b>Perjanjian</b> ini.
		</p>
		<br><br>

		<div class="title">
			PASAL 2 <br>
			NAMA DAN LINGKUP BARANG
		</div>
		<table style="margin-top: 20px;" width="100%">
			<tr>
				<td width="3%">1.</td>
				<td width="25%" colspan="2">Nama Pekerjaan</td>
				<td width="2%">:</td>
				<td width="70%">{{ $data->jenisPekerjaan->ket }}</td>
			</tr>
			<tr>
				<td width="3%">2.</td>
				<td width="25%" colspan="2">Lokasi Pekerjaan</td>
				<td width="2%">:</td>
				<td width="70%">{{ $npp->pat->ket }}</td>
			</tr>
			<tr>
				<td width="3%">3.</td>
				<td width="25%" colspan="2">Lingkup Pekerjaan</td>
				<td width="2%">:</td>
				<td width="70%">&nbsp;</td>
			</tr>

			<tr>
				<td width="3%">&nbsp;</td>
				<td width="3%" style="vertical-align: top;">3.1.</td>
				<td width="94%" colspan="3">
					Mendistribusikan produk {{ !empty($npp->sbu)?$npp->sbu->ket:"" }} dari {{ $npp->pat->ket }} ke {{ !empty($npp->pelanggan)?$npp->pelanggan->alamat:"-" }}
				</td>
			</tr>
			<tr>
				<td width="3%">&nbsp;</td>
				<td width="3%" style="vertical-align: top;">3.2.</td>
				<td width="94%" colspan="3">
					<b>PIHAK KEDUA</b> memastikan :<br>
					- Surat kapal masih berlaku<br>
					- Surat Izin Berlayar (SIB) disampaikan sebelum berlayar
				</td>
			</tr>
			<tr>
				<td width="3%">&nbsp;</td>
				<td width="3%" style="vertical-align: top;">3.3.</td>
				<td width="94%" colspan="3">
					<b>PIHAK KEDUA</b> memastikan dan bertanggung jawab penuh kapal bisa masuk sampai ke titik lokasi termasuk mengkondisikan perizinannya
				</td>
			</tr>
			<tr>
				<td width="3%">&nbsp;</td>
				<td width="3%" style="vertical-align: top;">3.4.</td>
				<td width="94%" colspan="3">
					<b>PIHAK KEDUA</b> harus memberikan semua surat jalan lengkap yang sudah ditandatangani oleh Pelanggan ke PT. Wijaya Karya Beton Tbk per shipment pengiriman, setelah 3 hari selesai bongkar
				</td>
			</tr>
		</table><br><br>

		<div class="title">
			PASAL 3 <br>
			KETENTUAN  DAN PERSYARATAN PELAKSANAAN PEKERJAAN
		</div>
		<p>
			Dalam pelaksanaan pekerjaan seperti yang tercantum dalam Pasal 2 surat <b>Perjanjian</b> ini, <b>PIHAK KEDUA</b> terikat pada ketentuan-ketentuan yang telah disepakati bersama, yaitu :<br>
			1. Proses penanganan barang/ jasa harus mengikuti ketentuan SMK3<br>
			2. Barang/ Jasa yang berdampak pada SMK3 harus menyertakan dokumen Material Safety Data Sheet (MSDS) atau yang setara<br>
			3. Proses Penanganan barang/ jasa agar berhubungan dan berkoordinasi dengan [PELUT/PELAKSANA] Bapak [NAMA PELUT/ PELAKSANA] ([NO HANDPHONE])
		</p>
		<br>

		<div class="title">
			PASAL 4 <br>
			WAKTU PELAKSANAAN
		</div>
		<p>
			1.&nbsp;&nbsp;Jangka waktu pelaksanaan untuk pekerjaan tersebut adalah tanggal {{fullDateHumanizeId($data->jadwal1)}} sampai dengan {{fullDateHumanizeId($data->jadwal2)}}  <br><br>
			2.&nbsp;&nbsp;Apabila terjadi perubahan waktu dari yang telah disepakati maka dapat diusulkan perubahan berdasarkan kesepakatan <b>PARA PIHAK</b>.<br>
		</p>

		<div class="footer">
			<table cellspacing="0" cellpadding="2" width="100%" style="font-size: 12px;">
				<tr>
					<td width="18%" colspan="3" style="border: 1px solid #000000;text-align: center;">PIHAK KEDUA</td>
					<td width="64%">&nbsp;</td>
					<td width="18%" colspan="3" style="border: 1px solid #000000;text-align: center;">PIHAK PERTAMA</td>
				</tr>
				<tr>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

					<td width="64%">&nbsp;</td>

					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

				</tr>
			</table>
		</div>
		<div class="page_break"></div>

		<div class="title">
			PASAL 5 <br>
			PEMESANAN DAN PENGADAAN BARANG DAN JASA
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Nilai Pekerjaan sebagai berikut  :	
		</div>
		<table width="100%" cellpadding="0" cellspacing="0" border="1" style="border-color: #000000;margin-left: 15px;">
			<tr>
				<th rowspan="2">No.</th>
				<th colspan="2">Uraian</th>
				<th colspan="2">Volume</th>
				<th colspan="2">Harga Satuan(Rp)</th>
				<th>Jumlah Harga</th>
			</tr>
			<tr>
				<th>Pabrik</th>
				<th>Tipe Produk</th>
				<th>(Btg)</th>
				<th>(Ton)</th>
				<th>(Btg)</th>
				<th>(Ton)</th>
				<th>(Rp)</th>
			</tr>
			@if(count($data->spk_d) > 0)
				<?php $i = 1; ?>
				@foreach($data->spk_d as $detail)
					<td>{{ $i }}</td>
					<td>{{ !empty($detail->pat)?$detail->pat->ket:'' }}</td>
					<td>{{ !empty($detail->produk)?$detail->produk->tipe:"" }}</td>
					<td>{{ !empty($detail->vol_btg)?$detail->vol_btg:"0" }}</td>
					<td>{{ !empty($detail->vol_ton)?$detail->vol_ton:"0" }}</td>
					<td>{{ !empty($detail->sat_harsat)?$detail->sat_harsat:"0" }}</td>
					<td>{{ !empty($detail->sat_volume)?$detail->sat_volume:"0" }}</td>
					<td>(Rp)</td>

					<?php $i++; ?>
				@endforeach
			@else
				<tr>
					<td colspan="8">Data Kosong</td>
				</tr>
			@endif
			<tr>
				<td colspan="3" class="tengahBold">Jumlah</td>
				<td class="tengah">-</td>
				<td class="tengah">-</td>
				<td colspan="2">-</td>
				<td>-</td>
			</tr>
			<tr>
				<td colspan="3" class="tengahBold">PPN 11%</td>
				<td colspan="4">&nbsp;</td>
				<td class="tebal">-</td>
			</tr>
			<tr>
				<td colspan="3" class="tengahBold">Harga</td>
				<td colspan="4">&nbsp;</td>
				<td class="tebal">-</td>
			</tr>
		</table>
		<div style="margin-top: 5px;margin-left: 15px;margin-bottom: 10px;">
			<b>Terbilang : &nbsp;&nbsp;&nbsp;</b><i>Delapan Belas Milyar Sembilan Ratus Sembilan Puluh Delapan Puluh Satu Juta Rupiah</i>
		</div>

		<div style="margin-bottom: 5px;">
			2.&nbsp;&nbsp;Harga pekerjaan sudah termasuk :
		</div>
		<div style="width: 100%;border: 1px solid #000000;height: 80px;margin-left: 15px;margin-bottom: 5px;">
			&nbsp;
		</div>
		<div style="margin-bottom: 15px;">
			3.&nbsp;&nbsp;<b>PIHAK KEDUA</b> menjamin tidak akan mengajukan klaim kenaikan harga dengan dalih apapun apabila dikelak kemudian hari ada kenaikan harga, kecuali ada perubahan secara dramatis dalam bidang moneter.
		</div>

		<div class="title">
			PASAL 6 <br>
			CARA PEMBAYARAN
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Pembayaran berdasarkan progres pengiriman dengan Fasilitas SCF usance 180 hari, beban bunga SCF ditanggung oleh <b>PIHAK KEDUA</b>.
		</div>
		<div style="margin-bottom: 5px;">
			2.&nbsp;&nbsp;<b>PIHAK KEDUA</b> Angkutan yang tidak dikenakan PPN, harus melampirkan Surat Pernyataan Bebas PPN.
		</div>
		<div style="margin-bottom: 5px;">
			3.&nbsp;&nbsp;Dokumen tagihan harus melampirkan :
		</div>
		<div style="width: 100%;border: 1px solid #000000;height: 80px;margin-left: 15px;margin-bottom: 15px;">
			&nbsp;
		</div>

		<div class="title">
			PASAL 7 <br>
			KESELAMATAN DAN KESEHATAN KERJA
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Mengikuti peraturan <b>Keselamatan, Kesehatan Kerja dan Lingkungan (K3L)</b> yang diberlakukan oleh <b>PIHAK KESATU</b>.
		</div>
		<div style="margin-bottom: 5px;">
			2.&nbsp;&nbsp;<b>PIHAK KEDUA</b> menyediakan tenaga kerja yang dilindungi dengan jaminan sosial tenaga kerja (Jamsostek)
		</div>
		<div style="margin-bottom: 5px;">
			3.&nbsp;&nbsp;<b>PIHAK KEDUA</b> dalam pelaksanaan pekerjaan baik itu dilokasi pekerjaan atau lokasi pabrikasi (workshop) harus mematuhi ketentuan peraturan <b>SMK3L/ISO 14001 (Sistem - Manajemen Keselamatan, Kesehatan Kerja dan Lingkungan)</b> yang diterapkan oleh <b>PIHAK KESATU</b>.
		</div>
		<div style="margin-bottom: 5px;">
			4.&nbsp;&nbsp;<b>PIHAK KEDUA</b> dilarang mempekerjakan  Tenaga Kerja dengan usia dibawah umur yang ditentukan oleh Departemen Tenaga Kerja dan wajib mengasuransikan Tenaga Kerja yang dipekerjakan di Proyek.
		</div>
		<div style="margin-bottom: 5px;">
			5.&nbsp;&nbsp;<b>PIHAK KEDUA</b> harus menyediakan personil safety officer yang akan memantau terhadap pematuhan dari ketentuan keselamatan dan kesehatan kerja di lapangan.
		</div>
		<div style="margin-bottom: 5px;">
			6.&nbsp;&nbsp;Apabila dalam pelaksanaan pekerjaan <b>PIHAK KEDUA</b> tidak mematuhi ketentuan tentang Keselamatan dan Kesehatan Kerja, <b>PIHAK KESATU</b> akan memberikan pemberitahuan tertulis kepada PIHAK KEDUA untuk segera melaksanakan ketentuan tersebut.
		</div>

		<div class="footer">
			<table cellspacing="0" cellpadding="2" width="100%" style="font-size: 12px;">
				<tr>
					<td width="18%" colspan="3" style="border: 1px solid #000000;text-align: center;">PIHAK KEDUA</td>
					<td width="64%">&nbsp;</td>
					<td width="18%" colspan="3" style="border: 1px solid #000000;text-align: center;">PIHAK PERTAMA</td>
				</tr>
				<tr>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

					<td width="64%">&nbsp;</td>

					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

				</tr>
			</table>
		</div>
		<div class="page_break"></div>

		<div class="title">
			PASAL 8 <br>
			SANKSI DAN PEMUTUSAN PERJANJIAN
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Apabila <b>PIHAK KEDUA</b> melakukan pekerjaan tidak sesuai dengan spesifikasi yang ditentukan dalam <b>Perjanjian</b>  ini, maka <b>PIHAK KESATU</b> berhak untuk menolak Pekerjaan tersebut. Resiko biaya akibat hal ini sepenuhnya menjadi tanggungan <b>PIHAK KEDUA</b>.
		</div>
		<div style="margin-bottom: 5px;">
			2.&nbsp;&nbsp;<b>PARA PIHAK</b> sepakat  mengesampingkan / tidak memberlakukan Pasal 1266 Kitab Undang- Undang Hukum Perdata ( KUHPer) untuk memutuskan <b>Perjanjian</b> ini.
		</div>
		<div style="margin-bottom: 5px;">
			3.&nbsp;&nbsp;<b>PIHAK KESATU</b> berhak memutuskan <b>Perjanjian</b> ini secara sepihak, dengan pemberitahuan tertulis 7 (tujuh) hari sebelumnya setelah melakukan peringatan / teguran tertulis kepada <b>PIHAK KEDUA</b>  :<br>
			<div style="margin-top: 5px;margin-left: 15px;">
				3.1.&nbsp;&nbsp;Dalam waktu 7 (tujuh) hari terhitung dari Surat Perintah Mulai Pekerjaan, tidak atau belum mulai melaksanakan Pekerjaan dalam <b>Perjanjian</b> ini<br>
				3.2.&nbsp;&nbsp;Dalam waktu 7 (tujuh) hari berturut-turut tidak melanjutkan Pekerjaan yang telah dimulai, tanpa alasan yang dapat diterima oleh <b>PIHAK KESATU</b><br>
				3.3.&nbsp;&nbsp;Dalam jangka waktu 7 (tujuh) hari kalender, sejak mendapat perintah untuk melakukan perbaikan Pekerjaan dari <b>PIHAK KESATU</b>, tidak memperbaiki pekerjaan yang tidak sesuai dengan spesifikasi yang disepakati.<br>
				3.4.&nbsp;&nbsp;Terjadi keterlambatan Progress pekerjaan sampai dengan sebesar 10% (sepuluh persen), tanpa alasan yang dapat diterima secara teknis dan atau nonteknis.<br>
			</div>
		</div>
		<div style="margin-bottom: 5px;">
			4.&nbsp;&nbsp;Sebagai akibat dari terjadinya pemutusan <b>Perjanjian</b> kepada <b>PIHAK KEDUA</b> dikenakan sanksi yaitu sisa pengembalian uang muka harus dilunasi sekaligus kepada <b>PIHAK KESATU</b>.
		</div>
		<div style="margin-bottom: 15px;">
			5.&nbsp;&nbsp;Jika terjadi pemutusan perjanjian secara sepihak oleh <b>PIHAK KESATU</b>, maka  <b>PIHAK KESATU</b> dapat menunjuk pihak lain atas kehendak sendiri untuk menyelesaikan Pekerjaan ini, tanpa ada tuntutan apapun dari <b>PIHAK KEDUA</b>.
		</div>

		<div class="title">
			PASAL 9 <br>
			DENDA KELALAIAN DAN DENDA KETERLAMBATAN
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Jika <b>PIHAK KEDUA</b> tidak dapat melakukan pengiriman sesuai dengan schedule   atau jangka waktu pelaksanaan yang telah disepakati dalam perjanjian ini karena kesalahan <b>PIHAK KEDUA</b>, maka untuk setiap hari keterlambatan, <b>PIHAK KEDUA</b> wajib membayar denda keterlambatan sebesar 1‰ (satu permil) dari harga kesepakatan, sampai sebanyak - banyaknya 5% (lima persen) dari harga kesepakatan sebagaimana tersebut dalam <b>Perjanjian</b> ini.
		</div>
		<div style="margin-bottom: 5px;">
			2.&nbsp;&nbsp;Apabila keterlambatan penyelesaian pekerjaan telah mencapai 10% (sepuluh persen) dan <b>PIHAK KEDUA</b> tetap tidak mampu menyelesaikan pekerjaan tersebut, maka <b>PIHAK KESATU</b> berhak menunjuk <b>PIHAK LAIN</b> untuk melaksanakan /melanjutkan pekerjaan /sisa pekerjaan, tanpa ada tuntutan apapun dari <b>PIHAK KEDUA</b>.
		</div>
		<div style="margin-bottom: 5px;">
			3.&nbsp;&nbsp;Denda tersebut dalam ayat 1 pasal ini, akan diperhitungkan dengan kewajiban pembayaran  <b>PIHAK KESATU</b> kepada <b>PIHAK KEDUA</b>.
		</div>
		<div style="margin-bottom: 5px;">
			4.&nbsp;&nbsp;Denda ini tidak dikenakan kepada <b>PIHAK KEDUA</b> apabila keterlambatan tersebut bukan akibat kesalahan <b>PIHAK KEDUA</b>, atau apabila keterlambatan tersebut disebabkan oleh kelalaian <b>PIHAK KESATU</b> untuk memenuhi kewajibannya.
		</div>

		<div class="footer">
			<table cellspacing="0" cellpadding="2" width="100%" style="font-size: 12px;">
				<tr>
					<td width="18%" colspan="3" style="border: 1px solid #000000;text-align: center;">PIHAK KEDUA</td>
					<td width="64%">&nbsp;</td>
					<td width="18%" colspan="3" style="border: 1px solid #000000;text-align: center;">PIHAK PERTAMA</td>
				</tr>
				<tr>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

					<td width="64%">&nbsp;</td>

					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
					<td width="6%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

				</tr>
			</table>
		</div>
		<div class="page_break"></div>
	</main>

	<div class="title">
			PASAL 10 <br>
			KEADAAN MEMAKSA/FORCE MAJEURE
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Keadaan memaksa / force majeure menurut Perjanjian ini adalah semua hal yang terjadi diluar kemampuan <b>PARA PIHAK</b> untuk mengatasinya yaitu, peperangan, blokade, huru-hara, bencana alam (banjir, gempa bumi), pemogokan (yang bukan disebabkan oleh kesalahan <b>PIHAK KEDUA</b>) dan kebakaran yang menyebabkan kerusakan mesin <b>PIHAK KEDUA</b> yang langsung menghalangi pelaksanaan pekerjaan.
		</div>
		<div style="margin-bottom: 5px;">
			2.&nbsp;&nbsp;Apabila terjadi keadaan memaksa/force majeure, <b>PIHAK KEDUA</b> wajib memberitahukan hal tersebut kepada <b>PIHAK KESATU</b> selambat-lambatnya 3 (tiga) hari kerja sejak tanggal terjadinya force majeure untuk mendapat persetujuan <b>PIHAK KEDUA</b> disertai surat keterangan dari instansi yang berwenang tentang kondisi tersebut.
		</div>
		<div style="margin-bottom: 15px;">
			3.&nbsp;&nbsp;Kejadian-kejadian yang termasuk dalam ayat 1 Pasal ini baru dapat diartikan keadaan memaksa/ force majeure untuk pekerjaan ini apabila memang terbukti bahwa kejadian tersebut mempunyai hubungan langsung dengan pekerjaan dan dinyatakan dalam Berita Acara yang ditandatangani oleh <b>PARA PIHAK</b>.
		</div>

		<div class="title">
			PASAL 11 <br>
			PERUBAHAN
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Hal-hal lain yang belum diatur dalam <b>Perjanjian</b> ini dan apabila ada perubahan-perubahan dan <b>Perjanjian</b> ini akan diatur kemudian atas dasar permufakatan kedua belah pihak yang akan dituangkan dalam bentuk addendum atau amandemen yang merupakan kesatuan yang tidak terpisahkan dari <b>Perjanjian</b> ini.
		</div>
		<div style="margin-bottom: 15px;">
			2.&nbsp;&nbsp;Semua pemberitahuan dan surat-surat antara <b>PARA PIHAK</b> sehubungan dengan <b>Perjanjian</b> ini dilakukan secara tertulis dan dianggap telah disampaikan kepada yang bersangkutan bilamana ada tanda terima tertulis.
		</div>

		<div class="title">
			PASAL 12 <br>
			PENYELESAIAN PERSELISIHAN
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Apabila terjadi perselisihan antara <b>PARA PIHAK</b>, pertama-tama akan diselesaikan dengan musyawarah untuk mufakat dalam jangka waktu 30 (tiga puluh) hari.
		</div>
		<div style="margin-bottom: 5px;">
			2.&nbsp;&nbsp;Apabila cara musyawarah tidak dapat menyelesaikan perselisihan, perselisihan akan diselesaikan atau diputuskan pada tingkat pertama dan terakhir oleh Badan Arbitrase Nasional Indonesia (BANI) dengan menggunakan peraturan/ prosedur yang berlaku pada BANI.
		</div>
		<div style="margin-bottom: 15px;">
			3.&nbsp;&nbsp;Keputusan BANI adalah final dan mengikat <b>PARA PIHAK</b>.
		</div>

		<div class="title">
			PASAL 13 <br>
			KERAHASIAAN DOKUMEN
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;PARA PIHAK harus:
			<table width="100%" style="margin-top: 5px;margin-left: 15px;">
				<tr>
					<td width="3%" style="vertical-align: top;">1.1.</td>
					<td width="97%">
						Menyatakan dan menjamin akan menjaga kerahasiaan terkait ketentuan-ketentuan yang tercantum dalam perjanjian ini dan tidak akan mempublikasikan dan/atau menyebarluaskan termasuk setiap informasi atau dokumen apapun yang berkaitan dengan perjanjian ini tanpa persetujuan tertulis yang disepakati <b>PARA PIHAK</b>.
					</td>
				</tr>
				<tr>
					<td width="3%" style="vertical-align: top;">1.2.</td>
					<td width="97%">
						Menggunakan informasi Rahasia tersebut secara ekslusif hanya untuk pelaksanaan pekerjaan ini
					</td>
				</tr>
				<tr>
					<td width="3%" style="vertical-align: top;">1.3.</td>
					<td width="97%">
						Melakukan tindakan apapun yang diperlukan untuk menjaga kerahasiaan Informasi Rahasia
					</td>
				</tr>
			</table>
		</div>
		<div style="margin-bottom: 5px;">
			2.&nbsp;&nbsp;Pemberitahuan, siaran pers atau komunikasi lain terkait perjanjian ini tidak dapat dibuat atau diijinkan tanpa persetujuan tertulis sebelumnya dari <b>PARA PIHAK</b>, kecuali apabila pemberitahuan, siaran pers, atau komunikasi tersebut dibuat berdasarkan Undang-Undang atau perintah instansi pemerintah, yang harus terlebih dahulu didiskusikan dan disetujui terlebih dahulu dari <b>PARA PIHAK</b>.
		</div>
		<div style="margin-bottom: 15px;">
			3.&nbsp;&nbsp;Pihak yang membocorkan informasi rahasia (Pihak yang Merugikan) dengan ini menyatakan dan menjamin akan bertanggung jawab penuh dan akan mengganti atas setiap kerugiaan baik materiil dan immateriil yang diderita oleh Pihak yang dibocorkan informasi rahasianya (Pihak yang Dirugikan) sebagaimana yang dimaksud dalam butir 1 baik oleh pengurus, pegawai dan/atau afiliasi dari Pihak yang Merugikan serta membebaskan Pihak yang Dirugikan dalam gugatan apapun yang diajukan oleh pihak ketiga.
		</div>

		<div class="title">
			PASAL 14 <br>
			PENUTUP
		</div>

		<div style="margin-bottom: 5px;margin-top: 15px;">
			1.&nbsp;&nbsp;Perjanjian ini dinyatakan sah dan mengikat <b>PARA PIHAK</b> dan berlaku setelah ditandatangani oleh <b>PARA PIHAK</b>.
		</div>
		<div style="margin-bottom: 20px;">
			2.&nbsp;&nbsp;Perjanjian ini dibuat dan ditandatangani oleh <b>PARA PIHAK</b> dalam rangkap 2 (dua),  bermeterai cukup dan kedua-duanya mempunyai kekuatan hukum yang sama.
		</div>

		<table width="100%">
			<tr>
				<td width="35%" style="font-weight: bold;">
					PIHAK KEDUA<br>
					{{ $data->vendor->nama }}
				</td>
				<td width="35%">
					&nbsp;
				</td>
				<td width="30%" style="font-weight: bold;">
					PIHAK KESATU<br>
					PT Wijaya Karya Beton Tbk
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<br><br><br><br><br><br>
				</td>
			</tr>
			<tr>
				<td width="35%" style="font-weight: bold;">
					{{ $data->pihak2 }}<br>
					{{ $data->pihak2_jabatan }}
				</td>
				<td width="35%">
					&nbsp;
				</td>
				<td width="30%" style="font-weight: bold;">
					{{ $data->pihak1 }}<br>
					{{ $data->pihak1_jabatan }}
				</td>
			</tr>
		</table>
</body>
</html>