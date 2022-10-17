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
        <input type="hidden" name="count_harsat[]" class="count_harsat" value="{{ count($listData) }}">

        @foreach ($listData as $key => $data)
            <tr>
                <td>
                    {{ sprintf('%03s', ((int) $key+1)) }}
                    <input type="hidden" name="key_harsat[]" value="{{ $key }}">
                </td>
                <td>
                    {{ $data['range_min'] }} - {{ $data['range_max'] }}
                    <input type="hidden" name="range_min[]" value="{{ $data['range_min'] }}">
                    <input type="hidden" name="range_max[]" value="{{ $data['range_max'] }}">
                </td>
                <td>
                    {{ $data['h_pusat'] }}
                    <input type="hidden" name="h_pusat[]" value="{{ $data['h_pusat'] }}">
                </td>
                <td>
                    {{ $data['h_final'] }}
                    <input type="hidden" name="h_final[]" value="{{ $data['h_final'] }}">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>