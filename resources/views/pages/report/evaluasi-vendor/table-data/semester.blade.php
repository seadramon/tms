@php
    $i = 1;
@endphp
@foreach ($data as $key => $item)
    <tr style="text-align: center;">
        <td>{{ $i }}</td>
        <td>{{ $item['vendor'] }}</td>
        <td>{{ round($item['mutu'], 2) }}</td>
        <td>{{ round($item['mutu'] * 35 / 100, 2) }}</td>
        <td>{{ round($item['waktu'], 2) }}</td>
        <td>{{ round($item['waktu'] * 35 / 100, 2) }}</td>
        <td>{{ round($item['pelayanan'], 2) }}</td>
        <td>{{ round($item['pelayanan'] * 20 / 100, 2) }}</td>
        <td>{{ round($item['k3l'], 2) }}</td>
        <td>{{ round($item['k3l'] * 10 / 100, 2) }}</td>
        <td>{{ round($item['total'], 2) }}</td>
    </tr>
    @php
        $i++;
    @endphp
@endforeach
