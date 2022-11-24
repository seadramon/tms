<label class="form-label">Harga Satuan</label>

<table class="table table-row-bordered text-center">
    <thead>
        <tr>
            <th width='10%'>No.</th>
            <th width='30%'>Range Jarak</th>
            <th width='30%'>Rencana Harsat Pusat</th>
            <th width='30%'>Harsat Final</th>
        </tr>
    </thead>

    <tbody>
        <input type="hidden" name="count_harsat[{{$index}}]" class="count_harsat" value="{{ count($listData) }}">

        @foreach ($listData as $key => $data)
            <tr>
                <td>
                    {{ ($key+1) }}
                    <input type="hidden" name="key_harsat[{{$index}}][]" value="{{ $key }}">
                </td>
                <td>
                    {{ $data['range_min'] }} - {{ $data['range_max'] }}
                    <input type="hidden" name="range_min[{{$index}}][]" value="{{ $data['range_min'] }}">
                    <input type="hidden" name="range_max[{{$index}}][]" value="{{ $data['range_max'] }}">
                </td>
                <td>
                    {{ number_format($data['h_pusat']) }}
                    <input type="hidden" name="h_pusat[{{$index}}][]" value="{{ $data['h_pusat'] }}">
                </td>
                <td>
                    {{ number_format($data['h_final']) }}
                    <input type="hidden" name="h_final[{{$index}}][]" value="{{ $data['h_final'] }}">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>