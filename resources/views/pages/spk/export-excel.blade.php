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
</style>
<table cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="15" style="text-align: right;font-size: 11px;">Lampiran B.9</td>
    </tr>
    <tr>
        <td colspan="11" style="text-align: right">
            &nbsp;
        </td>
        <td colspan="2" style="text-align: left;border: 1px solid #000000;padding-left: 5px;padding-right: 10px;font-size: 11px;">
            Form : WB-SCM-PS-02-
        </td>
        <td colspan="2" style="text-align: right;border: 1px solid #000000;padding-right: 5px;font-size: 11px;">
            Rev
        </td>
    </tr>
    <tr>
        <td colspan="15" style="text-align:center;vertical-align: middle; font-weight: bold;font-size: 12px;">SURAT PERJANJIAN PELAKSANAAN KERJA</td>
    </tr>
    <tr>
        <td colspan="15" style="text-align:center;vertical-align: middle; font-weight: bold;font-size: 12px;">ANTARA</td>
    </tr>
    <tr>
        <td colspan="15" style="text-align:center;vertical-align: middle; font-weight: bold;font-size: 12px;">PT WIJAYA KARYA BETON TBK</td>
    </tr>
    <tr>
        <td colspan="15" style="text-align:center;vertical-align: middle; font-weight: bold;font-size: 12px;">DENGAN</td>
    </tr>
    <tr>
        <td colspan="15" style="text-align: center; vertical-align: middle;font-size: 12px;font-weight: bold;">{{ $data->vendor->nama }}</td>
    </tr>
    <tr>
        <td colspan="15" style="text-align: center; vertical-align: middle;font-size: 12px;font-weight: bold;">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="15" style="text-align: center; vertical-align: middle;font-size: 12px;font-weight: bold;">Perihal</td>
    </tr>
    <tr>
        <td colspan="15" style="text-align: center; vertical-align: middle;font-size: 12px;font-weight: bold;">
            {{ $data->jenisPekerjaan->ket }} untuk {{ $npp->nama_proyek }}
        </td>
    </tr>
    <tr>
        <td colspan="15" style="text-align: center; vertical-align: middle;font-size: 12px;">{{ $data->no_spk }}</td>
    </tr>
    <tr>
        <td colspan="15" >&nbsp;</td>
    </tr>
    <tr>
        <td colspan="15" style="vertical-align: top;height: 50px;">
            Pada hari ini, {{ getDay($data->tgl_spk) }} tanggal {{fullDateHumanizeId($data->tgl_spk)}},  yang bertanda tangan dibawah ini  :

        </td>
    </tr>

    <tr>
        <td style="vertical-align: top;">I.</td>
        <td colspan="5" style="font-weight:bold;vertical-align: top;">PT. Wijaya Karya Beton Tbk</td>
        <td style="vertical-align: top;">:</td>
        <td colspan="8" style="height: 230px;vertical-align: top;">
            Suatu Perseroan Terbatas yang tunduk pada hukum  Negara
            Republik Indonesia, <br>berkedudukan di Jakarta Timur dan 
            beralamat di Gedung WIKA Tower 1, Jln. D.I. Panjaitan Kav. 9, 
            Jati Negara, Jakarta Timur, Indonesia, 13340,  didirikan berdasarkan Hukum Negara Republik Indonesia, berdasarkan Anggaran Dasar PT Wijaya Karya Beton Tbk., No. 44 tertanggal 11 Maret 1997, yang dibuat dihadapan Achmad Bajumi, S.H., pengganti dari Imas Fatimah, S.H., Notaris di Jakarta, yang telah beberapa kali diubah dan terakhir kali diubah dengan Akta Perubahan Anggaran Dasar No. 09 tanggal 08 Juni 2023 dibuat dihadapan Ir. Nanette Cahyanie Handari Adi Warsito S.H., Notaris di Jakarta Selatan dan telah memperoleh persetujuan Kementerian Hukum dan HAM RI No. AHU-0032467.AH.01.02.TAHUN 2023 tanggal 12 Juni 2023, dalam hal ini diwakili oleh selaku  PT. Wijaya Karya Beton Tbk, bertindak untuk dan atas nama PT Wijaya Karya Beton Tbk. Selanjutnya dalam <strong>Perjanjian</strong> ini disebut “PIHAK KESATU”

        </td>
    </tr>

</table>