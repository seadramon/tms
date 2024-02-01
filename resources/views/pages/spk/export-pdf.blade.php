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
    footer{
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
	<footer>
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
	</footer>
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
		<p>
			<b>PARA PIHAK</b> menerangkan terlebih dahulu bahwa <b>PARA PIHAK</b> telah membuat dan menandatangani Berita Acara Negosiasi {{$data->no_ban}} {{fullDateHumanizeId($data->tgl_ban)}} tentang {{ $data->jenisPekerjaan->ket }} untuk {{ $npp->nama_proyek }}
		</p><br>

		<p>
			Sehubungan dengan hal tersebut diatas, <b>PARA PIHAK</b> sepakat untuk membuat Perjanjian Kerja, yang selanjutnya disebut <b>“Perjanjian”</b>, dengan ketentuan-ketentuan dan syarat-syarat sebagai berikut  :
		</p><br><br>

		@foreach ($data->spk_pasal->sortBy('pasal') as $pasal)
			<div class="title">
				PASAL {{$pasal->pasal}} <br>
				{{$pasal->judul}}
			</div>
			{!! $pasal->keterangan !!}
			<br><br>
		@endforeach
	</main>
</body>
</html>