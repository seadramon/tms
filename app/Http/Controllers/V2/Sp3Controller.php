<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ban;
use App\Models\JenisPekerjaan;
use App\Models\Kontrak;
use App\Models\MonOp;
use App\Models\MsNoDokumen;
use App\Models\Npp;
use App\Models\Pat;
use App\Models\Pelabuhan;
use App\Models\Personal;
use App\Models\Produk;
use App\Models\Region;
use App\Models\Sp3;
use App\Models\Sp3D;
use App\Models\Sp3D2;
use App\Models\Sp3Dokumen;
use App\Models\Sp3Pic;
use App\Models\SptbD;
use App\Models\TrMaterial;
use App\Models\Vendor;
use App\Models\Sbu;
use App\Models\Views\VSpprbRi;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class Sp3Controller extends Controller
{
    public function create(Request $request)
    {
        $vendor = Vendor::where('sync_eproc', 1)
            ->where('vendor_id', 'like', 'WB%')
            ->get()
            ->pluck('nama', 'vendor_id')
            ->toArray();

        $vendor = ["" => "Pilih Vendor"] + $vendor;

        $jenisPekerjaan = ["darat" => "Angkutan Darat", "laut" => "Angkutan Laut"];

        $jenisPekerjaan = ["" => "Pilih Pekerjaan"] + $jenisPekerjaan;

        $sat_harsat = ["tonase" => "Tonase | Batang", "ritase" => "Ritase"];
        $npp = Npp::find($request->npp);

        return view('pages.sp3.v2.create', [
            'vendor' => $vendor,
            'jenisPekerjaan' => $jenisPekerjaan,
            'sat_harsat' => $sat_harsat,
            'npp' => $npp,
            'vendor_id' => $request->vendor_id ?? null,
        ]);
    }

    public function getDataBox2(Request $request)
    {
        $code = 200;
        try {
            $parameters = $request->all();

            $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
                ->where('no_npp', $parameters['no_npp'])
                ->get();

            $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();

            $sp3D = Sp3D::whereNoNpp($parameters['no_npp'])
                ->whereIn('kd_produk', $kd_produks)
                ->get()
                ->sortByDesc('no_sp3')
                ->groupBy([
                    'kd_produk', function ($item) {
                        return substr($item->no_sp3, 0, -3);
                    }
                ], true);

            $npp = Npp::with(['infoPasar.region'])
                ->where('no_npp', $parameters['no_npp'])
                ->first();

            $ban = Ban::get()->pluck('no_ban', 'no_ban')->toArray();
            $ban = ["" => "---Pilih---"] + $ban;

            $kontrak = Kontrak::get()->pluck('no_kontrak', 'no_kontrak')->toArray();
            $kontrak = ["" => "---Pilih---"] + $kontrak;

            $vendor = Vendor::where('vendor_id', $parameters['vendor_id'])->first();
            $trader = DB::connection('oracle-eproc')
                        ->table(DB::raw('"m_trader"'))
                        ->where('vendor_id', $parameters['vendor_id'])
                        ->first();

            $kondisiPenyerahan = [
                'L' => 'LOKO',
                'F' => 'FRANKO',
                'T' => 'TERPASANG',
                'D' => 'DISPENSASI'
            ];

            $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($parameters['no_npp'], -1))];

            $VSpprbRi = VSpprbRi::where('no_npp', $parameters['no_npp'])->first();

            if($VSpprbRi){
                $jarak = Sp3D::where('pat_to', $VSpprbRi->pat_to)
                    ->where('no_npp', $VSpprbRi->no_npp)
                    ->max('jarak_km');
            }else{
                $jarak = 0;
            }

            $unit = Pat::where('kd_pat', 'LIKE', '2%')
                ->orWhere('kd_pat', 'LIKE', '4%')
                ->orWhere('kd_pat', 'LIKE', '5%')
                ->get()
                ->pluck('ket', 'kd_pat')
                ->toArray();

            $unit = ["" => "Pilih Unit"] + $unit;

            $satuan = [
                "" => "Pilih",
                "btg" => "BTG",
                "ton" => "TON",
            ];

            $ppn = [
                "0" => "0%",
                "11" => "11%",
            ];
            $pph = DB::table('tb_pph_d')->leftJoin('tb_pph_h', 'tb_pph_d.pph_id', '=', 'tb_pph_h.pph_id')
                ->select('tb_pph_d.pph_id', 'tb_pph_d.ket', 'tb_pph_h.pph_nama','tb_pph_d.value')
                ->get()
                ->sortBy(['pph_id', 'value'])
                ->mapWithKeys(function($item){
                    return [$item->pph_id . '|' . $item->value => $item->pph_nama . ' [' . $item->value . '%]'];
                })
                ->all();

            $pph = ["0|0" => "0%"] + $pph;

            $kd_material = TrMaterial::where('kd_jmaterial', 'T')
                ->get()
                ->pluck('name', 'kd_material')->toArray();
            $kd_material = ["" => "---Pilih---"] + $kd_material;

            $sumber_daya = [
                "DTD" => "Angkutan Laut Door to Door",
                "DTP" => "Angkutan Laut Door to Port",
                "PTP" => "Angkutan Laut Port to Port"
            ];

            $pelabuhan = Pelabuhan::all()
                ->mapWithKeys(function($item){
                    return [$item->nama => $item->nama];
                })
                ->all();
            $pelabuhan = ["" => "---Pilih---"] + $pelabuhan;

            $site = Region::select('kecamatan_name')
                ->groupBy('kecamatan_name')
                ->get()
                ->mapWithKeys(function($item){
                    return [$item->kecamatan_name => $item->kecamatan_name];
                })
                ->all();
            $site = ["" => "---Pilih---"] + $site;

            $html = view('pages.sp3.v2.box2', [
                'detailPesanan' => $detailPesanan,
                'npp' => $npp,
                'ban' => $ban,
                'kontrak' => $kontrak,
                'vendor' => $vendor,
                'trader' => $trader,
                'kondisiPenyerahan' => $kondisiPenyerahan,
                'kondisiPenyerahanDipilih' => $kondisiPenyerahanDipilih,
                'VSpprbRi' => $VSpprbRi,
                'jarak' => $jarak,
                'unit' => $unit,
                'satuan' => $satuan,
                'ppn' => $ppn,
                'pph' => $pph,
                'sp3D' => $sp3D,
                'sat_harsat' => $request->sat_harsat,
                'kd_material' => $kd_material,
                'sumber_daya' => $sumber_daya,
                'pelabuhan' => $pelabuhan,
                'site' => $site,
            ])->render();
            $result = array('success' => true, 'html'=> $html);

        } catch(Exception $e) {
            $code = 400;
            $result = array('success' => false, 'message'=> $e->getMessage());
        }

        return response()->json($result, $code);
    }
}
