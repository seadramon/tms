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
            'data' => null,
            'mode' => "create",
            'jenis_angkutan' => null
        ]);
    }

    public function getDataBox2(Request $request)
    {
        $code = 200;
        // try {
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

            $spesifikasi = [
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

            if(in_array($request->mode, ['edit', 'show'])){
                $sp3 = Sp3::with('pic.employee', 'sp3D.produk', 'dokumen')->find($request->sp3);
            }else{
                $sp3 = null;
            }

            $documents = [
                '1' => 'Faktur / Invoice / Kwitansi',
                '2' => 'Packing List',
                '3' => 'Faktur Pajak',
                '4' => 'BAPB',
                '5' => 'SP3 / SPK',
                '6' => 'BA Pemeriksaan / Opname',
                '7' => 'Surat Jalan / SPtB',
                '8' => 'BA Pembayaran',
                '9' => 'Rekap Surat Jalan / SPtB',
                '10' => 'Lembar Kendali Pembayaran'
            ];

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
                'pekerjaan' => $request->kd_jpekerjaan ?? 'darat',
                'kd_material' => $kd_material,
                'spesifikasi' => $spesifikasi,
                'documents' => $documents,
                'pelabuhan' => $pelabuhan,
                'site' => $site,
                'sp3' => $sp3,
                'mode' => $request->mode
            ])->render();
            $result = array('success' => true, 'html'=> $html);

        // } catch(Exception $e) {
        //     $code = 400;
        //     $result = array('success' => false, 'message'=> $e->getMessage());
        // }

        return response()->json($result, $code);
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        // return response()->json($request->all());
        try {
            DB::beginTransaction();

            Validator::make($request->all(), [
                'no_npp'        => 'required',
                'vendor_id'     => 'required',
                'kd_jpekerjaan' => 'required',
            ])->validate();

            $vendor = Vendor::find($request->vendor_id);

            $noDokumen = 'TP.02.01/WB-' . (session('TMP_KDWIL') ?? '1A');

            $msNoDokumen = MsNoDokumen::where('tahun', date('Y'))->where('no_dokumen', $noDokumen);

            if($msNoDokumen->exists()){
                $msNoDokumen = $msNoDokumen->first();

                $newSequence = sprintf('%04s', ((int)$msNoDokumen->seq + 1));

                $msNoDokumen->update([
                    'seq'           =>  $newSequence,
                    'updated_by'    => session('TMP_NIP') ?? '12345',
                    'updated_date'  => date('Y-m-d H:i:s'),
                ]);
            }else{
                $newSequence = '0001';

                $msNoDokumenData = new MsNoDokumen();
                $msNoDokumenData->tahun = date('Y');
                $msNoDokumenData->no_dokumen = $noDokumen;
                $msNoDokumenData->seq = $newSequence;
                $msNoDokumenData->created_by = session('TMP_NIP') ?? '12345';
                $msNoDokumenData->created_date = date('Y-m-d H:i:s');
                $msNoDokumenData->save();
            }

            $noSp3 = $noDokumen . '.' . $newSequence . '/' . date('Y') . 'P00';
            $pph = explode('|', $request->pph);
            $data_ = [];

            $sp3 = new Sp3;
            $sp3->no_sp3 = $noSp3;
            $sp3->no_npp = $request->no_npp;
            $sp3->vendor_id = $vendor->vendor_id;
            $sp3->alamat_vendor = $vendor->alamat;
            $sp3->satuan_harsat = $request->sat_harsat;
            $sp3->tgl_sp3 = date('Y-m-d', strtotime($request->tgl_sp3));
            $sp3->no_ban = $request->no_ban;
            $sp3->no_kontrak_induk = $request->no_kontrak_induk;
            $sp3->jadwal1 = date('Y-m-d', strtotime($request->jadwal1));
            $sp3->jadwal2 = date('Y-m-d', strtotime($request->jadwal2));
            $sp3->rit = $request->rit;
            $sp3->jarak_km = $request->jarak_pesanan;
            $sp3->ppn = $request->ppn ? (float)($request->ppn / 100) : 0;
            $sp3->pph = $pph[1];
            $sp3->pph_id = $pph[0];
            $sp3->keterangan = $request->keterangan;
            $sp3->kd_material = $request->kd_material;
            $sp3->created_by = session('TMP_NIP') ?? '12345';
            $sp3->created_date = date('Y-m-d H:i:s');
            $sp3->kd_pat = session('TMP_KDWIL') ?? '1A';
            if($request->kd_jpekerjaan == 'laut'){
                $sp3->kd_jpekerjaan = '20';
                $sp3->spesifikasi = $request->spesifikasi;
                
                $harga_include = collect(json_decode($request->harga_include))->map(function($item){ return $item->value; })->all();
                $data_['harga_include'] = $harga_include;
            }else{
                $sp3->kd_jpekerjaan = '01';
            }
            if(strtolower($request->sat_harsat) == 'ritase'){
                $data_['harga_satuan_ritase'] = $request->harga_satuan_ritase;
            }
            $sp3->data = $data_;
            $sp3->save();

            foreach($request->pic as $pic){
                $sp3Pic = new Sp3Pic();
                $sp3Pic->no_sp3 = $noSp3;
                $sp3Pic->employee_id = $pic;
                $sp3Pic->save();
            }

            for($i=0; $i < count($request->unit); $i++){
                $sp3D = new Sp3D();
                $sp3D->no_sp3 = $noSp3;
                $sp3D->no_npp = $request->no_npp;
                $sp3D->pat_to = $request->unit[$i];
                $sp3D->kd_produk = $request->tipe[$i];
                $sp3D->jarak_km = str_replace(',', '', $request->jarak[$i]);
                $sp3D->vol_awal = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_akhir = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_ton_awal = str_replace(',', '', $request->vol_ton[$i]);
                $sp3D->vol_ton_akhir = str_replace(',', '', $request->vol_ton[$i]);

                if(strtolower($request->sat_harsat) == 'tonase'){
                    $sp3D->sat_harsat = $request->satuan[$i];
                }else{
                    $sp3D->ritase = $request->ritase[$i] ?? null;
                }
                if($request->kd_jpekerjaan == 'laut'){
                    $sp3D->port_asal = $request->pelabuhan_asal[$i] ?? null;
                    $sp3D->port_tujuan = $request->pelabuhan_tujuan[$i] ?? null;
                    $sp3D->site = $request->site[$i] ?? null;
                    $sp3D->site = $request->site[$i] ?? null;
                    $sp3D->total = str_replace(',', '', $request->jumlah[$i]);
                }

                $sp3D->harsat_awal = str_replace(',', '', $request->harsat[$i]);
                $sp3D->harsat_akhir = str_replace(',', '', $request->harsat[$i]);
                $sp3D->save();
            }

            $sp3D2Id = Sp3D2::max('id') ?? 0;

            foreach(($request->material_tambahan ?? []) as $material){
                $sp3D2Id++;

                $sp3D2 = new Sp3D2();
                $sp3D2->id = $sp3D2Id;
                $sp3D2->no_sp3 = $noSp3;
                $sp3D2->material = $material['material'];
                $sp3D2->spesifikasi = $material['spesifikasi'];
                $sp3D2->volume = $material['volume'];
                $sp3D2->save();
            }

            foreach ($request->dokumen_asli as $key => $item) {
                $sp3Dokumen = new Sp3Dokumen();
    
                $sp3Dokumen->no_sp3 = $noSp3;
                $sp3Dokumen->dok_id = $key;
                $sp3Dokumen->asli = $item;
                $sp3Dokumen->copy = $request->dokumen_copy[$key];
                $sp3Dokumen->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('sp3.index');
    }

    public function show($noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);

        $data = Sp3::find($noSp3);

        $vendor = Vendor::where('sync_eproc', 1)
            ->where('vendor_id', 'like', 'WB%')
            ->get()
            ->pluck('nama', 'vendor_id')
            ->toArray();

        $vendor = ["" => "Pilih Vendor"] + $vendor;

        $jenisPekerjaan = ["darat" => "Angkutan Darat", "laut" => "Angkutan Laut"];

        $jenisPekerjaan = ["" => "Pilih Pekerjaan"] + $jenisPekerjaan;

        $sat_harsat = ["tonase" => "Tonase | Batang", "ritase" => "Ritase"];
        $jenis_angkutan = $data->kd_jpekerjaan == '20' ? 'laut' : 'darat';
        $npp = Npp::find($data->no_npp);
        $amandemen = str_contains(request()->url(), 'amandemen');

        return view('pages.sp3.v2.create', [
            'vendor' => $vendor,
            'jenisPekerjaan' => $jenisPekerjaan,
            'sat_harsat' => $sat_harsat,
            'npp' => $npp,
            'vendor_id' => $data->vendor_id ?? null,
            'mode' => "show",
            'data' => $data,
            'amandemen' => $amandemen,
            'jenis_angkutan' => $jenis_angkutan
        ]);
    }

    public function edit($noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);

        $data = Sp3::find($noSp3);

        $vendor = Vendor::where('sync_eproc', 1)
            ->where('vendor_id', 'like', 'WB%')
            ->get()
            ->pluck('nama', 'vendor_id')
            ->toArray();

        $vendor = ["" => "Pilih Vendor"] + $vendor;

        $jenisPekerjaan = ["darat" => "Angkutan Darat", "laut" => "Angkutan Laut"];

        $jenisPekerjaan = ["" => "Pilih Pekerjaan"] + $jenisPekerjaan;

        $sat_harsat = ["tonase" => "Tonase | Batang", "ritase" => "Ritase"];
        $jenis_angkutan = $data->kd_jpekerjaan == '20' ? 'laut' : 'darat';
        $npp = Npp::find($data->no_npp);
        $amandemen = str_contains(request()->url(), 'amandemen');

        return view('pages.sp3.v2.create', [
            'vendor' => $vendor,
            'jenisPekerjaan' => $jenisPekerjaan,
            'sat_harsat' => $sat_harsat,
            'npp' => $npp,
            'vendor_id' => $data->vendor_id ?? null,
            'mode' => "edit",
            'data' => $data,
            'amandemen' => $amandemen,
            'jenis_angkutan' => $jenis_angkutan
        ]);
    }

    public function update(Request $request, FlasherInterface $flasher, $noSp3){
        // return response()->json($request->all());
        DB::beginTransaction();
        try {

            $noSp3 = str_replace('|', '/', $noSp3);

            if($request->amandemen){
                $noSp3Sequence = sprintf('%02s', ((int)substr($noSp3, -2))+1);

                $newNoSp3 = str_replace(substr($noSp3, -2), $noSp3Sequence, $noSp3);

                $sp3 = new Sp3();
            }else{
                $newNoSp3 = $noSp3;

                $sp3 = Sp3::find($noSp3);

                //Delete child data
                Sp3Pic::where('no_sp3', $noSp3)->delete();
                Sp3D::where('no_sp3', $noSp3)->delete();
                Sp3D2::where('no_sp3', $noSp3)->delete();
                Sp3Dokumen::where('no_sp3', $noSp3)->delete();
            }

            $vendor = Vendor::find($request->vendor_id);

            $pph = explode('|', $request->pph);

            //Update or Create Sp3
            $sp3->no_sp3 = $noSp3;
            $sp3->no_npp = $request->no_npp;
            $sp3->vendor_id = $vendor->vendor_id;
            $sp3->alamat_vendor = $vendor->alamat;
            $sp3->satuan_harsat = $request->sat_harsat;
            $sp3->tgl_sp3 = date('Y-m-d', strtotime($request->tgl_sp3));
            $sp3->no_ban = $request->no_ban;
            $sp3->no_kontrak_induk = $request->no_kontrak_induk;
            $sp3->jadwal1 = date('Y-m-d', strtotime($request->jadwal1));
            $sp3->jadwal2 = date('Y-m-d', strtotime($request->jadwal2));
            $sp3->rit = $request->rit;
            $sp3->jarak_km = $request->jarak_pesanan;
            $sp3->ppn = $request->ppn ? (float)($request->ppn / 100) : 0;
            $sp3->pph = $pph[1];
            $sp3->pph_id = $pph[0];
            $sp3->keterangan = $request->keterangan;
            $sp3->kd_material = $request->kd_material;
            $sp3->created_by = session('TMP_NIP') ?? '12345';
            $sp3->created_date = date('Y-m-d H:i:s');
            $sp3->kd_pat = session('TMP_KDWIL') ?? '1A';
            $data_ = $sp3->data;
            if($request->kd_jpekerjaan == 'laut'){
                $sp3->kd_jpekerjaan = '20';
                $sp3->spesifikasi = $request->spesifikasi;
                
                $harga_include = collect(json_decode($request->harga_include))->map(function($item){ return $item->value; })->all();
               
                $data_['harga_include'] = $harga_include;
                
            }else{
                $sp3->kd_jpekerjaan = '01';
            }
            if(strtolower($request->sat_harsat) == 'ritase'){
                $data_['harga_satuan_ritase'] = $request->harga_satuan_ritase;
            }
            $sp3->data = $data_;
            $sp3->save();

            foreach($request->pic as $pic){
                $sp3Pic = new Sp3Pic();
                $sp3Pic->no_sp3 = $noSp3;
                $sp3Pic->employee_id = $pic;
                $sp3Pic->save();
            }

            for($i=0; $i < count($request->unit); $i++){
                $sp3D = new Sp3D();
                $sp3D->no_sp3 = $noSp3;
                $sp3D->no_npp = $request->no_npp;
                $sp3D->pat_to = $request->unit[$i];
                $sp3D->kd_produk = $request->tipe[$i];
                $sp3D->jarak_km = str_replace(',', '', $request->jarak[$i]);
                $sp3D->vol_awal = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_akhir = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_ton_awal = str_replace(',', '', $request->vol_ton[$i]);
                $sp3D->vol_ton_akhir = str_replace(',', '', $request->vol_ton[$i]);

                if(strtolower($request->sat_harsat) == 'tonase'){
                    $sp3D->sat_harsat = $request->satuan[$i];
                }else{
                    $sp3D->ritase = $request->ritase[$i] ?? null;
                }
                if($request->kd_jpekerjaan == 'laut'){
                    $sp3D->port_asal = $request->pelabuhan_asal[$i] ?? null;
                    $sp3D->port_tujuan = $request->pelabuhan_tujuan[$i] ?? null;
                    $sp3D->site = $request->site[$i] ?? null;
                    $sp3D->site = $request->site[$i] ?? null;
                    $sp3D->total = str_replace(',', '', $request->jumlah[$i]);
                }

                $sp3D->harsat_awal = str_replace(',', '', $request->harsat[$i]);
                $sp3D->harsat_akhir = str_replace(',', '', $request->harsat[$i]);
                $sp3D->save();
            }

            $sp3D2Id = Sp3D2::max('id') ?? 0;

            foreach(($request->material_tambahan ?? []) as $material){
                $sp3D2Id++;

                $sp3D2 = new Sp3D2();
                $sp3D2->id = $sp3D2Id;
                $sp3D2->no_sp3 = $noSp3;
                $sp3D2->material = $material['material'];
                $sp3D2->spesifikasi = $material['spesifikasi'];
                $sp3D2->volume = $material['volume'];
                $sp3D2->save();
            }

            foreach ($request->dokumen_asli as $key => $item) {
                $sp3Dokumen = new Sp3Dokumen();
    
                $sp3Dokumen->no_sp3 = $noSp3;
                $sp3Dokumen->dok_id = $key;
                $sp3Dokumen->asli = $item;
                $sp3Dokumen->copy = $request->dokumen_copy[$key];
                $sp3Dokumen->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();

            $flasher->addError($e->getMessage());

            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('sp3.index');
    }   
}
