<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelangganNpp extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tms_pelanggan_npps';

    /**
     * Get the pelanggan_user that owns the PelangganNpp
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pelanggan_user(): BelongsTo
    {
        return $this->belongsTo(PelangganUser::class, 'pelanggan_user_id', 'id');
    }
}
