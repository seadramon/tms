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
                @endphp
                <div class="col-12">
                    <!--begin::Progress-->
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between w-100 fs-4 fw-bold mb-3">
                            <span>Progress Pengiriman Barang</span>
                            <span>{{nominal($volsptb)}} of {{nominal($volsp3)}}</span>
                        </div>
                        <div class="h-20px bg-light rounded mb-3">
                            <div class="bg-success rounded h-20px" role="progressbar" style="width: {{$progress}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
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