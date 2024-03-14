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
        top: -40px;
        left: 0px;
        right: 0px;
        height: 50px;
        text-align: right;
    }
    footer{
        position: fixed;
        bottom: -20px;
        left: 0px;
        right: 0px;
        height: 50px;
    }
</style>
</head>
<body>
    <header>
        <p>Lampiran B.9</p>
        <table cellspacing="0" cellpadding="0" style="width: 100%; margin-top: -10px">
		    <tr>
                <td style="width: 70%;"></td>
		        <td style="width: 25%;text-align: left;border: 1px solid #000000;padding-left: 5px;padding-right: 10px;">
		            Form : WB-SCM-PS-02-F10
		        </td>
		        <td style="width: 5%;text-align: right;border: 1px solid #000000;padding-right: 5px;">
		            Rev
		        </td>
		    </tr>
        </table>
    </header>
    <footer>
		<table cellspacing="0" cellpadding="2" width="100%" style="font-size: 12px;">
			<tr>
				<td width="32%" colspan="4" style="border: 1px solid #000000;text-align: center;">PIHAK KEDUA</td>
				<td width="36%">&nbsp;</td>
				<td width="32%" colspan="4" style="border: 1px solid #000000;text-align: center;">PIHAK PERTAMA</td>
			</tr>
			<tr>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

				<td width="36%">&nbsp;</td>

				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
			</tr>
			<tr>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>

				<td width="36%">&nbsp;</td>

				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
				<td width="8%" style="border: 1px solid #000000;padding-top: 5px;">&nbsp;</td>
			</tr>
		</table>
	</footer>
	<main>
        <p style="text-align: center; font-weight: bold; font-size: 13px;">
            SURAT PERJANJIAN PELAKSANAAN KERJA<br>
            ANTARA<br>
            PT WIJAYA KARYA BETON TBK<br>
            DENGAN<br>
            {{ $data->vendor->nama }}<br>
            Perihal<br>
            {{ $data->jenisPekerjaan->ket }} untuk {{ $npp->nama_proyek }} <br>
            {{ $data->no_spk }}
        </p>
        <hr>
        <p style="text-align: justify; font-size: 12px;">
            Pada hari ini, {{ getDay($data->tgl_spk) }} tanggal {{fullDateHumanizeId($data->tgl_spk)}},  yang bertanda tangan dibawah ini  :<br>
            <table style="width: 100%">
                <tr>
                    <td style="width: 2%">I.</td>
                    <td style="width: 20%">Nama</td>
                    <td style="width: 3%">:</td>
                    <td style="width: 75%">{{ $data->pihak1_data->full_name }}</td>
                </tr>
                <tr>
                    <td style="width: 2%">&nbsp;</td>
                    <td style="width: 20%">Jabatan</td>
                    <td style="width: 3%">:</td>
                    <td style="width: 75%">{{ $data->pihak1_jabatan }}</td>
                </tr>
                <tr>
                    <td style="width: 2%">&nbsp;</td>
                    <td style="width: 98%; text-align: justify;" colspan="3">
                        {{-- Bertindak untuk dan atas nama <strong>PT WIJAYA KARYA BETON Tbk.</strong>, suatu badan hukum yang didirikan berdasarkan Akta No. 44 tanggal 11 Maret 1997, yang dibuat di hadapan Achmad Bajumi, S.H., pengganti dari Imas Fatimah, S.H., Notaris di Jakarta, yang telah beberapa kali diubah dan terakhir diubah dengan Akta Perubahan Anggaran Dasar No 09 tanggal 08 Juni 2023, dibuat dihadapan Ir Nanette Cahyanie Handari Adi Warsito, S.H., Notaris di Jakarta dan telah mendapat persetujuan dari Menteri Hukum dan Hak Asasi Manusia Republik Indonesia No. AHU-0032467.AH.01.02.TAHUN 2023 tanggal 12 Juni 2023, beralamat di Jl. D.I. Panjaitan Kav. 9 Jakarta Timur 13340, (selanjutnya disebut sebagai <strong>“PIHAK PERTAMA”</strong>). --}}
                        {!! $data->pihak1_ket !!}
                    </td>
                </tr>
                <tr>
                    <td style="width: 2%">II.</td>
                    <td style="width: 20%">Nama</td>
                    <td style="width: 3%">:</td>
                    <td style="width: 75%">{{ $data->pihak2 }}</td>
                </tr>
                <tr>
                    <td style="width: 2%">&nbsp;</td>
                    <td style="width: 20%">Jabatan</td>
                    <td style="width: 3%">:</td>
                    <td style="width: 75%">{{ $data->pihak2_jabatan }}</td>
                </tr>
                <tr>
                    <td style="width: 2%">&nbsp;</td>
                    <td style="width: 98%; text-align: justify;" colspan="3">
                        {{-- Suatu [Jenis Perseroan] yang tunduk pada hukum Negara Republik Indonesia, berkedudukan di {{ !empty($data->vendor->kota)?ucwords(strtolower($data->vendor->kota)):"" }} dan beralamat di {{ !empty($data->vendor->kota)?ucwords(strtolower($data->vendor->alamat)):"" }} didirikan berdasarkan [akta pendirian beserta SK Kemenkumham] yang telah beberapa kali diubah dan terakhir kali diubah dengan [akta perubahan anggaran dasar-(jika ada) beserta SK Kemenkumham], dalam hal ini diwakili oleh {{ $data->pihak2 }} selaku {{ $data->pihak2_jabatan }} {{ $data->vendor->nama }}. Selanjutnya dalam Perjanjian disebut <strong>"PIHAK KEDUA"</strong>. --}}
                        {!! $data->pihak2_ket !!}
                    </td>
                </tr>
            </table>
            <br>
            <strong>PIHAK PERTAMA</strong> dan <strong>PIHAK KEDUA</strong> selanjutnya secara masing-masing disebut sebagai <strong>"PIHAK"</strong> dan secara bersama-sama disebut sebagai <strong>“PARA PIHAK”</strong>.<br><br>
            <strong>PARA PIHAK</strong> menerangkan terlebih dahulu bahwa <strong>PARA PIHAK</strong> telah membuat dan menandatangani Berita Acara Negosiasi {{$data->no_ban}} {{fullDateHumanizeId($data->tgl_ban)}} tentang {{ $data->jenisPekerjaan->ket }} untuk {{ $npp->nama_proyek }}<br><br>
            Sehubungan dengan hal tersebut diatas, <strong>PARA PIHAK</strong> sepakat untuk membuat Perjanjian Kerja, yang selanjutnya disebut “Perjanjian”, dengan ketentuan-ketentuan dan syarat-syarat sebagai berikut:
        </p>
		<br><br>

		@foreach ($data->spk_pasal->sortBy('pasal') as $pasal)
            <p style="text-align: center; font-weight: bold;">
                PASAL {{$pasal->pasal}} <br>
				{{$pasal->judul}}
            </p>
            @php
                $keterangan = $pasal->keterangan;
                foreach ($modify_params as $param => $value) {
                    $keterangan = str_replace(htmlentities($param), $value, $keterangan);
                }
            @endphp
			{!! $keterangan !!}
			<br><br>
		@endforeach
	</main>
</body>
</html>
