<?php

namespace App\Http\Resources\Pelanggan;

use Illuminate\Http\Resources\Json\JsonResource;

class PelangganResource extends JsonResource
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
            "id" => $this->pelanggan_id,
            "nama" => $this->nama,
            "pat" => $this->pat_pelanggan
        ];
    }
}
