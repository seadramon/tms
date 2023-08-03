<?php

namespace App\Http\Controllers;

use App\Models\Npp;
use App\Models\Pat;
use App\Models\PotensiH;
use Illuminate\Http\Request;

use App\Models\SppbH;
use App\Models\SppbD;
use App\Models\Views\VPotensiMuat;
use App\Models\Views\VSpprbRi;
use Exception;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SppApprovalController extends Controller
{
    
    public function approval(Request $request)
    {
    	$noSppb = str_replace("|", "/", $request->nosppb);
        $approval = $request->urutan;

    	$data = SppbH::with(['detail', 'spprb', 'npp'])
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
        if (!empty($data->no_npp)) {
            $npp = $data->npp;
            if (!empty($npp->no_info)) {
                $arrData['kontrak'] = DB::table('KD_SEPEDM_D')
                    ->where('no_proyek', $npp->no_info)
                    ->where('no_dok', '12')
                    ->whereRaw("P_KE = (select
                            max(P_KE)
                        from
                            KD_SEPEDM_D
                        WHERE
                            NO_DOK = '12'
                            AND NO_PROYEK = '$npp->no_info'
                        )")
                    ->first();
            }
        }

        //RUTE Data
        $pat_ = Pat::where('kd_pat','LIKE','2%')->orwhere('kd_pat','LIKE','4%')->orwhere('kd_pat','LIKE','5%')->get();
        $muat = VPotensiMuat::with('pat')->where('no_npp', $data->no_npp)->get();
        
        $arrData['lokasi_muat'] = VSpprbRi::with(['produk', 'pat'])
            ->join('spprb_h', 'spprb_h.no_spprb', '=', 'v_spprb_ri.spprblast')
            ->select('v_spprb_ri.pat_to')
            ->where('v_spprb_ri.no_npp', $data->no_npp)
            ->get()
            ->map(function($item){
                return $item->pat->ket;
            })
            ->unique()
            ->all();


        $collection_table = new Collection();
        foreach($muat as $row){
            $spprbRi = VSpprbRi::
                        select('kd_produk','pat_to','no_npp','vol_spprb')
                        ->with(['produk' => function($sql){
                            $sql->select('kd_produk','tipe', 'vol_m3');
                        }])
                        ->with(['ppb_muat' =>function($sql){
                            $sql->select('kd_pat','lat_gps','lng_gps');
                        }])
                        ->where('no_npp',$row->no_npp)
                        ->where('pat_to',$row->ppb_muat)
                        ->groupBy('kd_produk','pat_to','no_npp','vol_spprb')
                        ->get();

            $sqlNpp = Npp::select('npp.nama_proyek',
                        'npp.nama_pelanggan',
                        'npp.no_npp',
                        'tb_region.kabupaten_name as kab', 'tb_region.kecamatan_name as kec',
                        'tb_pat.ket as pat',
                        'npp.kd_pat',
                        'tb_pat.singkatan',
                        'info_pasar_h.lat as info_pasar_lat','info_pasar_h.lang as info_pasar_long',
                        'tb_region.lat as tb_region_lat','tb_region.lang as tb_region_long')
                    ->leftJoin('info_pasar_h', 'npp.no_info', '=', 'info_pasar_h.no_info')
                    ->leftJoin('tb_region', 'tb_region.kd_region', '=', 'info_pasar_h.kd_region')
                    ->leftJoin('tb_pat', 'tb_pat.kd_pat', '=', 'npp.kd_pat')
                    ->where('npp.no_npp', $row->no_npp)
                    ->first();

            $potensiH = PotensiH::where('no_npp',$row->no_npp)
                    ->where('pat_to', $row->ppb_muat)
                    ->first();

            $collection_table->push((object)[
                'no_npp' => $row->no_npp,
                'ppb_muat' => $row->ppb_muat,
                'vol_btg' => $row->vol_btg,
                'tonase' => $row->tonase,
                'jadwal3' => $row->jadwal3,
                'jadwal4' => $row->jadwal4,
                'jml_rit' => $row->jml_rit,
                'pat' => $row->pat->ket,
                'jarak_km' => $row->jarak_km,
                'spprbri' => $spprbRi,
                'lat_source' => $spprbRi[0]->ppb_muat->lat_gps,
                'long_source' => $spprbRi[0]->ppb_muat->lng_gps,
                'lat_dest' => $sqlNpp->info_pasar_lat ?? $sqlNpp->tb_region_lat,
                'long_dest' => $sqlNpp->info_pasar_long ?? $sqlNpp->tb_region_long,
                'destination' => $sqlNpp->kab. ',' . $sqlNpp->kec,
                'potensiH' => $potensiH
            ]);
        }
        $arrData['pat_'] = $pat_;
        $arrData['muat'] = $collection_table;

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
