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
        if($this->sptbh && $this->waktu_datang){
            $status = 'terbit_sptb';
        }elseif($this->waktu_datang){
            $status = 'pemuatan';
        }else{
            $status = 'belum_tiba';
        }
        return [
            "no_spm"         => $this->no_spm,
            "no_sptb"        => $this->sptbh->no_sptb ?? null,
            "no_npp"         => $this->sppb->no_npp,
            "nama_proyek"    => $this->sppb->npp->nama_proyek,
            "nama_pelanggan" => $this->sppb->npp->nama_pelanggan,
            "nopol"          => $this->no_pol,
            "vendor"         => $this->vendor->nama ?? "Not Found",
            "sbu"            => $this->spmd->first()->sbu->singkatan,
            "ppb_muat"       => $this->pat->ket,
            "status"         => $status
        ];
    }
}
