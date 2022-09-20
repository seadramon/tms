<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Armada extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tms_armadas';
    protected $primaryKey = 'id';
    protected $appends = ['status_label', 'v_status_label'];

    const REVERIFIED_COLUMNS = [
        'tahun',
        'tgl_stnk',
        'tgl_kir_head',
        'tgl_kir_trailer',
        'tgl_pajak',
        'foto_stnk',
        'foto_kir_head',
        'foto_kir_trailer',
        'foto_pajak'
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function (Armada $armada) {
            // set unverified if params changed
            if($armada->isDirty(self::REVERIFIED_COLUMNS)){
                $armada->v_status = 'unverified';

                $category = ['stnk', 'kir_head', 'kir_trailer', 'pajak'];
                foreach ($category as $row) {
                    $param = "v_" . $row;
                    if($armada->isDirty(['tgl_' . $row, 'foto_' . $row])){
                        $armada->$param = null;
                    }
                }
            }

            // Verify
            if($armada->isDirty(['v_stnk', 'v_kir_head', 'v_kir_trailer', 'v_pajak', 'visual', 'kelengkapan', 'kondisi_ban'])){
                $verify = collect([$armada->v_stnk, $armada->v_kir_head, $armada->v_kir_trailer, $armada->v_pajak, $armada->visual, $armada->kelengkapan, $armada->kondisi_ban])->unique()->values();
                if($verify->containsOneItem() && $verify->first() == 'deluxe'){
                    $armada->v_status = 'deluxe';
                }elseif($verify->contains(null)){
                    $armada->v_status = 'unverified';
                }elseif($verify->count() <= 2 && $verify->contains('fit') && $verify->doesntContain('fair')){
                    $armada->v_status = 'fit';
                }else{
                    $armada->v_status = 'fair';
                }
            }
        });
    }

    protected function statusLabel(): Attribute
    {
        $label = $this->status == 'aktif' ? 'success' : 'danger';
        return Attribute::make(get: fn ($value) => "<span class=\"badge badge-light-{$label} mr-2 mb-2\">" . Str::of($this->status)->camel()->ucfirst() . "</span>");
    }
    protected function vStatusLabel(): Attribute
    {
        $label = [
            'unverified' => 'danger',
            'fair'       => 'warning',
            'fit'        => 'info',
            'deluxe'     => 'success',
        ];
        return Attribute::make(get: fn ($value) => "<span class=\"badge badge-light-{$label[$this->v_status]} mr-2 mb-2\">" . Str::of($this->v_status)->camel()->ucfirst() . "</span>");
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    public function jenis()
    {
        return $this->belongsTo(TrMaterial::class, 'kd_armada', 'kd_material');
    }
}
