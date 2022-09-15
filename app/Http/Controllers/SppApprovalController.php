<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pat;
use App\Models\Produk;
use App\Models\SppbH;
use App\Models\SppbD;
use App\Models\SpprbH;
use App\Models\Sp3;
use App\Models\SptbD;
use App\Models\VSpprbRi;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use DB;
use Session;
use Validator;
use Storage;

class SppApprovalController extends Controller
{
    
    public function approval(Request $request)
    {
    	$noSppb = str_replace("|", "/", $request->nosppb);
        $approval = $request->urutan;

    	$data = SppbH::with(['detail', 'spprb'])
    		->where('no_sppb', $noSppb)
    		->first();

        if ($data->jns_sppb == 0) {
            $jenis="Pesanan Wilayah";
        } else {
            $jenis = "Pesanan Lain-lain";
        }

        $arrData = [];

        if (!empty($data->spprb)) {
            $noSpprb = $data->spprb->no_spprb;
            $noNpp = $data->spprb->no_npp;

            $arrData = $this->getDataSpprb($noSpprb, $noNpp);
        }

    	return view('pages.spp.approve', [
    		'data' => $data,
            'jenis' => $jenis,
            'approval' => $approval
    	] + $arrData);
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
    	try {

    	    DB::beginTransaction();
// dd($request->All());
            if ($request->approvalNum == 'first') { //FIRST APPROVAL
                $next = 'second';
                $data = SppbH::find($request->no_sppb);

                if ($request->uang_muka == '1') {
                    $data->chk_tanpa_dp = $request->uang_muka;
                } else {
                    $data->chk_kontrak = $request->uang_muka;
                }
                $data->chk_produksi = $request->progres_prod;
                $data->chk_distribusi = $request->progres_distribusi;
                $data->catatan_app1 = $request->catatan_app1;

                $data->app = '1';
                $data->app_jbt = str_replace("JBT", "", session('TMP_KDJBT'));
                $data->app_empid = session('TMP_NIP');
                $data->app_date = date('Y-m-d');

                $data->save();

                foreach ($request->rencana as $key => $value) {
                    SppbD::where('no_sppb', $request->no_sppb)
                        ->where('kd_produk', $key)
                        ->update([
                            'app1_vol' => $value['app1_vol']
                        ]);
                }    
            } elseif ($request->approvalNum == 'second') { //SECOND APPROVAL
                $next = 'third';

                $data = SppbH::find($request->no_sppb);

                $data->catatan_app2 = $request->catatan_app2;
                $data->app2 = '1';
                $data->app2_jbt = str_replace("JBT", "", session('TMP_KDJBT'));
                $data->app2_empid = session('TMP_NIP');
                $data->app2_date = date('Y-m-d');
                $data->save();

                foreach ($request->rencana as $key => $value) {
                    SppbD::where('no_sppb', $request->no_sppb)
                        ->where('kd_produk', $key)
                        ->update([
                            'app2_vol' => $value['app2_vol']
                        ]);
                }
            } elseif ($request->approvalNum == 'third') {
                $next = "";

                $data = SppbH::find($request->no_sppb);

                $data->catatan_app3 = $request->catatan_app3;
                $data->app3 = '1';
                $data->app3_jbt = str_replace("JBT", "", session('TMP_KDJBT'));
                $data->app3_empid = session('TMP_NIP');
                $data->app3_date = date('Y-m-d');
                $data->save();

                foreach ($request->rencana as $key => $value) {
                    SppbD::where('no_sppb', $request->no_sppb)
                        ->where('kd_produk', $key)
                        ->update([
                            'app3_vol' => $value['app3_vol']
                        ]);
                }
            }

    	    DB::commit();
    	    $flasher->addSuccess('Data telah berhasil disimpan!');
    	} catch (Exception $e) {
    		DB::rollback();
            $flasher->addError('Terjadi error silahkan coba beberapa saat lagi.');
    	}

        /*return redirect()->route('spp-approve.approval', [
            'urutan' => $next, 
            'nosppb' => str_replace("/", "&", $request->no_sppb)
        ]);*/

        return redirect()->route('spp.index');
    }

    public function secondApproval()
    {
    	
    }

    public function thirdApproval()
    {
    	
    }

    private function getDataSpprb($noSpprb, $noNpp)
    {
        $dtlPesanan = DB::table('v_spprb_ri vsr')
            ->leftJoin('tb_produk', 'vsr.kd_produk', '=', 'tb_produk.kd_produk')
            ->leftJoin('sppb_h', 'sppb_h.no_spprb', '=', 'vsr.spprblast')
            ->leftJoin('sppb_d', function($join) {
                $join->on('sppb_h.no_sppb', '=', 'sppb_d.no_sppb')
                    ->on('vsr.kd_produk', '=', 'sppb_d.kd_produk');
            })
            ->select('tb_produk.tipe', 'tb_produk.ket', 'vsr.vol_spprb', 'tb_produk.vol_m3', 
                'sppb_d.vol', 'vsr.kd_produk')
            ->where('vsr.spprblast', $noSpprb)
            ->where('vsr.no_npp', $noNpp)
            ->get();

        $sqlNpp = DB::table('v_spprb_ri vsr')
            ->leftJoin('npp', 'npp.no_npp', '=', 'vsr.no_npp')
            ->leftJoin('info_pasar_h', 'npp.no_info', '=', 'info_pasar_h.no_info')
            ->leftJoin('tb_region', 'tb_region.kd_region', '=', 'info_pasar_h.kd_region')
            ->where('vsr.spprblast', $noSpprb)
            ->where('vsr.no_npp', $noNpp)
            ->select('npp.nama_proyek', 'npp.nama_pelanggan', 'vsr.no_npp', 'tb_region.kabupaten_name as kab', 'tb_region.kecamatan_name as kec')
            ->first();

        $sqlPat = DB::table('v_spprb_ri vsr')
            ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'vsr.pat_to')
            ->where('vsr.spprblast', $noSpprb)
            ->where('vsr.no_npp', $noNpp)
            ->select('tb_pat.ket', 'vsr.pat_to', 'tb_pat.singkatan')
            ->first();

        $sp3 = DB::table('SP3_D')
            ->where('no_npp', $noNpp)
            ->where('pat_to', $sqlPat->pat_to)
            ->max('jarak_km');

        return [
            'tblPesanan' => $dtlPesanan,
            'npp' => $sqlNpp,
            'pat' => $sqlPat,
            'jarak' => $sp3
        ];
    }
}
