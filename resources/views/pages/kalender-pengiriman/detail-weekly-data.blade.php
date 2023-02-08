<!-- Begin Table -->
@php
    
@endphp
<ul class="nav mb-3" id="kt_chart_widget_8_tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link btn btn-sm btn-color-info btn-active btn-active-light-info fw-bold px-4 me-1 active" data-bs-toggle="tab" id="ritase-btn"  aria-selected="false" tabindex="-1" role="tab">Ritase</a>
    </li>
    
    <li class="nav-item" role="presentation">
        <a class="nav-link btn btn-sm btn-color-info btn-active btn-active-light-info fw-bold px-4 me-1" data-bs-toggle="tab" id="produk-btn" aria-selected="true" role="tab">Produk</a>
    </li>
</ul>
<table class="table table-condesed" id="ritase-mode">
    <thead style="">
        <tr class="border border-gray-100 text-lg-center" style="font-size: 12px; font-weight: bold; color:white;">
            <th width="30%" style="vertical-align: middle;" colspan="3"></th>
            <th width="10%" style="vertical-align: middle; background-color: #ace1af;">TOTAL: {{$data->sum(function($item){ return $item->count(); })}}</th>
            @foreach ($dates as $i => $baris)
                @php
                    $day = $dow[date('w', strtotime($baris))] ?? "X";
                    $tgl = date('d', strtotime($baris));
                @endphp
                <th width="1.2%" style="vertical-align: middle; background-color: #ace1af;">
                    {{$data->map(function($item, $key) use ($data_daily, $baris) { $daily_key = $key . '_' . $baris; return count($data_daily[$daily_key] ?? []); })->values()->sum()}}
                </th>
            @endforeach
            <th width="33%" style="vertical-align: middle;" colspan="3"></th>
        </tr>
        <tr class="border border-gray-100 text-lg-center" style="font-size: 12px; font-weight: bold; color:white;background-color: darkblue;">
            <th width="10%" style="vertical-align: middle;">NPP</th>
            <th width="10%" style="vertical-align: middle;">PELANGGAN</th>
            <th width="10%" style="vertical-align: middle;">PROYEK</th>
            <th width="10%" style="vertical-align: middle;">TOTAL RIT</th>
            @foreach ($dates as $i => $baris)
                @php
                    $day = $dow[date('w', strtotime($baris))] ?? "X";
                    $tgl = date('d', strtotime($baris));
                @endphp
                <th width="1.2%" style="vertical-align: middle; background-color: cornflowerblue;">
                    {{$day}}<hr style="margin-top: -4px; margin-bottom: -4px;">
                    <span style="font-size: 8px;">{{$tgl}}</span>
                </th>
            @endforeach
            <th width="10%" style="vertical-align: middle;">JENIS ARMADA</th>
            <th width="10%" style="vertical-align: middle;">PPB MUAT</th>
            {{-- <th width="10%" style="vertical-align: middle;">PPB MUAT</th> --}}
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
                @foreach ($dates as $i => $baris)
                    @php
                        $daily_key = $key . '_' . $baris;
                    @endphp
                    <td width="1.2%" class="text-center" style="background-color: lavenderblush; color:black; vertical-align: middle; font-weight: bolder;">
                        {{count($data_daily[$daily_key] ?? [])}}
                    </td>
                @endforeach
                <td class="text-center" width="15%">{{ $row->first()->armada->jenis->name ?? '-' }}</td>
                <td class="text-center" width="15%" data-spm="{{$row->first()->no_spm}}">{{($row->first()->pat->ket ?? 'Unknown' )}}</td>
                {{-- <td class="text-center" width="13%">
                    <a href="javascript:void(0)" class="expandChildTable"><i class="fa fa-eye"></i></a>
                </td> --}}
            </tr>
            <tr class="childTableRow" style="display: none;">
                <td colspan="15" style="padding-top:0px; padding-bottom: 0px; padding-left: 0px; padding-right: 0px;">
                    <table class="table" style="margin-bottom:0px; " style="width: 100%;">
                        @if ($detail[$key] ?? false)
                        @foreach ($detail[$key]->keys() as $produk)
                            <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; border-bottom: 1px solid #f5f8fa !important;">
                                <td style="width: 33%;" colspan="3"></td>
                                <td style="width: 10.3%;" style="background-color: #f2f2f2;">{{$produk}}</td>
                                @foreach ($dates as $i => $baris)
                                    <td class="text-center" style="width: {{1.3 + (0.15 * $i)}}%;" style="font-weight: bolder;">
                                        {{ count($detail[$key][$produk][$baris] ?? []) }}
                                    </td>
                                @endforeach
                                <td colspan="3" class="text-center" style="width: 48.3%;"></td>
                            </tr>
                            
                        @endforeach
                        @endif
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<table class="table table-condesed hidden" id="produk-mode">
    <thead style="">
        <tr class="border border-gray-100 text-lg-center" style="font-size: 12px; font-weight: bold; color:white;">
            <th width="30%" style="vertical-align: middle;" colspan="3"></th>
            @php
                $ttl=0;
            @endphp
            @foreach ($dates as $baris)
                @php
                    $ttl+= $data->filter(function($item, $key) use($detail) { return ($detail[$key] ?? false); })->map(function($item, $key) use($detail, $baris) { return $detail[$key]->map(function($item1, $key1) use($detail, $baris, $key) { return count($detail[$key][$key1][$baris] ?? []); })->values(); })->values()->flatten()->sum();
                @endphp
            @endforeach
            <th width="10%" style="vertical-align: middle; background-color: #ace1af;">TOTAL: {{$ttl}}</th>
            @foreach ($dates as $i => $baris)
                @php
                    $day = $dow[date('w', strtotime($baris))] ?? "X";
                    $tgl = date('d', strtotime($baris));
                @endphp
                <th width="1.2%" style="vertical-align: middle; background-color: #ace1af;">
                    {{ $data->filter(function($item, $key) use($detail) { return ($detail[$key] ?? false); })->map(function($item, $key) use($detail, $baris) { return $detail[$key]->map(function($item1, $key1) use($detail, $baris, $key) { return count($detail[$key][$key1][$baris] ?? []); })->values(); })->values()->flatten()->sum() }}
                </th>
            @endforeach
            <th width="33%" style="vertical-align: middle;" colspan="3"></th>
        </tr>
        <tr class="border border-gray-100 text-lg-center" style="font-size: 12px; font-weight: bold; color:white;background-color: darkblue;">
            <th width="10%" style="vertical-align: middle;">NPP</th>
            <th width="10%" style="vertical-align: middle;">PELANGGAN</th>
            <th width="10%" style="vertical-align: middle;">PROYEK</th>
            <th width="10%" style="vertical-align: middle;">KODE PRODUK</th>
            @foreach ($dates as $i => $baris)
                @php
                    $day = $dow[date('w', strtotime($baris))] ?? "X";
                    $tgl = date('d', strtotime($baris));
                @endphp
                <th width="1.2%" style="vertical-align: middle; background-color: cornflowerblue;">
                    {{$day}}<hr style="margin-top: -4px; margin-bottom: -4px;">
                    <span style="font-size: 8px;">{{$tgl}}</span>
                </th>
            @endforeach
            <th width="10%" style="vertical-align: middle;">JENIS ARMADA</th>
            <th width="10%" style="vertical-align: middle;">PPB MUAT</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach ($data as $key => $row)
            @if ($detail[$key] ?? false)
                @php
                    $n =0;
                @endphp
                @foreach ($detail[$key]->keys() as $produk)
                    @if ($n==0)
                        <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; vertical-align: middle;">
                            <td width="10%" class="text-center" rowspan="{{$detail[$key]->keys()->count()}}">{{$row->first()->sppb->no_npp ?? 'UnknownSppb'}}</td>
                            <td width="10%" rowspan="{{$detail[$key]->keys()->count()}}">{{$row->first()->sppb->npp->nama_pelanggan ?? 'Unknown Npp'}}</td>
                            <td width="10%" rowspan="{{$detail[$key]->keys()->count()}}">{{$row->first()->sppb->npp->nama_proyek ?? 'Unknown Npp'}}</td>
                            <td width="10%" class="text-center" style="background-color: darkblue; color:white; vertical-align: middle; font-weight: bolder;">{{$produk}}</td>
                            @foreach ($dates as $i => $baris)
                                <td width="1.2%" class="text-center" style="background-color: lavenderblush; color:black; vertical-align: middle; font-weight: bolder;">
                                    {{ count($detail[$key][$produk][$baris] ?? []) }}
                                </td>
                            @endforeach
                            <td class="text-center" width="15%" rowspan="{{$detail[$key]->keys()->count()}}">{{ $row->first()->armada->jenis->name ?? '-' }}</td>
                            <td class="text-center" width="15%" rowspan="{{$detail[$key]->keys()->count()}}" data-spm="{{$row->first()->no_spm}}">{{($row->first()->pat->ket ?? 'Unknown' )}}</td>
                        </tr>
                    @else
                        <tr class="text-gray-800 border border-gray-100" style="font-size: 11px; vertical-align: middle;">
                            <td width="10%" class="text-center" style="background-color: darkblue; color:white; vertical-align: middle; font-weight: bolder;">{{$produk}}</td>
                            @foreach ($dates as $i => $baris)
                                <td width="1.2%" class="text-center" style="background-color: lavenderblush; color:black; vertical-align: middle; font-weight: bolder;">
                                    {{ count($detail[$key][$produk][$baris] ?? []) }}
                                </td>
                            @endforeach
                        </tr>
                        
                    @endif
                    
                    @php
                        $n++;
                    @endphp
                @endforeach
            @endif
            </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">


    // show detail list on table
    $(function() {
        $('.expandChildTable').on('click', function() {
            $(this).toggleClass('selected').closest('tr').next().toggle();
        })
        $('#ritase-btn').on('click', function() {
            $("#ritase-mode").removeClass('hidden');
            $("#produk-mode").addClass('hidden');
        })
        $('#produk-btn').on('click', function() {
            $("#produk-mode").removeClass('hidden');
            $("#ritase-mode").addClass('hidden');
        })
    });
</script>
