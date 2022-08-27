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
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use DB;
use Session;
use Validator;
use Storage;

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
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Adendum</a></li>
                                <li><a class="dropdown-item" href="#">Approve</a></li>
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

        return view('pages.sp3.create', compact(
            'vendor', 'jenisPekerjaan'
        ));
    }

    public function searchNpp(Request $request)
    {
        return Npp::where('kd_pat', session('TMP_KDWIL') ?? '1A')
            ->where('no_npp', 'LIKE', '%' . $request->q . '%')
            ->get();
    }

    public function searchPic(Request $request)
    {
        return Personal::select( 'kd_pat', 'employee_id', 'first_name', 'last_name')
            ->where('ST', 1)
            ->where('kd_pat', 'LIKE', '%' . $request->q . '%')
            ->get();
    }

    public function getDataBox2(Request $request)
    {
        $parameters = $request->all();
        
        $detailPesanan = MonOp::with(['produk', 'sp3D', 'vSpprbRi'])
            ->where('no_npp', $parameters['no_npp'])
            ->get();

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

        $kondisiPenyerahanDipilih = strtoupper(substr($parameters['no_npp'], -1));

        $VSpprbRi = VSpprbRi::where('no_npp', $parameters['no_npp'])->first();

        $jarak = Sp3D::where('pat_to', $VSpprbRi?->pat_to)
            ->where('no_npp', $VSpprbRi?->no_npp)
            ->max('jarak_km');

        $unit = Pat::where('kd_pat', 'LIKE', '2%')
            ->orWhere('kd_pat', 'LIKE', '4%')
            ->orWhere('kd_pat', 'LIKE', '5%')
            ->get()
            ->pluck('ket', 'kd_pat')
            ->toArray();
            
        $unit = ["" => "Pilih Unit"] + $unit;

        $satuan = [
            "" => "Pilih Satuan",
            "btg" => "BTG",
            "ton" => "TON",
        ];

        $ppn = [
            "0" => "0%",
            "11" => "11%",
        ];

        $html = view('pages.sp3.box2', compact(
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
        ))->render();
        
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
                    'updated_by'    => session('TMP_KDWIL') ?? '1A',
                    'updated_date'  => date('Y-m-d'),
                ]);
            }else{
                $newSequence = '9999';

                $msNoDokumenData = new MsNoDokumen();
                $msNoDokumenData->tahun = date('Y');
                $msNoDokumenData->no_dokumen = $noDokumen;
                $msNoDokumenData->seq = $newSequence;
                $msNoDokumenData->created_by = session('TMP_KDWIL') ?? '1A';
                $msNoDokumenData->created_date = date('Y-m-d');
                $msNoDokumenData->updated_date = date('Y-m-d');
                $msNoDokumenData->save();
            }

            $noSp3 = $noDokumen . '.' . $newSequence . '/' . date('Y') . 'P00';

            $sp3 = new Sp3();
            $sp3->no_sp3 = $noSp3;
            $sp3->no_npp = $request->no_npp;
            $sp3->vendor_id = $vendor->vendor_id;
            $sp3->alamat_vendor = $vendor->alamat;
            $sp3->kd_jpekerjaan = $request->kd_jpekerjaan;
            $sp3->tgl_sp3 = $request->tgl_sp3;
            $sp3->no_ban = $request->no_ban;
            $sp3->no_kontrak_induk = $request->no_kontrak_induk;
            $sp3->jadwal1 = $request->jadwal1;
            $sp3->jadwal2 = $request->jadwal2;
            $sp3->rit = $request->rit;
            $sp3->jarak_km = $request->jarak_pesanan;
            $sp3->ppn = $request->ppn ? (float)($request->ppn / 100) : 0;
            $sp3->keterangan = $request->keterangan;
            $sp3->created_by = session('TMP_KDWIL') ?? '1A';
            $sp3->created_date = date('Y-m-d');
            $sp3->kd_pat = session('TMP_KDWIL') ?? '1A';
            $sp3->save();

            $sp3Pic = new Sp3Pic();
            $sp3Pic->no_sp3 = $noSp3;
            $sp3Pic->employee_id = $request->pic ?? '123';
            $sp3Pic->save();

            for($i=0; $i < 1; $i++){
                $sp3D = new Sp3D();
                $sp3D->no_sp3 = $noSp3;
                $sp3D->no_npp = $request->no_npp[$i];
                $sp3D->pat_to = $request->unit[$i] ?? '2A';
                $sp3D->kd_produk = $request->tipe[$i];
                $sp3D->jarak_km = $request->jarak_pekerjaan[$i];
                $sp3D->vol_awal = $request->vol_btg[$i];
                $sp3D->vol_akhir = $request->vol_btg[$i];
                $sp3D->vol_ton_awal = $request->vol_ton[$i];
                $sp3D->vol_ton_akhir = $request->vol_ton[$i];
                $sp3D->sat_harsat = $request->satuan[$i];
                $sp3D->harsat_awal = $request->harsat[$i];
                $sp3D->harsat_akhir = $request->harsat[$i];
                $sp3D->save();
            }

            $sp3D2Id = Sp3D2::max('id') ?? 1;

            foreach(($request->material_tambahan ?? []) as $material){
                $sp3D2 = new Sp3D2();
                $sp3D2->id = $sp3D2Id++;
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
            $flasher->addError('An error has occurred please try again later.');
        }

        return redirect()->route('sp3.index');
    }
}