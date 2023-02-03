<?php

namespace App\Http\Resources\Internal;

use Illuminate\Http\Resources\Json\JsonResource;

class SpmListResource extends JsonResource
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
            "no_spm"      => $this->no_spm,
            "no_npp"      => $this->sppb->no_npp,
            "nama_proyek" => $this->sppb->npp->nama_proyek,
            "nopol"       => $this->no_pol,
            "vendor"      => $this->vendor->nama,
            "sbu"         => $this->spmd->first()->sbu->singkatan,
            "ppb_muat"    => $this->pat->ket
        ];
    }
}
