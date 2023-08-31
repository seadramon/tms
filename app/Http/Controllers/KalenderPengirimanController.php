<?php
namespace App\Http\Controllers;

use App\Models\KalenderMg;
use App\Models\Pat;
use App\Models\Sp3;
use App\Models\SpmD;
use App\Models\SpmH;
use App\Models\SppbH;
use App\Services\KalenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KalenderPengirimanController extends Controller
{
    public function index()
    {
        return view('pages.kalender-pengiriman.index');
    }
	
	public function spmData(Request $request)
	{
		$data = (new KalenderService($request->start, $request->end))->rekapDailySpm();
		return response()->json($data);
	}
	
	public function sppData(Request $request)
	{
		$data = (new KalenderService($request->start, $request->end))->rekapDailySppWithSp3();
		return response()->json($data);
	}
	
	public function detailWeekly()
	{
		$labelSemua = ["" => "Semua"];
        if(session('TMP_KDWIL') != '0A'){
            $pat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['1', '4', '5'])
                ->whereKdPat(session('TMP_KDWIL'))
                ->get()
                ->pluck('ket', 'kd_pat')
                ->toArray();
        }else{
            $pat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['1', '4', '5'])
                ->get()
                ->pluck('ket', 'kd_pat')
                ->toArray();
    
            $pat = $labelSemua + $pat;
        }
		$ppb_muat = Pat::whereIn(DB::raw('SUBSTR(KD_PAT, 1, 1)'), ['2', '4', '5'])
            ->get()
            ->pluck('ket', 'kd_pat')
            ->toArray();
		
		$periode = [];
        for($i=0; $i<10; $i++){
            $year = date('Y', strtotime(($i-5) . ' years'));
            $periode[$year] = $year;
        }
		$periode_minggu = KalenderMg::whereTh('2022')
			->whereKdPat('1A')
			->get()
			->sortBy(function ($item) {
				return (int) $item->mg;
			})
			->mapWithKeys(function($item){ 
				$awal = date('d/m/Y', strtotime($item->tgl_awal));
				$akhir = date('d/m/Y', strtotime($item->tgl_akhir));
				return [$item->mg => "Minggu ke-" . $item->mg . " ({$awal}-{$akhir})"]; 
			})
			->all();

		$active_week = DB::select("select  WOS.\"FNC_GETMG\" (to_Date('" . date('d/m/Y') . "','dd/mm/yyyy'), '1A') minggu from dual")[0]->minggu;

		return view('pages.kalender-pengiriman.detail-weekly', [
			'pat'            => $pat,
			'ppb_muat'       => $ppb_muat,
			'periode'        => $periode,
			'periode_minggu' => $periode_minggu,
			'active_week'    => $active_week,
		]);
	}

	public function detailWeeklyData(Request $request)
	{
		$dow = [
			1 => 'S',
			2 => 'S',
			3 => 'R',
			4 => 'K',
			5 => 'J',
			6 => 'S',
			0 => 'M'
		];
		// $spm = SpmH::with('sppb.npp', 'pat', 'armada.jenis', 'spmd.produk')->whereBetween('tgl_spm', ['2008-06-01 00:00:00', '2008-06-30 23:59:59'])
		// 	->get();
		// $week = KalenderMg::whereTh('2008')->whereMg('24')->whereKdPat('1A')->first();

		$week = KalenderMg::whereTh($request->tahun)->whereMg($request->minggu)->whereKdPat('1A')->first();
		$query = SpmH::with('sppb.npp', 'pat', 'armada.jenis', 'spmd.produk')
			->whereBetween('tgl_spm', [$week->tgl_awal, date('Y-m-d 23:59:59', strtotime($week->tgl_akhir))]);
		if($request->ppbmuat != ''){
			$query->wherePatTo($request->ppbmuat);
		}
		if($request->unitkerja != ''){
			$query->whereHas('sppb.npp', function($sql) use($request) {
				$sql->whereKdPat($request->unitkerja);
			});
		}
		$spm = $query->get();

		$data = $spm->groupBy(function($item){
			return ($item->sppb->no_npp ?? 'UnknownSppb') . '_' . ($item->sppb->npp->nama_proyek ?? 'UnknownNpp') . '_' . ($item->pat->ket ?? 'Unknown');
		});

		$data_daily = $spm->groupBy(function($item){
			return ($item->sppb->no_npp ?? 'UnknownSppb') . '_' . ($item->sppb->npp->nama_proyek ?? 'UnknownNpp') . '_' . ($item->pat->ket ?? 'Unknown') . '_' . date('Ymd', strtotime($item->tgl_spm));
		});

		$query = SpmD::with('spmh.sppb.npp', 'spmh.pat', 'produk')->whereHas('spmh', function($sql) use ($week, $request){
				$sql->whereBetween('tgl_spm', [$week->tgl_awal, date('Y-m-d 23:59:59', strtotime($week->tgl_akhir))]);
				if($request->ppbmuat != ''){
					$sql->wherePatTo($request->ppbmuat);
				}
			});
		if($request->unitkerja != ''){
			$query->whereHas('spmh.sppb.npp', function($sql) use($request) {
				$sql->whereKdPat($request->unitkerja);
			});
		}
		$spmd = $query->get();
		
		// $spmd = SpmD::with('spmh.sppb.npp', 'spmh.pat', 'produk')->whereHas('spmh', function($sql) use ($week, $request){
		// 	$sql->whereBetween('tgl_spm', ['2008-06-01 00:00:00', '2008-06-30 23:59:59']);
		// })->get();
		$detail = $spmd->groupBy([
				function($item){
					return ($item->spmh->sppb->no_npp ?? 'UnknownSppb') . '_' . ($item->spmh->sppb->npp->nama_proyek ?? 'UnknownNpp') . '_' . ($item->spmh->pat->ket ?? 'Unknown');
				},
				function($item){
					return $item->produk->tipe;
				},
				function($item){
					return date('Ymd', strtotime($item->spmh->tgl_spm));
				},
			]);

		// $dates = KalenderService::createDateRangeArray('2008-06-01 00:00:00', '2008-06-30 23:59:59', 'Ymd');
		$dates = KalenderService::createDateRangeArray(date('Y-m-d', strtotime($week->tgl_awal)), date('Y-m-d', strtotime($week->tgl_akhir)), 'Ymd');
		// return response()->json([
		// 	'data'       => $data,
		// 	'data_daily' => $data_daily,
		// 	'dates'      => $dates,
		// 	'dow'        => $dow,
		// 	'detail'        => $detail
		// ]);
		return view('pages.kalender-pengiriman.detail-weekly-data', [
			'data'       => $data,
			'data_daily' => $data_daily,
			'dates'      => $dates,
			'dow'        => $dow,
			'detail'     => $detail
		]);
	}

	public function periodeMinggu(Request $request){
		$periode_minggu = KalenderMg::whereTh($request->tahun)
			->whereKdPat('1A')
			->get()
			->sortBy(function ($item) {
				return (int) $item->mg;
			})
			->mapWithKeys(function($item){ 
				$awal = date('d/m/Y', strtotime($item->tgl_awal));
				$akhir = date('d/m/Y', strtotime($item->tgl_akhir));
				return [$item->mg => "Minggu ke-" . $item->mg . " ({$awal}-{$akhir})"]; 
			})
			->all();

		$active_week = DB::select("select  WOS.\"FNC_GETMG\" (to_Date('" . date('d/m/Y') . "','dd/mm/yyyy'), '1A') minggu from dual")[0]->minggu;

		return response()->json([
			'periode_minggu' => $periode_minggu,
			'active_week' => $active_week,
		]);
	}
}