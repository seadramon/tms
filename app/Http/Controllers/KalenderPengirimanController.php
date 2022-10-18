<?php
namespace App\Http\Controllers;

use App\Models\Pat;
use App\Models\Sp3;
use App\Models\SpmH;
use App\Models\SppbH;
use App\Services\KalenderService;
use Illuminate\Http\Request;

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
        $pat = Pat::all()->pluck('ket', 'kd_pat')->toArray();
        $pat = $labelSemua + $pat;
		
		$periode = [];
        for($i=0; $i<10; $i++){
            $year = date('Y', strtotime(($i-5) . ' years'));
            $periode[$year] = $year;
        }
		$periode_minggu = [];
        for($i=1; $i<53; $i++){
            $periode_minggu[$i] = $i;
        }
		
		return view('pages.kalender-pengiriman.detail-weekly', [
			'pat'     => $pat,
			'periode' => $periode,
			'periode_minggu' => $periode_minggu,
		]);
	}

	public function detailWeeklyData()
	{
		$data = SpmH::with('sppb.npp', 'pat')
			->whereBetween('tgl_spm', ['2008-06-01 00:00:00', '2008-06-30 23:59:59'])
			->get()
			->groupBy(function($item){
				return $item->sppb->no_npp . '_' . $item->sppb->npp->nama_proyek . '_' . ($item->pat->ket ?? 'Unknown');
			});
		return view('pages.kalender-pengiriman.detail-weekly-data', [
			'data' => $data
		]);
	}
}