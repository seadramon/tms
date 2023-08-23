<?php

namespace App\Http\Resources\Internal;

use Illuminate\Http\Resources\Json\JsonResource;

class SptbListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->app_pelanggan == 1){
            $status = 'received';
        }else{
            $status = 'on_progress';
        }
        $proc = $this->sptbd[0]->singkatan2 ?? null;
        return [
            "no_sptb"      => $this->no_sptb,
            "no_npp"       => $this->no_npp,
            "nama_proyek"  => $this->npp->nama_proyek,
            "nama_pelanggan"  => $this->npp->nama_pelanggan,
            "nopol"        => $this->no_pol,
            "vendor"       => $this->spmh->vendor->nama ?? "Not Found",
            "ppb_muat"     => $this->ppb_muat->ket,
            "tgl_dikirim"  => date('d/m/Y', strtotime($this->tgl_berangkat)),
            "tgl_diterima" => date('d/m/Y', strtotime($this->tgl_sampai)),
            "status"       => $status,
            "procedure_path" => $proc ? "http://tms.wika-beton.co.id/document/procedure/" . $proc . ".pdf" : null
        ];
    }
}
