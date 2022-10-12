<?php
namespace App\Http\Controllers;

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
}