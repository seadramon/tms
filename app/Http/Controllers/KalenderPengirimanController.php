<?php
namespace App\Http\Controllers;

use App\Models\Sp3;
use App\Models\SpmH;
use App\Models\SppbH;
use Illuminate\Http\Request;

class KalenderPengirimanController extends Controller
{
    public function index()
    {
        return view('pages.kalender-pengiriman.index');
    }

	public function spmData(Request $request)
	{
		$data = SpmH::whereBetween('tgl_spm', [date('Y-m-d 00:00:00', strtotime($request->start)), date('Y-m-d 23:59:59', strtotime($request->end))])
			->get()
			->groupBy(function ($item, $key) {
				return date('Y-m-d', strtotime($item->tgl_spm));
			})
			->map(function ($item, $key) {
				return [
					'title' => $item->count(),
					'start' => $key,
					'extendedProps' => [
						'withText' => true
					]
				];
			})
			->values();
		return response()->json($data);
	}

	public function sppData(Request $request)
	{
		$spp = SppbH::whereBetween('tgl_sppb', [date('Y-m-d 00:00:00', strtotime($request->start)), date('Y-m-d 23:59:59', strtotime($request->end))])
			->get()
			->groupBy(function ($item, $key) {
				return date('Y-m-d', strtotime($item->tgl_sppb));
			})
			->map(function ($item, $key) {
				return [
					'title' => $item->count(),
					'start' => $key,
					'backgroundColor' => '#af96e2',
					'extendedProps' => [
						'withText' => false
					]
				];
			});
			// ->values();

		$sp3 = Sp3::whereBetween('tgl_sp3', [date('Y-m-d 00:00:00', strtotime($request->start)), date('Y-m-d 23:59:59', strtotime($request->end))])
			->get()
			->groupBy(function ($item, $key) {
				return date('Y-m-d', strtotime($item->tgl_sp3));
			})
			->map(function ($item, $key) {
				return [
					'title' => $item->count(),
					'start' => $key,
					'backgroundColor' => '#b6f5f7',
					'extendedProps' => [
						'withText' => false
					]
				];
			});
			// ->values();
		
		if($sp3->count() > 0){
			$data = $sp3->merge($spp->all())->values();
		}else{
			$data = $spp->values();
		}
		return response()->json($data);
	}
}