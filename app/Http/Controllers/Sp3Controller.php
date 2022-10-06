<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ban;
use App\Models\JenisPekerjaan;
use App\Models\Kontrak;
use App\Models\MonOp;
use App\Models\MsNoDokumen;
use App\Models\Npp;
use App\Models\Pat;
use App\Models\Personal;
use App\Models\Produk;
use App\Models\Sp3;
use App\Models\Sp3D;
use App\Models\Sp3D2;
use App\Models\Sp3Pic;
use App\Models\Vendor;
use App\Models\Views\VSpprbRi;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Sp3Controller extends Controller
{
    public function index(){
        $labelSemua = ["" => "Semua"];

        $pat = Pat::all()->pluck('ket', 'kd_pat')->toArray();
        $pat = $labelSemua + $pat;

        $periode = [];

        for($i=0; $i<10; $i++){
            $year = date('Y', strtotime('-' . $i . ' years'));
            $periode[$year] = $year;
        }

        $status = [
            'draft'             => 'Draft',
            'belum_verifikasi'  => 'Belum Verifikasi',
            'aktif'             => 'Aktif',
            'selesai'           => 'Selesai'
        ];

        $rangeCutOff = [
            'sd'    => 's/d',
            'di'    => '='
        ];

        $monthCutOff = [
            'januari'   => 'Januari',
            'februari'  => 'Februari',
            'maret'     => 'Maret',
            'april'     => 'April',
            'mei'       => 'Mei',
            'juni'      => 'Juni',
            'juli'      => 'Juli',
            'agustus'   => 'Agustus',
            'september' => 'September',
            'oktober'   => 'Oktober',
            'november'  => 'November',
            'desember'  => 'Desember'
        ];

        return view('pages.sp3.index', compact(
            'pat', 'periode', 'status', 'rangeCutOff', 'monthCutOff'
        ));
    }

    public function data()
    {
        $query = Sp3::with('vendor')->select('*');

        return DataTables::eloquent($query)
                ->editColumn('tgl_sp3', function ($model) {
                    return date('d-m-Y', strtotime($model->tgl_sp3));
                })
                ->addColumn('menu', function ($model) {
                    $edit = '<div class="btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">View</a></li>
                                <li><a class="dropdown-item" href="' . route('sp3.edit', str_replace('/', '|', $model->no_sp3)) . '">Edit</a></li>
                                <li><a class="dropdown-item" href="' . route('sp3.amandemen', str_replace('/', '|', $model->no_sp3)) . '">Amandemen</a></li>
                                <li><a class="dropdown-item" href="#">Adendum</a></li>
                                <li><a class="dropdown-item" href="' . route('sp3.get-approve', [!$model->app1 ? 'first' : 'second', str_replace('/', '|', $model->no_sp3)]) . '">Approve</a></li>
                                <li><a class="dropdown-item" href="#">Print</a></li>
                                <li><a class="dropdown-item" href="#">Hapus</a></li>
                            </ul>
                            </div>';

                    return $edit;
                })
                ->rawColumns(['menu'])
                ->toJson();
    }

    public function create()
    {
        $vendor = Vendor::where('sync_eproc', 1)
            ->where('vendor_id', 'like', 'WB%')
            ->get()
            ->pluck('nama', 'vendor_id')
            ->toArray();
            
        $vendor = ["" => "Pilih Vendor"] + $vendor;

        $jenisPekerjaan = JenisPekerjaan::get()
            ->pluck('ket', 'kd_jpekerjaan')
            ->toArray();
            
        $jenisPekerjaan = ["" => "Pilih Pekerjaan"] + $jenisPekerjaan;
        $sat_harsat = ["volume" => "Volume", "ritase" => "Ritase"];

        return view('pages.sp3.create', compact(
            'vendor', 'jenisPekerjaan', 'sat_harsat'
        ));
    }

    public function searchNpp(Request $request)
    {
        $query = Npp::where('no_npp', 'LIKE', '%' . $request->q . '%');

        if(session('TMP_KDWIL') != '0A'){
            $query->where('kd_pat', session('TMP_KDWIL') ?? '1A');
        }
        
        return $query->get();
    }

    public function searchPic(Request $request)
    {
        $personal = Personal::select('employee_id', 'first_name', 'last_name')
            ->where('ST', 1)
            ->where(DB::raw('LOWER(employee_id)'), 'LIKE', '%' . $request->q . '%')
            ->orWhere(DB::raw('LOWER(first_name)'), 'LIKE', '%' . $request->q . '%')
            ->orWhere(DB::raw('LOWER(last_name)'), 'LIKE', '%' . $request->q . '%');
        if(session('TMP_KDWIL') != '0A'){
            $personal->where('kd_pat', session('TMP_KDWIL') ?? '0A');
        }
        return $personal->get();
    }

    public function getDataBox2(Request $request)
    {
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

        $ban = Ban::where('pat_ban', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_ban', 'no_ban');

        $kontrak = Kontrak::where('pat_kontrak', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_kontrak', 'no_kontrak');

        $vendor = Vendor::where('vendor_id', $parameters['vendor_id'])->first();

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

        $html = view('pages.sp3.box2', [
            'detailPesanan' => $detailPesanan,
            'npp' => $npp,
            'ban' => $ban,
            'kontrak' => $kontrak,
            'vendor' => $vendor,
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
        ])->render();
        
        return response()->json( array('success' => true, 'html'=> $html) );
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
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

            $sp3 = new Sp3();
            $sp3->no_sp3 = $noSp3;
            $sp3->no_npp = $request->no_npp;
            $sp3->vendor_id = $vendor->vendor_id;
            $sp3->alamat_vendor = $vendor->alamat;
            $sp3->satuan_harsat = $request->sat_harsat;
            $sp3->kd_jpekerjaan = $request->kd_jpekerjaan;
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
            $sp3->created_by = session('TMP_NIP') ?? '12345';
            $sp3->created_date = date('Y-m-d H:i:s');
            $sp3->kd_pat = session('TMP_KDWIL') ?? '1A';
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
                $sp3D->kd_produk = $request->kd_produk[$i];
                $sp3D->jarak_km = str_replace(',', '', $request->jarak_pekerjaan[$i]);
                $sp3D->vol_awal = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_akhir = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_ton_awal = str_replace(',', '', $request->vol_ton[$i]);
                $sp3D->vol_ton_akhir = str_replace(',', '', $request->vol_ton[$i]);

                if($request->sat_harsat == 'volume'){
                    $sp3D->sat_harsat = $request->satuan[$i];
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

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();
            $flasher->addError($e->getMessage());
        }

        return redirect()->route('sp3.index');
    }

    public function edit($noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);

        $data = Sp3::find($noSp3);

        $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
            ->where('no_npp', $data->no_npp)
            ->get();

        $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();
        
        $sp3D = Sp3D::whereNoNpp($data->no_npp)
            ->whereIn('kd_produk', $kd_produks)
            ->get()
            ->sortByDesc('no_sp3')
            ->groupBy([
                'kd_produk', function ($item) {
                    return substr($item->no_sp3, 0, -3);
                }
            ], true);

        $npp = Npp::with(['infoPasar.region'])
            ->where('no_npp', $data->no_npp)
            ->first();

        $ban = Ban::where('pat_ban', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_ban', 'no_ban');

        $kontrak = Kontrak::where('pat_kontrak', session('TMP_KDWIL') ?? '0A')
            ->get()
            ->pluck('no_kontrak', 'no_kontrak');

        $vendor = Vendor::where('vendor_id', $data->vendor_id)->first();

        $kondisiPenyerahan = [
            'L' => 'LOKO', 
            'F' => 'FRANKO', 
            'T' => 'TERPASANG', 
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($data->no_npp, -1))];

        $VSpprbRi = VSpprbRi::where('no_npp', $data->no_npp)->first();

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

        $sat_harsat = $data->satuan_harsat;

        $listPic = Sp3Pic::with('employee')
            ->where('no_sp3', $noSp3)
            ->get();

        $detailPekerjaan = Sp3D::where('no_sp3', $noSp3)->get();

        $materialTambahan = Sp3D2::where('no_sp3', $noSp3)->get();

        $isAmandemen = str_contains(request()->url(), 'amandemen');

        return view('pages.sp3.edit', compact(
            'data',
            'detailPesanan',
            'npp',
            'ban',
            'kontrak',
            'vendor',
            'kondisiPenyerahan',
            'kondisiPenyerahanDipilih',
            'VSpprbRi',
            'jarak',
            'unit',
            'satuan',
            'ppn',
            'pph',
            'sp3D',
            'sat_harsat',
            'listPic',
            'detailPekerjaan',
            'materialTambahan',
            'isAmandemen'
        ));
    }

    public function update(Request $request, FlasherInterface $flasher, $noSp3){
        try {
            DB::beginTransaction();

            $noSp3 = str_replace('|', '/', $noSp3);
            
            if($request->isAmandemen){
                $noSp3Sequence = sprintf('%02s', ((int)substr($noSp3, -2))+1);

                $newNoSp3 = str_replace(substr($noSp3, -2), $noSp3Sequence, $noSp3);
            }else{
                $newNoSp3 = $noSp3;
            }
            
            $pph = explode('|', $request->pph);

            //Delete child data
            Sp3Pic::where('no_sp3', $noSp3)->delete();
            Sp3D::where('no_sp3', $noSp3)->delete();
            Sp3D2::where('no_sp3', $noSp3)->delete();

            //Update Sp3
            $sp3 = Sp3::find($noSp3);
            $sp3->no_sp3 = $newNoSp3;
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
            $sp3->created_by = session('TMP_NIP') ?? '12345';
            $sp3->created_date = date('Y-m-d H:i:s');
            $sp3->kd_pat = session('TMP_KDWIL') ?? '1A';
            $sp3->save();

            foreach($request->pic as $pic){
                $sp3Pic = new Sp3Pic();
                $sp3Pic->no_sp3 = $newNoSp3;
                $sp3Pic->employee_id = $pic;
                $sp3Pic->save();
            }

            for($i=0; $i < count($request->unit); $i++){
                $sp3D = new Sp3D();
                $sp3D->no_sp3 = $newNoSp3;
                $sp3D->no_npp = $sp3->no_npp;
                $sp3D->pat_to = $request->unit[$i];
                $sp3D->kd_produk = $request->kd_produk[$i];
                $sp3D->jarak_km = str_replace(',', '', $request->jarak_pekerjaan[$i]);
                $sp3D->vol_awal = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_akhir = str_replace(',', '', $request->vol_btg[$i]);
                $sp3D->vol_ton_awal = str_replace(',', '', $request->vol_ton[$i]);
                $sp3D->vol_ton_akhir = str_replace(',', '', $request->vol_ton[$i]);

                if($request->sat_harsat == 'volume'){
                    $sp3D->sat_harsat = $request->satuan[$i];
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
                $sp3D2->no_sp3 = $newNoSp3;
                $sp3D2->material = $material['material'];
                $sp3D2->spesifikasi = $material['spesifikasi'];
                $sp3D2->volume = $material['volume'];
                $sp3D2->save();
            }

            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();
            $flasher->addError($e->getMessage());
        }

        return $request->isAmandemen 
            ? redirect()->route('sp3.amandemen', str_replace('/', '|', $newNoSp3))
            : redirect()->route('sp3.edit', str_replace('/', '|', $newNoSp3));
    }

    public function showApprove($type, $noSp3)
    {
        $noSp3 = str_replace('|', '/', $noSp3);
        $data = Sp3::find($noSp3);

        $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
            ->where('no_npp', $data->no_npp)
            ->get();

        $kd_produks = $detailPesanan->map(function ($item, $key) { return $item->kd_produk_konfirmasi; })->all();
    
        $sp3D = Sp3D::whereNoNpp($data->no_npp)
            ->whereIn('kd_produk', $kd_produks)
            ->get()
            ->sortByDesc('no_sp3')
            ->groupBy([
                'kd_produk', function ($item) {
                    return substr($item->no_sp3, 0, -3);
                }
            ], true);

        $kondisiPenyerahan = [
            'L' => 'LOKO', 
            'F' => 'FRANKO', 
            'T' => 'TERPASANG', 
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($data->no_npp, -1))];

        $listPic = [];

        foreach($data->pic as $pic){
            $listPic[] = $pic->employee->employee_id . ' - ' . $pic->employee->first_name . ($pic->employee->last_name ? ' - ' . $pic->employee->last_name : '');
        }
        return view('pages.sp3.approve', compact(
            'data', 'detailPesanan', 'sp3D', 'kondisiPenyerahanDipilih', 'type', 'listPic'
        ));
    }

    public function storeApprove(Request $request)
    {
        $data = Sp3::find($request->no_sp3);

        if($request->type == 'first'){
            $data->app1 = 1;
            $data->app1_empid = session('TMP_NIP') ?? '12345';
            $data->app1_jbt = session('TMP_KDJBT') ?? '12345';
            $data->app1_date = date('Y-m-d H:i:s');
        }else{
            $data->app2 = 1;
            $data->app2_empid = session('TMP_NIP') ?? '12345';
            $data->app2_jbt = session('TMP_KDJBT') ?? '12345';
            $data->app2_date = date('Y-m-d H:i:s');
        }

        $data->save();

        return redirect()->route('sp3.index');
    }
}