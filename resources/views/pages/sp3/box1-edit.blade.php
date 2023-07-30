<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Tambah Baru SP3 / SPK</h3>
    </div>

    @if ($isAmandemen)
        <div class="card-body">
            <div class="row">
                @php
                    $volsp3 = $data->sp3D->sum('vol_akhir');
                    $volsptb = $sptbd->map(function($i, $k){ return $i->sum('vol'); })->values()->sum();
                    $progress = $volsp3 == 0 ? 0 : round($volsptb / $volsp3 * 100);
                    $progress = $progress > 100 ? 100 : $progress;

                    $volsp3 = $data->sp3D->sum('vol_akhir');
                    $volsptb = $sptbd->map(function($i, $k){ return $i->sum('vol'); })->values()->sum();
                    $progress_vol = $volsp3 == 0 ? 0 : round($volsptb / $volsp3 * 100, 2);
                    $progress_vol = $progress_vol > 100 ? 100 : $progress_vol;
                    
                    $sp3d_ = $data->sp3D->groupBy(function($item){ return $item->kd_produk . '_' . $item->pat_to; });
                    $sptb_rp = $sptbd_->sum(function($item) use($sp3d_) {
                        $key = $item->kd_produk . '_' . $item->sptbh->kd_pat;
                        return $item->vol * ($sp3d_[$key][0]->harsat_akhir ?? 0);
                    });
                    $sp3_rp = $data->sp3D->sum(function($item) { return intval($item->vol_akhir) * intval($item->harsat_akhir); });
                    $progress_rp = $sp3_rp == 0 ? 0 : round($sptb_rp / $sp3_rp * 100, 2);
                    $progress_rp = $progress_rp > 100 ? 100 : $progress_rp;

                    $tgl1 = 0;
                    $tgl2 = 0;
                    $progress_wkt = 0;
                    $ret = 0;
                    if (!is_null($data->jadwal1) && !is_null($data->jadwal2)) {
                        $tgl1 = (strtotime(date('Y-m-d'))-strtotime($data->jadwal1)) / 3600 / 24;
                        $tgl2 = (strtotime($data->jadwal2)-strtotime($data->jadwal1)) / 3600 / 24;
                        
                        if ($tgl2 > 0) {
                            $progress_wkt = round(($tgl1 / $tgl2) * 100, 2);
                        }
                    }
                    $progress_wkt = $progress_wkt > 100 ? 100 : $progress_wkt;
                @endphp
                <div class="col-12">
                    <!--begin::Progress-->
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between w-100 fs-4 fw-bold mb-3">
                            <span>Progress Pengiriman Barang (Volume)&nbsp;<span class="badge badge-square badge-dark badge-outline">{{$progress_vol}}%</span></span>
                            <span>{{nominal($volsptb > $volsp3 ? $volsp3 : $volsptb, 0)}} of {{nominal($volsp3, 0)}}</span>
                        </div>
                        <div class="h-20px bg-light rounded mb-3">
                            <div class="bg-success rounded h-20px" role="progressbar" style="width: {{$progress_vol}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        {{-- <div class="fw-semibold text-gray-600">14 Targets are remaining</div> --}}
                    </div>
                    <!--end::Progress-->
                </div>
                <div class="col-12">
                    <!--begin::Progress-->
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between w-100 fs-4 fw-bold mb-3">
                            <span>Progress Pengiriman Barang (Rupiah)&nbsp;<span class="badge badge-square badge-dark badge-outline">{{$progress_rp}}%</span></span>
                            <span>{{nominal($sptb_rp > $sp3_rp ? $sp3_rp : $sptb_rp, 0)}} of {{nominal($sp3_rp, 0)}}</span>
                        </div>
                        <div class="h-20px bg-light rounded mb-3">
                            <div class="bg-info rounded h-20px" role="progressbar" style="width: {{$progress_rp}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        {{-- <div class="fw-semibold text-gray-600">14 Targets are remaining</div> --}}
                    </div>
                    <!--end::Progress-->
                </div>
                <div class="col-12">
                    <!--begin::Progress-->
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between w-100 fs-4 fw-bold mb-3">
                            <span>Progress Pengiriman Barang (Periode)&nbsp;<span class="badge badge-square badge-dark badge-outline">{{$progress_wkt}}%</span></span>
                            <span>{{nominal($tgl1 > $tgl2 ? $tgl2 : $tgl1, 0)}} of {{nominal($tgl2, 0)}}</span>
                        </div>
                        <div class="h-20px bg-light rounded mb-3">
                            <div class="bg-warning rounded h-20px" role="progressbar" style="width: {{$progress_wkt}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        {{-- <div class="fw-semibold text-gray-600">14 Targets are remaining</div> --}}
                    </div>
                    <!--end::Progress-->
                </div>
            </div>
        </div>
    @endif

    <div class="card-body">
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">NPP</label>
                {!! Form::text('no_npp_text', $data->npp?->no_npp . ' | ' . $data->npp?->nama_proyek, ['class'=>'form-control', 'id'=>'no_npp', 'disabled']) !!}
                {!! Form::hidden('no_npp', $data->no_npp, []) !!}
            </div>
            
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">No. SP3 / SPK</label>
                {!! Form::text('no_sp3_text', $data->no_sp3, ['class'=>'form-control', 'id'=>'no_sp3', 'disabled']) !!}
                {!! Form::hidden('no_sp3', $data->no_sp3, []) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Vendor</label>
                {!! Form::text('vendor_id_text', $data->vendor?->nama, ['class'=>'form-control', 'id'=>'vendor_id', 'disabled']) !!}
                {!! Form::hidden('vendor_id', $data->vendor_id, []) !!}
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Pekerjaan</label>
                {!! Form::text('kd_jpekerjaan_text', $data->jenisPekerjaan?->ket, ['class'=>'form-control', 'id'=>'kd_jpekerjaan', 'disabled']) !!}
                {!! Form::hidden('kd_jpekerjaan', $data->kd_jpekerjaan, []) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Satuan HarSat</label>
                {!! Form::text('sat_harsat_text', ucfirst($data->satuan_harsat), ['class'=>'form-control', 'id'=>'sat_harsat', 'disabled']) !!}
                {!! Form::hidden('sat_harsat', $data->satuan_harsat, []) !!}
            </div>
        </div>
    </div>
</div>