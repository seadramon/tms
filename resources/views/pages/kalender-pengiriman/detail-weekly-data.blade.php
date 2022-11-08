<!-- Begin Table -->
<table class="table table-condesed">
    <thead style="background-color: darkblue;">
        <tr class="border border-gray-100 text-lg-center" style="font-size: 12px; font-weight: bold; color:white;">
            <th width="10%" style="vertical-align: middle;">NPP</th>
            <th width="10%" style="vertical-align: middle;">PELANGGAN</th>
            <th width="10%" style="vertical-align: middle;">PROYEK</th>
            <th width="10%" style="vertical-align: middle;">TOTAL RIT</th>
            @foreach ($dates as $i => $row)
                @php
                    $day = $dow[date('w', strtotime($row))];
                @endphp
                <th width="1%" style="vertical-align: middle; background-color: cornflowerblue;">
                    {{$day}}<hr style="margin-top: -4px; margin-bottom: -4px;">
                    <span style="font-size: 8px;">{{$i+1}}</span>
                </th>
            @endforeach
            <th width="10%" style="vertical-align: middle;">JENIS ARMADA</th>
            <th width="10%" style="vertical-align: middle;">PPB MUAT</th>
            <th width="13%" style="vertical-align: middle;" class="text-center">ACTION</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach ($data as $key => $row)
            <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; vertical-align: middle;">
                <td width="10%" class="text-center">{{$row->first()->sppb->no_npp ?? 'UnknownSppb'}}</td>
                <td width="10%">{{$row->first()->sppb->npp->nama_pelanggan ?? 'Unknown Npp'}}</td>
                <td width="10%">{{$row->first()->sppb->npp->nama_proyek ?? 'Unknown Npp'}}</td>
                <td width="10%" class="text-center" style="background-color: darkblue; color:white; vertical-align: middle; font-weight: bolder;">{{$row->count()}}</td>
                @foreach ($dates as $i => $row)
                    @php
                        $daily_key = $key . '_' . $row;
                    @endphp
                    <td width="1%" class="text-center" style="background-color: lavenderblush; color:black; vertical-align: middle; font-weight: bolder;">
                        {{count($data_daily[$daily_key] ?? [])}}
                    </td>
                @endforeach
                <td class="text-center" width="15%">{{ $row->first()->armada->jenis->name ?? '-' }}</td>
                <td class="text-center" width="15%">{{($row->first()->pat->ket ?? 'Unknown' )}}</td>
                <td class="text-center" width="13%">
                    <a href="javascript:void(0)" class="expandChildTable"><i class="fa fa-eye"></i></a>
                </td>
            </tr>
            <tr class="childTableRow" style="display: none;">
                <td colspan="14" style="padding-top:0px; padding-bottom: 0px; padding-left: 0px; padding-right: 0px;">
                    <table class="table" style="margin-bottom:0px; ">
                        <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; border-bottom: 1px solid #f5f8fa !important;">
                            <td width="30.3%" colspan="3"></td>
                            <td width="9.7%" style="background-color: #f2f2f2;">50 B0 BM 15 7 B K3.5</td>
                            <td class="text-center" width="1.6%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">6</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">8</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">3</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td colspan="3" class="text-center" width="42%"></td>
                        </tr>
                        <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; border-bottom: 1px solid #f5f8fa !important;">
                            <td width="30.3%" colspan="3"></td>
                            <td width="9.7%" style="background-color: #f2f2f2;">50 B0 BM 15 7 B K3.5</td>
                            <td class="text-center" width="1.6%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">6</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">8</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">3</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td colspan="3" class="text-center" width="42%"></td>
                        </tr>
                        <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; border-bottom: 1px solid #f5f8fa !important;">
                            <td width="30.3%" colspan="3"></td>
                            <td width="9.7%" style="background-color: #f2f2f2;">50 B0 BM 15 7 B K3.5</td>
                            <td class="text-center" width="1.6%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">6</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">8</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">3</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td colspan="3" class="text-center" width="42%"></td>
                        </tr>
                        <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; border-bottom: 1px solid #f5f8fa !important;">
                            <td width="30.3%" colspan="3"></td>
                            <td width="9.7%" style="background-color: #f2f2f2;">50 B0 BM 15 7 B K3.5</td>
                            <td class="text-center" width="1.6%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">6</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">8</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">3</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td colspan="3" class="text-center" width="42%"></td>
                        </tr>
                        <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; border-bottom: 1px solid #f5f8fa !important;">
                            <td width="30.3%" colspan="3"></td>
                            <td width="9.7%" style="background-color: #f2f2f2;">50 B0 BM 15 7 B K3.5</td>
                            <td class="text-center" width="1.6%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">6</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">8</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">3</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">4</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td class="text-center" width="1%" style="font-weight: bolder;">0</td>
                            <td colspan="3" class="text-center" width="42%"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


<!--begin::Accordion-->
{{-- <div class="accordion" id="kt_accordion_1">
    @php
        $i = 1;
    @endphp
    @foreach ($data as $key => $row)
        <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_{{ $i }}">
                <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_{{ $i }}" aria-expanded="true" aria-controls="kt_accordion_1_body_{{ $i }}">
                    <span class="badge badge-light-dark badge-lg" style="margin-right: 20px;">{{$row->first()->sppb->no_npp}}</span>
                    <span class="badge badge-outline badge-primary badge-lg" style="margin-right: 20px;">{{$row->first()->sppb->npp->nama_pelanggan}}</span>
                    <span class="badge badge-outline badge-danger badge-lg" style="margin-right: 20px;">{{$row->first()->sppb->npp->nama_proyek}}</span>
                    <span class="badge badge-outline badge-info badge-lg" style="margin-right: 20px;">{{($row->first()->pat->ket ?? 'Unknown')}}</span>
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
</div> --}}
<!--end::Accordion-->


<script type="text/javascript">


    // show detail list on table
    $(function() {
        $('.expandChildTable').on('click', function() {
            $(this).toggleClass('selected').closest('tr').next().toggle();
        })
    });
</script>
