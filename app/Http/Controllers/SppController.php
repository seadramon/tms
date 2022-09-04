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

class SppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.spp.index');
    }

    public function data()
    {
        $query = SppbH::with(['spprb', 'detail'])->select('*');

        return DataTables::eloquent($query)
            ->editColumn('jadwal1', function ($model) {
                return date('d-m-Y', strtotime($model->jadwal1));
            })
            ->editColumn('jadwal2', function ($model) {
                return date('d-m-Y', strtotime($model->jadwal2));
            })
            ->addColumn('no_sp3', function($model) {
                $nosp3 = "";

                if (!empty($model->spprb)) {
                    $temp = Sp3::where('no_npp', $model->spprb->no_npp)->first();

                    if ($temp) {
                        $nosp3 = $temp->no_sp3;
                    }
                }

                return $nosp3;
            })
            ->addColumn('waktu', function($model) {
                $ret = 0;
                if (!empty($model->jadwal1) && !empty($model->jadwal2)) {
                    $a = $this->diffDate($model->jadwal1, date('d-m-Y'));
                    $b = $this->diffDate($model->jadwal1, $model->jadwal2);

                    if ($b > 0) {
                        $ret = round(($a / $b) * 100);
                    }
                }
                if($ret > 100){
                    $ret = 100;
                }

                return $ret . '%';
            })
            ->addColumn('vol', function($model) {
                $res = "";

                $sptb = SptbD::where('no_spprb', $model->no_spprb)->sum('vol');
                $sppb = $model->detail->sum('vol');

                if ($sppb > 0) {
                    $res = $sptb / $sppb;
                }

                return $res . '%';
            })
            ->addColumn('menu', function ($model) {
                switch (true) {
                    case ($model->app == 0):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'first', 
                            'nosppb' => str_replace("/", "&", $model->no_sppb)
                        ]);
                        $caption = "Approve First";
                        break;
                    case ($model->app == 1 && $model->app2 == 0):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'second', 
                            'nosppb' => str_replace("/", "&", $model->no_sppb)
                        ]);
                        $caption = "Approve Second";
                        break;
                    case ($model->app == 1 && $model->app2 == 1 && $model->app3 == 0):
                        $approve = route('spp-approve.approval', [
                            'urutan' => 'third', 
                            'nosppb' => str_replace("/", "&", $model->no_sppb)
                        ]);
                        $caption = "Approve Third";
                        break;
                    default:
                        $approve = "";
                        $caption = "";
                        break;
                }
                if ($approve!="") {
                    $approval = '<li><a class="dropdown-item" href="'.$approve.'">'. $caption .'</a></li>';
                } else {
                    $approval = "";
                }
// dd($approve);
                $edit = '<div class="btn-group">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">View</a></li>
                            <li><a class="dropdown-item" href="#">Edit</a></li>
                            <li><a class="dropdown-item" href="#">Adendum</a></li>
                            '.$approval.'
                            <li><a class="dropdown-item" href="#">Print</a></li>
                            <li><a class="dropdown-item" href="#">Hapus</a></li>
                        </ul>
                        </div>';

                return $edit;
            })
            ->rawColumns(['menu', 'waktu'])
            ->toJson();
    }

    private function diffDate($date1, $date2)
    {
        $j1 = date_create($date1);
        $j2 = date_create($date2);
        $interval = date_diff($j1, $j2);

        $ret = $interval->format('%d');

        return $ret;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenis = [
            '' => '-Pilih Jenis-',
            'Pesanan Wilayah' => 'Pesanan Wilayah',
            'Pesanan Lain-lain' => 'Pesanan Lain-lain'
        ];

        return view('pages.spp.create', [
            'jenis' => $jenis
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createDraft(Request $request)
    {
        try {
            DB::beginTransaction();
            
            Validator::make($request->all(), [
                'jenis'   => 'required',
                'no_npp'   => 'required',
                'no_spprb'   => 'required'
            ])->validate();

            $params = explode("|", $request->no_spprb);
            $noSpprb = $params[0];
            $noNpp = $params[1];

            // 040/PI/SPPRB/III/WP-I/21P01
            $sqldtlPesanan = DB::table('v_spprb_ri vsr')
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
                // ->take(3)
                ->get();

            $listDetailPesanan = $this->detailPesananHtml($sqldtlPesanan);
            $rencanaProduk = $this->rencanaProdukHtml($sqldtlPesanan);

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

            return response()->json([
                'result' => 'success',
                'tblPesanan' => $listDetailPesanan,
                'npp' => $sqlNpp,
                'pat' => $sqlPat,
                'jarak' => $sp3,
                'rencanaProd' => $rencanaProduk,
                'noSpprb' => $noSpprb
            ])->setStatusCode(200, 'OK');
        } catch(Exception $e) {
            return response()->json(['result' => $e->getMessage()])->setStatusCode(500, 'ERROR');
        }
    }

    private function rencanaProdukHtml($data)
    {
        $ret = "";

        if (count($data) > 0) {
            $i = 1;

            foreach ($data as $row) {
                $ret .= "<tr>";
                $ret .= "<td>" .$i. "</td>";
                $ret .= "<td>" .$row->tipe. "</td>";
                $ret .= "<td><input type='text' name='rencana[$i][kd_produk]' value='$row->kd_produk' class='form-control'></td>";
                $ret .= "<td><input type='number' name='rencana[$i][saat_ini]' data-sblmbtg='$row->vol' data-urutan='$i' class='form-control saat-ini' onkeyup='sdSaatIni($row->vol, $i)' id='id-saatini-$i'></td>";
                $ret .= "<td><input type='number' name='rencana[$i][sd_saat_ini]' id='id-sdsaatini-$i' class='form-control' disabled></td>";

                $ret .= "<td><input type='text' name='rencana[$i][ket]' class='form-control'></td>";
                $ret .= "<td><input class='form-check-input' name='rencana[$i][segmental]' type='checkbox' value='1' id='flexCheckDefault'/></td>";
                $ret .= "<td><input type='number' name='rencana[$i][jml_segmen]' class='form-control'></td>";

                $ret .= "</tr>";
                $i++;
            }
        }

        return $ret;
    }

    private function detailPesananHtml($data) 
    {
        $ret = "";
        if (count($data) > 0) {

            foreach ($data as $row) {
                $ret .= "<tr>";
                $ret .= "<td>" .$row->tipe. "</td>";

                $volm3 = !empty($row->vol_m3)?$row->vol_m3:1;
                $pesananVolBtg = $row->vol_spprb;
                $pesananVolTon = $row->vol_spprb * $volm3 * 2.5;
                $sppSebelumVolBtg = $row->vol;
                $sppSebelumVolTon = $row->vol * $volm3 * 2.5;
                $sisaBtg = $pesananVolBtg - $sppSebelumVolBtg;
                $sisaTon = $pesananVolTon - $sppSebelumVolTon;
                if ($pesananVolBtg > 0) {
                    $persen = $sisaBtg / $pesananVolBtg * 100;
                }

                $ret .= "<td>". $pesananVolBtg ."</td>";
                $ret .= "<td>". $pesananVolTon ."</td>";
                $ret .= "<td>". $sppSebelumVolBtg ."</td>";
                $ret .= "<td>". $sppSebelumVolTon ."</td>";

                $ret .= "<td>". $sisaBtg ."</td>";
                $ret .= "<td>". $sisaTon ."</td>";
                $ret .= "<td>". round($persen, 2) ."</td>";

                $ret .= "</tr>";
            }

        } else {
            $ret = "<tr colspan='8'>Data tidak ditemukan</tr>";
        }

        return $ret;
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        try {
            DB::beginTransaction();

            if ($request->rencana) {
                $jadwal = [];
                $rencana = $request->rencana;
                $kdProduk = $request->rencana[1]['kd_produk'];
                $noSppb = $this->generateSppb($kdProduk, session('TMP_KDWIL'));

                if (!empty($request->jadwal)) {
                    $jadwal = explode(" - ", $request->jadwal);
                }

                $data = new SppbH;
                $data->no_sppb = $noSppb;
                $data->no_spprb = $request->no_spprb;
                $data->tujuan = $request->tujuan;
                $data->rit = $request->rit;
                $data->jarak_km = $request->jarak_km;
                $data->catatan = $request->catatan;
                $data->jadwal1 = !empty($jadwal[0])?date('Y-m-d', strtotime($jadwal[0])):date('Y-m-d', strtotime('-3 day', time()));
                $data->jadwal2 = !empty($jadwal[1])?date('Y-m-d', strtotime($jadwal[1])):date('Y-m-d');
                $data->tgl_sppb = date('Y-m-d');
                $data->created_by = session('TMP_NIP');

                $data->save();

                foreach ($rencana as $row) {
                    $detail = new SppbD;

                    $detail->kd_produk = $row['kd_produk'];
                    $detail->vol = $row['saat_ini'];
                    $detail->ket = $row['ket'];
                    $detail->segmental = !empty($row['segmental'])?$row['segmental']:'0';
                    $detail->jml_segmen = $row['jml_segmen'];

                    $data->detail()->save($detail);
                }

                DB::commit();
                $flasher->addSuccess('Data telah berhasil disimpan!');
            } else {
                $flasher->addError('Detail Rencana Produk dikirim kosong.');
            }
        } catch(Exception $e) {
            DB::rollback();
            $flasher->addError('Terjadi error silahkan coba beberapa saat lagi.');
        }

        return redirect()->route('spp.index');
    }


    public function getSpprb(Request $request)
    {
        $term = trim($request->q);

        if (empty($term)) {
            return \Response::json([]);
        }

        $tags = DB::table('v_spprb_ri vsr')
            ->selectRaw("vsr.spprblast, vsr.NO_NPP,
                vsr.SPPRBLAST || ' | ' || vsr.NO_NPP || ' | ' || npp.NAMA_PROYEK as name")
            ->join('npp', 'vsr.no_npp', '=', 'npp.no_npp')
            ->where(function($query) use($term){
                $query->where('vsr.spprblast', 'like', "%$term%")
                    ->orWhere('vsr.no_npp', 'like', "%$term%")
                    ->orWhere('npp.nama_proyek', 'like', "%$term%");
            })
            ->groupBy('vsr.SPPRBLAST', 'vsr.NO_NPP', 'npp.NAMA_PROYEK')
            ->limit(5)->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->spprblast.'|'.$tag->no_npp, 'text' => $tag->name];
        }

        return \Response::json($formatted_tags);
    }

    private function generateSppb($kdProduk, $patSingkatan)
    {
        $produk = DB::table('view_master_produk')->where('kd_produk', $kdProduk)->first();
        $singkatan = !empty($produk)?$produk->singkatan2:'RT';
        $tahun = date('Y');
        $pat = Pat::where('kd_pat', $patSingkatan)->first();

        $maks = SppbH::whereRaw("no_sppb like '%/$singkatan/%/$tahun'")->max('no_sppb');

        if (!empty($maks)) {
            $maks = substr($maks, 0, 4);

            $urutan = sprintf('%04d', $maks + 1);
        } else {
            $urutan = sprintf('%04d', 1);
        }

        $noSppb = $urutan.'/SPPB/'.$singkatan.'/'.($pat->singkatan ?? 'XX').'/'.date('m').'/'.date('Y');

        return $noSppb;
    }
}
