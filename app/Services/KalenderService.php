<?php
namespace App\Services;

use App\Models\Sp3;
use App\Models\SpmH;
use App\Models\SppbH;
use App\Models\SptbH;
use DateInterval;
use DatePeriod;
use DateTime;

class KalenderService {

    protected $start, $end, $kd_pat;

    public function __construct($start, $end, $kd_pat = null)
    {
        $this->start  = $start;
        $this->end    = $end;
        $this->kd_pat = $kd_pat;
    }

    public function rekapDailySpm($nopol = null)
    {
        $query = SpmH::whereBetween('tgl_spm', [date('Y-m-d 00:00:00', strtotime($this->start)), date('Y-m-d 23:59:59', strtotime($this->end))]);
		if($nopol){
			// $query->whereNoPol($nopol);
			$query->whereRaw("replace(no_pol, ' ','') ='".$nopol."'");
		}
		$data = $query->get()
			->groupBy(function ($item, $key) {
				return date('Y-m-d', strtotime($item->tgl_spm));
			})
			->map(function ($item, $key) {
				return [
					'title' => $item->count(),
					'start' => $key,
                    'textColor' => '#50cd89',
                    'backgroundColor' => '#e8fff3',
					'extendedProps' => [
						'withText' => true
					]
				];
			})
			->values();

        return $data;
    }

    public function rekapDailySppWithSp3()
    {
        $query = SppbH::whereBetween('tgl_sppb', [date('Y-m-d 00:00:00', strtotime($this->start)), date('Y-m-d 23:59:59', strtotime($this->end))]);
		if($this->kd_pat && $this->kd_pat != '0A'){
			$query->whereHas('npp', function($sql){
				$sql->where('kd_pat', $this->kd_pat);
			});
		}
		$spp = $query->get()
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

		$query = Sp3::whereBetween('tgl_sp3', [date('Y-m-d 00:00:00', strtotime($this->start)), date('Y-m-d 23:59:59', strtotime($this->end))]);
		if($this->kd_pat && $this->kd_pat != '0A'){
			$query->where('kd_pat', $this->kd_pat);
		}
		$sp3 = $query->get()
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

        return $data;
    }

	public static function createDateRangeArray($start, $end, $format = 'Y-m-d')
	{
		$period = new DatePeriod(
			new DateTime($start),
			new DateInterval('P1D'),
			new DateTime(date('Y-m-d', strtotime('+1 day ' . $end)))
	    );
		$data = [];
		foreach ($period as $key => $value) {
			$data[] = $value->format($format);       
		}
		return $data;
	}

	public function rekapDailySptb($source = null, $type = null)
    {
        $sptb = SptbH::whereBetween('tgl_berangkat', [date('Y-m-d 00:00:00', strtotime($this->start)), date('Y-m-d 23:59:59', strtotime($this->end))]);
		if($type == 'pelanggan'){
			$sptb->whereHas('pelanggan_npp.pelanggan_user', function($sql) use($source) {
				$sql->where('no_hp', $source);
			});
			$sptb->where('app_pelanggan', '<>', '1');
		}else{
			$sptb->whereRaw("app_pelanggan = '1' and replace(no_pol, ' ','') ='" . $source . "'");
		}
		$sptb = $sptb->get()
			->groupBy(function ($item, $key) {
				return date('Y-m-d', strtotime($item->tgl_berangkat));
			})
			->map(function ($item, $key) {
				return [
					'title' => $item->count(),
					'start' => $key,
					'textColor' => '#53d2f9',
					'backgroundColor' => '#2521b6',
					'extendedProps' => [
						'withText' => true
					]
				];
			});

		$data = $sptb->values();
		return $data;
    }
}