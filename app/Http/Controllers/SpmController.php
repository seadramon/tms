<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\Produk;
use App\Models\SppbH;
use App\Models\SppbD;
use App\Models\SpprbH;

use App\Models\Sp3D;
use App\Models\SpmH;
use App\Models\SpmD;
use App\Models\SptbD;
use App\Models\MsNoDokumen;
use App\Models\Vendor;
use App\Models\Npp;
use App\Models\Sbu;
use Exception;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SpmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $no_spp = SppbH::where('app2',1)->orWhere('app3',1)->get();
        return view('pages.spm.create', [
            'no_spp' => $no_spp
        ]);
    }

    public function getPbbMuat(Request $request){

        $data = SppbH::select('no_npp')->where('no_sppb',$request->no_spp)->first();
        $data_1 = SpprbH::with('pat')->where('no_npp',$data->no_npp)->get();
        return response()->json($data_1);

    }

    public function getDataBox2(Request $request){

        $no_spp = $request->no_spp;

        $detail_spp = SppbD::with('produk')->where('no_sppb',$request->no_spp)->get();

        $collection_table = new Collection();
        foreach($detail_spp as $item){
            // $sppdis_vol_btg = DB::select('
            //     SELECT SPM_H.NO_SPM, SPM_H.NO_SPPB, SPTB_H.NO_SPM,  SPTB_D.NO_SPTB, SUM(SPTB_D.VOL) as sppdis_vol_btg
            //     FROM SPM_H
            //     JOIN SPTB_H on SPTB_H.NO_SPM = SPM_H.NO_SPM
            //     JOIN SPTB_D on SPTB_H.NO_SPTB = SPTB_D.NO_SPTB
            //     where SPM_H.NO_SPPB = '.$request->no_spp.'
            //     GROUP BY SPM_H.NO_SPM, SPM_H.NO_SPPB, SPTB_H.NO_SPM,  SPTB_D.NO_SPTB');

            $collection_table->push((object)[
                'kode_produk' => $item->produk->kd_produk,
                'type_produk' => $item->produk->kd_produk.' - '.$item->produk->tipe,
                'spp_vol_btg' => $item->app2_vol,
                'spp_vol_ton' => 0,
                'sppdis_vol_btg' => 0
            ]);
        }


        $no_npp = SppbH::select('no_npp')->where('no_sppb',$request->no_spp)->first();
        $no_spprb = SpprbH::with('pat')->where('no_npp',$no_npp->no_npp)->first();
        $pelanggan = Npp::select('nama_pelanggan','nama_proyek')->where('no_npp',$no_npp->no_npp)->first();
        $vendor_angkutan = Vendor::where('vendor_id','LIKE','WB%')->where('sync_eproc',1)->get();
        $tujuan = Npp::with('infoPasar.region')->first();

        $jarak = Sp3D::where('no_npp',$no_npp->no_npp)->where('pat_to',$no_spprb->pat->kd_pat)->first();
        if(empty($jarak)){
            $jarak = 0;
        }

        $kondisiPenyerahan = [
            'L' => 'LOKO',
            'F' => 'FRANKO',
            'T' => 'TERPASANG',
            'D' => 'DISPENSASI'
        ];

        $kondisiPenyerahanDipilih = $kondisiPenyerahan[strtoupper(substr($no_npp->no_npp, -1))];

        $html = view('pages.spm.box2', [
            'no_npp' => $no_npp->no_npp,
            'no_spprb' => $no_spprb->no_spprb,
            'detail_spp' => $collection_table,
            'no_spp' => $no_spp,
            'vendor_angkutan' => $vendor_angkutan,
            'kp'=> $kondisiPenyerahanDipilih,
            'pelanggan' => $pelanggan->nama_pelanggan,
            'nama_proyek' => $pelanggan->nama_proyek,
            'tujuan' => $tujuan,
            'jarak' => $jarak
        ])->render();

        return response()->json( array('success' => true, 'html'=> $html) );
    }

    function getJmlSegmen(Request $request){
        $no_sppb = $request->no_sppb;
        $kd_produk = $request->kd_produk;

        $data = SppbD::select('jml_segmen','app2_vol')
                ->where('no_sppb',$no_sppb)
                ->where('kd_produk',$kd_produk)
                ->first();

        $spm = SpmH::with(['spmd' => function($sql){
                    $sql->where('kd_produk',$kd_produk);
                }])
                ->where('no_sppb',$no_sppb)
                ->first();

        $collection = new Collection();

        $jml = 0;
        if(!empty($spm)){
            foreach($spm->spmd as $row){
                $jml = $jml + $row->vol;
            }
        }


        $collection->push((object)[
            'jml_segmen' => $data->jml_segmen,
            'app2_vol' => $data->app2_vol,
            'jml_spm' => $jml,
        ]);

        return response()->json($collection);

    }

    function getVolSPM(){
        $no_sppb = $request->no_sppb;
        $kd_produk = $request->kd_produk;

        $data = SpmH::with(['spmd' => function($sql){
                    $sql->where('kd_produk',$kd_produk);
                }])
                ->where('no_sppb',$no_sppb)
                ->first();


        if(empty($data)){
            return 0;
        }else{
            $jml = 0;
            foreach($data->spmd as $row){
                $jml = $jml + $row->vol;
            }
            return response()->json($jml);
        }
    }

    public function store(Request $request, FlasherInterface $flasher){
        try {
            DB::beginTransaction();
            // store in SPM_H
            $no_sppb = $request->no_spp;
            $tgl_spm = $request->tanggal;
            $jns_spm = $request->jenis_spm;

            // ---------
            $no_npp = SppbH::select('no_npp')->where('no_sppb',$no_sppb)->first();
            $no_spprb = SpprbH::with('pat')->where('no_npp',$no_npp->no_npp)->first();
            $pat_to = $no_spprb->pat->kd_pat;
            // -----------

            $vendor_angkutan = $request->vendor;
            $jarak = $request->jarak;

            //create number
            $kd_sbu = substr($request->tipe_produk_select[0], 0, 1);
            $n3 = Sbu::select('singkatan2')->where('kd_sbu',$kd_sbu)->first();
            $n4 = Pat::select('singkatan')->where('kd_pat',$pat_to)->first();
            // end of create number

            $noDokumen = 'SPM/'.$n3->singkatan2.'/'.$n4->singkatan;

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

            $no_spm = $newSequence.'/'.$noDokumen.'/'.date('m').'/'.date('Y');

            $SpmH = new SpmH();
            $SpmH->no_spm = $no_spm;
            $SpmH->no_sppb = $no_sppb;
            $SpmH->vendor_id = $vendor_angkutan;
            $SpmH->tgl_spm = $tgl_spm;
            $SpmH->app1 = 0;
            $SpmH->save();
            // store to smp_d
            $i = 0;
            foreach($request->keterangan_select as $row){
                $SpmD = new SpmD();
                $SpmD->no_spm = $no_spm;
                $SpmD->kd_produk = $request->tipe_produk_select[$i];
                $SpmD->vol = $request->volume_produk_select[$i];
                $SpmD->ket = $request->keterangan_select[$i];
                $SpmD->save();
                $i++;
            }
            DB::commit();

            $flasher->addSuccess('Data has been saved successfully!');
        } catch(Exception $e) {
            DB::rollback();
            $flasher->addError($e->getMessage());
        }

        return redirect()->route('spm.create');
    }
}
