<?php

namespace App\Http\Resources\Internal;

use Illuminate\Http\Resources\Json\JsonResource;

class PelangganUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $pelanggan = $this->pelanggan;
        return [
            "id"           => $this->id,
            "pelanggan_id" => $this->pelanggan_id,
            "nama"         => $this->nama,
            "ktp"          => $this->ktp,
            "no_hp"        => $this->no_hp,
            "jabatan"      => $this->jabatan,
            "pelanggan"    => [
                "pat"  => $pelanggan->pat_pelanggan,
                "nama" => $pelanggan->nama,
            ]
        ];
    }
}
