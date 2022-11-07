<?php
namespace App\Services;

use App\Models\Sp3;
use App\Models\SpmH;
use App\Models\SppbH;
use DateInterval;
use DatePeriod;
use DateTime;

class KalenderService {

    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function rekapDailySpm()
    {
        $data = SpmH::whereBetween('tgl_spm', [date('Y-m-d 00:00:00', strtotime($this->start)), date('Y-m-d 23:59:59', strtotime($this->end))])
			->get()
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
        $spp = SppbH::whereBetween('tgl_sppb', [date('Y-m-d 00:00:00', strtotime($this->start)), date('Y-m-d 23:59:59', strtotime($this->end))])
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

		$sp3 = Sp3::whereBetween('tgl_sp3', [date('Y-m-d 00:00:00', strtotime($this->start)), date('Y-m-d 23:59:59', strtotime($this->end))])
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
}