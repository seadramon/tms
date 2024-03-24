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
        <table width="100%" style="margin-top: 10px;margin-bottom: 5px;">
            <tr>
                <td width="50%" valign="top">
                    PT WIJAYA KARYA BETON<br>
                    {{ 'PAT PPB' }}
                </td>
                <td width="50%">
                    <table cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <td colspan="3" style="text-align: right;">
                                Lampiran B.41
                            </td>
                        </tr>
            		    <tr>
                            <td style="width: 55%;"></td>
            		        <td style="width: 35%;text-align: center;border: 1px solid #000000;padding-left: 5px;padding-right: 10px;">
            		            Form : WB-SCM-PS-01-F41
            		        </td>
            		        <td style="width: 10%;text-align: center;border: 1px solid #000000;padding-right: 5px;">
            		            Rev : 00
            		        </td>
            		    </tr>
                    </table>
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
        <p style="text-align: center; font-size: 13px;">
            <b><u>BERITA ACARA PEMERIKSAAN PEKERJAAN</u><br></b>
            Nomor : {{ $data->no_bapp }}
        </p>
        
        <p>
            Pada hari ini {{ getDay($data->tgl_bapp) }} tanggal {{fullDateHumanizeId($data->tgl_bapp)}} Pelaksana PT. Wijaya Karya Beton Tbk. telah melaksanakan Pemeriksaan Pekerjaan sesuai dengan Surat Perintah Pelaksanaan Pekerjaan Nomor : <b>{{ $data->no_sp3 }}</b> yang bertanda tangan :
        </p>
        <table cellspacing="0" cellpadding="0" width="100%" style="font-size: 12px;">
            <tr>
                <td width="2%">1.</td>
                <td width="16%">Nama</td>
                <td width="2%">:</td>
                <td width="80%">
                    {{ !empty($data->pihak1)?$data->pihak1_data->full_name:"" }}
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Jabatan</td>
                <td>:</td>
                <td>
                    {{ !empty($data->pihak1)?$data->pihak1_data->jabatan->ket:"" }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div style="padding-top: 10px;padding-bottom: 10px;">Bertindak dan atas nama <b>PT. WIJAYA KARYA BETON</b>, yang selanjutnya disebut <b>PIHAK PERTAMA</b></div>
                </td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Nama</td>
                <td>:</td>
                <td>
                    {{ !empty($data->pihak2)?$data->pihak2:"" }}
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Jabatan</td>
                <td>:</td>
                <td>
                    {{ !empty($data->pihak2_jabatan)?$data->pihak2_jabatan:"" }}
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div style="padding-top: 10px;padding-bottom: 10px;">Bertindak atas nama <b>{{ !empty($data->vendor_id)?$data->vendor->nama:"" }}</b>, yang selanjutnya disebut <b>PIHAK KEDUA</b><br>
                        Kedua Belah Pihak secara bersama-sama telah mengadakan Opname Pekerjaan sebagai berikut :
                    </div>
                </td>
            </tr>
        </table>
	</main>
</body>
</html>
