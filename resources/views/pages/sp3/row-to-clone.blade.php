<tr class="detail_pekerjaan" id="detail_pekerjaan_clone" style="display: none">
    <td style="width: 10%;">
        {!! Form::select('unit[]', $unit, null, ['class'=>'form-control', 'data-control'=>'select2', 'disabled']) !!}
    </td>
    <td style="width: 14%;">
        {!! Form::hidden('kd_produk[]', $detailPesanan->first()?->produk?->kd_produk, []) !!}
        {!! Form::select('tipe[]', $produk, null, ['class'=>'form-control', 'data-control'=>'select2', 'disabled']) !!}
    </td>
    <td style="width: 12%;">
        {!! Form::text('jarak_pekerjaan[]', null, ['class'=>'form-control jarak_pekerjaan decimal', 'disabled']) !!}
    </td>
    <td style="width: 13%;">
        {!! Form::text('vol_btg[]', null, ['class'=>'form-control vol_btg decimal', 'disabled']) !!}
        <input type="hidden" value="0" disabled>
    </td>
    <td style="width: 13%;">
        {!! Form::text('vol_ton[]', null, ['class'=>'form-control vol_ton decimal', 'disabled']) !!}
    </td>
    @if ($sat_harsat != 'ritase')
        <td style="width: 10%;">
            {!! Form::select('satuan[]', $satuan, null, ['class'=>'form-control satuan', 'data-control'=>'select2', 'disabled']) !!}
        </td>
    @endif
    <td style="width: 15%;">
        {!! Form::text('harsat[]', null, ['class'=>'form-control harsat decimal', 'disabled']) !!}
    </td>
    <td style="width: 10%;">
        {!! Form::text('jumlah[]', null, ['class'=>'form-control jumlah decimal', 'readonly', 'disabled']) !!}
    </td>
    <td style="vertical-align: middle; padding-left: 0px; width: 3%;">
        <button type="button" class="btn btn-danger btn-sm delete_pekerjaan" style="padding: 5px 6px;">
            <span class="bi bi-trash"></span>
        </button>
    </td>
</tr>