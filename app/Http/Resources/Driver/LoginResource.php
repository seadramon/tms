<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "nama" => $this->nama,
            "no_hp" => $this->no_hp,
            "status" => $this->status,
            "company" => [
                "id" => $this->vendor_id,
                "nama" => $this->vendor->nama
            ],
            "vehicle" => [
                "nopol" => $this->armada->nopol,
                "kode" => $this->armada->kd_armada,
                "jenis" => $this->armada->detail
            ],
        ];
    }
}
