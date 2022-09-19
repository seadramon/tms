<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tms_drivers';
    protected $appends = ['status_label'];

    protected function statusLabel(): Attribute
    {
        $label = $this->status == 'aktif' ? 'success' : 'danger';
        return Attribute::make(get: fn ($value) => "<span class=\"badge badge-light-{$label} mr-2 mb-2\">" . Str::of($this->status)->camel()->ucfirst() . "</span>");
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function armada()
    {
        return $this->hasOne(Armada::class, 'driver_id', 'id');
    }
}
