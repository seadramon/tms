<!--begin::Accordion-->
<div class="accordion" id="kt_accordion_1">
    @php
        $i = 1;
    @endphp
    @foreach ($data as $key => $row)
        <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_{{ $i }}">
                <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_{{ $i }}" aria-expanded="true" aria-controls="kt_accordion_1_body_{{ $i }}">
                    <span class="badge badge-light-dark badge-lg" style="margin-right: 20px;">{{$row->first()->sppb->no_npp}}</span><span class="badge badge-outline badge-primary badge-lg" style="margin-right: 20px;">{{$row->first()->sppb->npp->nama_pelanggan}}</span><span class="badge badge-outline badge-danger badge-lg" style="margin-right: 20px;">{{$row->first()->sppb->npp->nama_proyek}}</span><span class="badge badge-outline badge-info badge-lg" style="margin-right: 20px;">{{($row->first()->pat->ket ?? 'Unknown')}}</span>
                </button>
            </h2>
            <div id="kt_accordion_1_body_{{ $i }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_{{ $i }}" data-bs-parent="#kt_accordion_1">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-2">
                                <label class="col-lg-3 fw-semibold">No NPP</label>
                                <div class="col-lg-9">
                                    <span class="fw-bold fs-6 text-gray-800">{{$row->first()->sppb->no_npp}}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-lg-3 fw-semibold">Pelanggan</label>
                                <div class="col-lg-9">
                                    <span class="fw-bold fs-6 text-gray-800">{{$row->first()->sppb->npp->nama_pelanggan}}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-lg-3 fw-semibold">Proyek</label>
                                <div class="col-lg-9">
                                    <span class="fw-bold fs-6 text-gray-800">{{$row->first()->sppb->npp->nama_proyek}}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-lg-3 fw-semibold">Jenis Armada</label>
                                <div class="col-lg-9">
                                    <span class="fw-bold fs-6 text-gray-800">{{ '-' }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-lg-3 fw-semibold">PPB Muat</label>
                                <div class="col-lg-9">
                                    <span class="fw-bold fs-6 text-gray-800">{{$row->first()->pat->ket ?? 'Unknown'}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <table class="table table-row-dashed table-row-gray-300" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>RIT</th>
                                        <th>S<br>1</th>
                                        <th>S<br>2</th>
                                        <th>R<br>3</th>
                                        <th>K<br>4</th>
                                        <th>J<br>5</th>
                                        <th>S<br>6</th>
                                        <th>M<br>7</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="border-right: 1px dashed #e4e6ef">50 B0 BM 15 7 B K3.5</td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">2</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">5</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">4</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">7</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">1</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">0</span></td>
                                        <td><span class="badge badge-circle badge-outline badge-dark">0</span></td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px dashed #e4e6ef">50 B0 BM 15 7 B K3.5</td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">2</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">5</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">4</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">7</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">1</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">0</span></td>
                                        <td><span class="badge badge-circle badge-outline badge-dark">0</span></td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px dashed #e4e6ef">50 B0 BM 15 7 B K3.5</td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">2</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">5</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">4</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">7</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">1</span></td>
                                        <td style="border-right: 1px dashed #e4e6ef"><span class="badge badge-circle badge-outline badge-dark">0</span></td>
                                        <td><span class="badge badge-circle badge-outline badge-dark">0</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $i++;
        @endphp
    @endforeach
</div>
<!--end::Accordion-->