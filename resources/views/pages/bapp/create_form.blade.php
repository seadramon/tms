<div class="row">
    <div class="form-group col-lg-12 mt-2">
        <label class="form-label">NPP</label>
        {!! Form::text('no_npp', $npp->no_npp . ' | ' . $npp->nama_proyek, ['class'=>'form-control', 'id'=>'no_npp', 'readonly']) !!}
    </div>
    <div class="form-group col-lg-6 mt-2">
        <label class="form-label">Pihak Pertama</label>
        {!! Form::select('pihak_pertama', $personal, $data->pihak1 ?? null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'pihak_pertama'], $opt_personal) !!}
    </div>
    <div class="form-group col-lg-6 mt-2">
        <label class="form-label">Pihak Kedua</label>
        {!! Form::text('pihak_kedua', $data ? $data->pihak2 : ($trader->pimpinan_nama ?? ""), ['class'=>'form-control', 'id'=>'pihak_kedua']) !!}
    </div>
    <div class="form-group col-lg-6 mt-2">
        <label class="form-label">Jabatan Pihak Pertama</label>
        {!! Form::text('pihak_pertama_jabatan', $data->pihak1_data->jabatan->ket ?? null, ['class'=>'form-control', 'id'=>'pihak_pertama_jabatan', 'readonly']) !!}
    </div>
    <div class="form-group col-lg-6 mt-2">
        <label class="form-label">Jabatan Pihak Kedua</label>
        {!! Form::text('pihak_kedua_jabatan', $data ? $data->pihak2_jabatan : ($trader->pimpinan_jabatan ?? ""), ['class'=>'form-control', 'id'=>'pihak_kedua_jabatan']) !!}
    </div>
    <div class="form-group col-lg-6 mt-2">
        <label class="form-label">Tanggal BAPP</label>
        <div class="col-lg-12">
            <div class="input-group date">
                {!! Form::text('tgl_bapp', $data ? date('d-m-Y', strtotime($data->tgl_bapp)) : null, ['class'=>'form-control datepicker', 'id'=>'tgl_bapp']) !!}
                <div class="input-group-append">
                    <span class="input-group-text" style="display: block">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-12 mt-2">
        <div class="table-responsive">
            <table class="table table-rounded table-row-bordered border gy-2 gs-2" style="">
                <thead class="bg-primary">
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-right-2 border-gray-200" style="vertical-align: middle;">
                        <th rowspan="2" class="text-center">#</th>
                        <th rowspan="2" class="text-center">No</th>
                        <th rowspan="2">Uraian/Type</th>
                        <th rowspan="2" class="text-center">Satuan</th>
                        <th colspan="4" class="text-center">SP3</th>
                        <th colspan="6" class="text-center">Realisasi s/d Lalu</th>
                        <th colspan="6" class="text-center">Saat Ini</th>
                        <th colspan="6" class="text-center">s/d Saat Ini</th>
                        <th colspan="6" class="text-center">Sisa (Ra-Ri)</th>
                    </tr>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-right-2 border-gray-200">
                        <th class="text-center">Vol Btg</th>
                        <th class="text-center">Vol Ton</th>
                        <th class="text-center">Harsat</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Vol Btg</th>
                        <th class="text-center">% Btg</th>
                        <th class="text-center">Vol Ton</th>
                        <th class="text-center">% Ton</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">% Harga</th>
                        <th class="text-center">Vol Btg</th>
                        <th class="text-center">% Btg</th>
                        <th class="text-center">Vol Ton</th>
                        <th class="text-center">% Ton</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">% Harga</th>
                        <th class="text-center">Vol Btg</th>
                        <th class="text-center">% Btg</th>
                        <th class="text-center">Vol Ton</th>
                        <th class="text-center">% Ton</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">% Harga</th>
                        <th class="text-center">Vol Btg</th>
                        <th class="text-center">% Btg</th>
                        <th class="text-center">Vol Ton</th>
                        <th class="text-center">% Ton</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">% Harga</th>
                    </tr>
                </thead>
                <tbody id="tbody-produks">
                    @php
                        $no = 0;
                        $total_sp3 = 0;
                        $total_lalu = 0;
                    @endphp
                    @if ($data)
                        @foreach ($data->bapp_d as $detail)
                            @php
                                $no++;
                                $produk = $detail->produk;
                                $satuan = $detail->satuan;
                                $jml_sp3 = ($satuan == 'ton') ? ($detail->sp3_vol_ton * $detail->harsat) : ($detail->sp3_vol_btg * $detail->harsat);
                                $total_sp3 += $jml_sp3;
                                $jml_lalu = ($satuan == 'ton') ? ($detail->lalu_vol_ton * $detail->harsat) : ($detail->lalu_vol_btg * $detail->harsat);
                                $total_lalu += $jml_lalu;
                                $jml_saatini = ($satuan == 'ton') ? ($detail->vol_ton * $detail->harsat) : ($detail->vol_btg * $detail->harsat);
                            @endphp
                            <tr style="vertical-align: middle;" id="tr-{{$detail->kd_produk}}" class="pake-border-v">
                                <input type="hidden" name="sp3_produk[]" class="sp3_produk" value="{{$detail->kd_produk}}">
                                <td class="text-center">
                                    <button class="btn btn-icon btn-dark edit-saatini" style="height: 20px; width: 20px;" data-row="{{$detail->kd_produk}}" id="btn-{{$detail->kd_produk}}" type="button"><i class="fa-solid fa-pen-to-square"></i></button>
                                </td>
                                <td class="text-center">{{ $no }}</td>
                                <td>{{ ($produk->ket ?? '') }}<br>{{ ($produk->tipe ?? '') }}</td>
                                <td style="text-align: center;">{{ $satuan }}<input type="hidden" name="sp3_satuan[{{$detail->kd_produk}}]" class="sp3_satuan" value="{{$satuan}}"></td>
                                <td style="text-align: center;">{{ $detail->sp3_vol_btg }}<input type="hidden" name="sp3_btg[{{$detail->kd_produk}}]" class="sp3_btg" value="{{$detail->sp3_vol_btg}}"></td>
                                <td style="text-align: center;">{{ $detail->sp3_vol_ton }}<input type="hidden" name="sp3_ton[{{$detail->kd_produk}}]" class="sp3_ton" value="{{$detail->sp3_vol_ton}}"></td>
                                <td style="text-align: right;">{{ number_format($detail->harsat) }}<input type="hidden" name="sp3_harsat[{{$detail->kd_produk}}]" class="sp3_harsat" value="{{$detail->harsat}}"></td>
                                <td style="text-align: right;">{{ number_format($jml_sp3) }}<input type="hidden" name="sp3_ttl[{{$detail->kd_produk}}]" class="sp3_ttl" value="{{$jml_sp3}}"></td>
                                <td style="text-align: center;">{{ $detail->lalu_vol_btg }}<input type="hidden" name="lalu_btg[{{$detail->kd_produk}}]" class="lalu_btg" value="{{$detail->lalu_vol_btg}}"></td>
                                <td style="text-align: center;">{{ round($detail->lalu_vol_btg / $detail->sp3_vol_ton * 100, 0) }}%</td>
                                <td style="text-align: center;">{{ $detail->lalu_vol_ton }}<input type="hidden" name="lalu_ton[{{$detail->kd_produk}}]" class="lalu_ton" value="{{$detail->lalu_vol_ton}}"></td>
                                <td style="text-align: center;">{{ round($detail->lalu_vol_ton / $detail->sp3_vol_ton * 100, 0) }}%</td>
                                <td style="text-align: right;">{{ number_format($jml_lalu) }}<input type="hidden" name="lalu_harga[{{$detail->kd_produk}}]" class="lalu_harga" value="{{$jml_lalu}}"></td>
                                <td style="text-align: right;">{{ round($jml_lalu / $jml_sp3 * 100, 0) }}%</td>
                                <td style="text-align: center;" class="saatini_btg1">{{ $detail->vol_btg }}<input type="hidden" name="saatini_btg[{{$detail->kd_produk}}]" class="saatini_btg" value="{{ $detail->vol_btg }}"></td>
                                <td style="text-align: center;" class="saatini_btg2">{{ round($detail->vol_btg / $detail->sp3_vol_btg * 100, 0) }}%</td>
                                <td style="text-align: center;" class="saatini_ton1">{{ $detail->vol_ton }}<input type="hidden" name="saatini_ton[{{$detail->kd_produk}}]" class="saatini_ton" value="{{ $detail->vol_ton }}"></td>
                                <td style="text-align: center;" class="saatini_ton2">{{ round($detail->vol_ton / $detail->sp3_vol_ton * 100, 0) }}%</td>
                                <td style="text-align: right;" class="saatini_harga1">{{ number_format($jml_saatini) }}<input type="hidden" name="saatini_harga[{{$detail->kd_produk}}]" class="saatini_harga" value="{{$jml_saatini}}"></td>
                                <td style="text-align: center;" class="saatini_harga2">{{ round($jml_saatini / $jml_sp3 * 100, 0) }}%</td>
                                <td style="text-align: center;" class="sd_saatini_btg1">{{ 0 }}</td>
                                <td style="text-align: center;" class="sd_saatini_btg2">{{ 0 }}</td>
                                <td style="text-align: center;" class="sd_saatini_ton1">{{ 0 }}</td>
                                <td style="text-align: center;" class="sd_saatini_ton2">{{ 0 }}</td>
                                <td style="text-align: right;" class="sd_saatini_harga1">{{ number_format(0) }}</td>
                                <td style="text-align: center;" class="sd_saatini_harga2">{{ 0 }}%</td>
                                <td style="text-align: center;" class="ss_saatini_btg1">{{ 0 }}</td>
                                <td style="text-align: center;" class="ss_saatini_btg2">{{ 0 }}</td>
                                <td style="text-align: center;" class="ss_saatini_ton1">{{ 0 }}</td>
                                <td style="text-align: center;" class="ss_saatini_ton2">{{ 0 }}</td>
                                <td style="text-align: right;" class="ss_saatini_harga1">{{ number_format(0) }}</td>
                                <td style="text-align: center;" class="ss_saatini_harga2">{{ 0 }}%</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($sp3->sp3D as $detail)
                            @php
                                $no++;
                                $produk = $detail->produk;
                                $satuan = ($detail->sat_harsat ?? 'ton');
                                $jml_sp3 = ($satuan == 'ton') ? ($detail->vol_ton_akhir * $detail->harsat_akhir) : ($detail->vol_akhir * $detail->harsat_akhir);
                                $total_sp3 += $jml_sp3;
                            @endphp
                            <tr style="vertical-align: middle;" id="tr-{{$detail->kd_produk}}" class="pake-border-v">
                                <input type="hidden" name="sp3_produk[]" class="sp3_produk" value="{{$detail->kd_produk}}">
                                <td class="text-center">
                                    <button class="btn btn-icon btn-dark edit-saatini" style="height: 20px; width: 20px;" data-row="{{$detail->kd_produk}}" id="btn-{{$detail->kd_produk}}" type="button"><i class="fa-solid fa-pen-to-square"></i></button>
                                </td>
                                <td class="text-center">{{ $no }}</td>
                                <td>{{ ($produk->ket ?? '') }}<br>{{ ($produk->tipe ?? '') }}</td>
                                <td style="text-align: center;">{{ $satuan }}<input type="hidden" name="sp3_satuan[{{$detail->kd_produk}}]" class="sp3_satuan" value="{{$satuan}}"></td>
                                <td style="text-align: center;">{{ $detail->vol_akhir }}<input type="hidden" name="sp3_btg[{{$detail->kd_produk}}]" class="sp3_btg" value="{{$detail->vol_akhir}}"></td>
                                <td style="text-align: center;">{{ $detail->vol_ton_akhir }}<input type="hidden" name="sp3_ton[{{$detail->kd_produk}}]" class="sp3_ton" value="{{$detail->vol_ton_akhir}}"></td>
                                <td style="text-align: right;">{{ number_format($detail->harsat_akhir) }}<input type="hidden" name="sp3_harsat[{{$detail->kd_produk}}]" class="sp3_harsat" value="{{$detail->harsat_akhir}}"></td>
                                <td style="text-align: right;">{{ number_format($jml_sp3) }}<input type="hidden" name="sp3_ttl[{{$detail->kd_produk}}]" class="sp3_ttl" value="{{$jml_sp3}}"></td>
                                <td style="text-align: center;">{{ 0 }}<input type="hidden" name="lalu_btg[{{$detail->kd_produk}}]" class="lalu_btg" value="0"></td>
                                <td style="text-align: center;">{{ 0 }}</td>
                                <td style="text-align: center;">{{ 0 }}<input type="hidden" name="lalu_ton[{{$detail->kd_produk}}]" class="lalu_ton" value="0"></td>
                                <td style="text-align: center;">{{ 0 }}</td>
                                <td style="text-align: right;">{{ number_format(0) }}<input type="hidden" name="lalu_harga[{{$detail->kd_produk}}]" class="lalu_harga" value="0"></td>
                                <td style="text-align: right;">{{ 0 }}</td>
                                <td style="text-align: center;" class="saatini_btg1">{{ 0 }}<input type="hidden" name="saatini_btg[{{$detail->kd_produk}}]" value="0"></td>
                                <td style="text-align: center;" class="saatini_btg2">{{ 0 }}</td>
                                <td style="text-align: center;" class="saatini_ton1">{{ 0 }}<input type="hidden" name="saatini_ton[{{$detail->kd_produk}}]" value="0"></td>
                                <td style="text-align: center;" class="saatini_ton2">{{ 0 }}</td>
                                <td style="text-align: right;" class="saatini_harga1">{{ number_format(0) }}<input type="hidden" name="saatini_harga[{{$detail->kd_produk}}]" value="0"></td>
                                <td style="text-align: center;" class="saatini_harga2">{{ 0 }}%</td>
                                <td style="text-align: center;" class="sd_saatini_btg1">{{ 0 }}</td>
                                <td style="text-align: center;" class="sd_saatini_btg2">{{ 0 }}</td>
                                <td style="text-align: center;" class="sd_saatini_ton1">{{ 0 }}</td>
                                <td style="text-align: center;" class="sd_saatini_ton2">{{ 0 }}</td>
                                <td style="text-align: right;" class="sd_saatini_harga1">{{ number_format(0) }}</td>
                                <td style="text-align: center;" class="sd_saatini_harga2">{{ 0 }}%</td>
                                <td style="text-align: center;" class="ss_saatini_btg1">{{ 0 }}</td>
                                <td style="text-align: center;" class="ss_saatini_btg2">{{ 0 }}</td>
                                <td style="text-align: center;" class="ss_saatini_ton1">{{ 0 }}</td>
                                <td style="text-align: center;" class="ss_saatini_ton2">{{ 0 }}</td>
                                <td style="text-align: right;" class="ss_saatini_harga1">{{ number_format(0) }}</td>
                                <td style="text-align: center;" class="ss_saatini_harga2">{{ 0 }}%</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot class="bg-primary">
                    <tr style="vertical-align: middle;">
                        <td class="text-center" colspan="4">Jumlah</td>
                        <td colspan="4" style="text-align: right;" id="sp3_total">{{ number_format($total_sp3) }}</td>
                        <td colspan="5" style="text-align: right;" id="lalu_total1">{{ number_format(0) }}</td>
                        <td style="text-align: right;" id="lalu_total2">{{ number_format(0) }}%</td>
                        <td colspan="5" style="text-align: right;" id="saatini_total1">{{ number_format(0) }}</td>
                        <td style="text-align: right;" id="saatini_total2">{{ number_format(0) }}%</td>
                        <td colspan="5" style="text-align: right;" id="sd_saatini_total1">{{ number_format(0) }}</td>
                        <td style="text-align: right;" id="sd_saatini_total2">{{ number_format(0) }}%</td>
                        <td colspan="5" style="text-align: right;" id="ss_saatini_total1">{{ number_format(0) }}</td>
                        <td style="text-align: right;" id="ss_saatini_total2">{{ number_format(0) }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-2">
        <label class="form-label">Jumlah</label>
    </div>
    <div class="col-10">
        {!! Form::text('jumlah', null, ['class'=>'form-control', 'id'=>'jumlah', 'readonly']) !!}
    </div>
    <div class="col-2 mt-2">
        <label class="form-label">Terbilang</label>
    </div>
    <div class="col-10 mt-2">
        {!! Form::text('terbilang', null, ['class'=>'form-control', 'id'=>'terbilang', 'readonly']) !!}
    </div>
    <div class="form-group col-12 mt-2">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" id="catatan" rows="5" class="col-md-12">{{ $data->catatan ?? '' }}</textarea>
    </div>
</div>
@include('pages.bapp.modal_saat_ini')
<style>
    .fix-column {
        position: absolute;
    }
    tr.pake-border-v td {
        border-right: solid 1px black;
        border-bottom: solid 1px black;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        @if (in_array($mode, ['show']))
            calculateSisa();
            calculateTotal();
        @endif
        $('.form-select-solid').select2();
        $('#pihak_pertama').on('change', function(){
            $("#pihak_pertama_jabatan").val($("#pihak_pertama option:selected").attr('data-jabatan'));
        });

        $(".datepicker").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            autoApply: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        $('.edit-saatini').on('click', function(){
            initModalSaatIni($(this));
            $('#modal_saatini').modal('toggle');
        });
    });

    function initModalSaatIni(el){
        $(".modal-text").val("");
        $(".modal-text").attr('data-max', 0);
        $("#modal_for").val("add");
        $("#modal_saatini_btn").text("Simpan");

        console.log(el.attr('data-row'));
        $('#modal_tr').val(el.attr('data-row'));

        // set max volume btg
        var btg_sp3 = el.closest("tr").find("input.sp3_btg").val();
        var btg_lalu = el.closest("tr").find("input.lalu_btg").val();
        $("#modal_vol_btg").attr('data-max', btg_sp3 - btg_lalu);
        $("#modal_vol_btg").attr('data-sp3', btg_sp3);
        // set max volume ton
        var ton_sp3 = el.closest("tr").find("input.sp3_ton").val();
        var ton_lalu = el.closest("tr").find("input.lalu_ton").val();
        $("#modal_vol_ton").attr('data-max', ton_sp3 - ton_lalu);
        $("#modal_vol_ton").attr('data-sp3', ton_sp3);
        // set max harga
        var harga_sp3 = el.closest("tr").find("input.sp3_harsat").val();
        $("#modal_harga").attr('data-max', harga_sp3);
        $("#modal_harga").attr('data-sp3', harga_sp3);
    }

    function calculateTotal(){
        var sp3 = parseFloat($("#sp3_total").text().replace(/[^0-9\.]/g,''));
        var lalu = parseFloat($("#lalu_total1").text().replace(/[^0-9\.]/g,''));

        var saatini = 0;
        $("[name^=saatini_harga]").each(function(el){
            var h = parseFloat($(this).val());
            saatini += h;
        });
        $("#saatini_total1").text(currencyFormat(saatini.toString()));
        $("#saatini_total2").text((saatini / sp3 * 100).toFixed(0) + "%");
        $("#sd_saatini_total1").text(currencyFormat((saatini + lalu).toString()));
        $("#sd_saatini_total2").text(((saatini + lalu) / sp3 * 100).toFixed(0) + "%");
        $("#ss_saatini_total1").text(currencyFormat((sp3 - (saatini + lalu)).toString()));
        $("#ss_saatini_total2").text(((sp3 - (saatini + lalu)) / sp3 * 100).toFixed(0) + "%");

        $("#jumlah").val(currencyFormat((saatini + lalu).toString()))
        getTerbilang(saatini + lalu);
    }

    function calculateSisa(){
        $("#tbody-produks tr").each(function(el){
            var kode = $(this).find("input.sp3_produk").val();
            var satuan = $("#tr-" + kode).find("input.sp3_satuan").val();
            var harsat = $("#tr-" + kode).find("input.sp3_harsat").val();
            var ttl_sp3 = $("#tr-" + kode).find("input.sp3_ttl").val();
            var btg = $("#tr-" + kode).find("input.sp3_btg").val();
            var ton = $("#tr-" + kode).find("input.sp3_ton").val();

            var lalu_btg = parseFloat($("#tr-" + kode).find('input.lalu_btg').val());
            var lalu_ton = parseFloat($("#tr-" + kode).find('input.lalu_ton').val());
            var lalu_harga = parseFloat($("#tr-" + kode).find('input.lalu_harga').val());

            var saatini_btg = parseFloat($("#tr-" + kode).find('input.saatini_btg').val());
            var saatini_ton = parseFloat($("#tr-" + kode).find('input.saatini_ton').val());
            var saatini_harga = parseFloat($("#tr-" + kode).find('input.saatini_harga').val());

            // update s/d
            var sd_btg = (parseFloat(saatini_btg) + lalu_btg);
            var sd_ton = (parseFloat(saatini_ton) + lalu_ton).toFixed(2);
            var sd_harga = (saatini_harga + lalu_harga);
            $("#tr-" + kode).find('td.sd_saatini_btg1').html(currencyFormat(sd_btg.toString()));
            $("#tr-" + kode).find('td.sd_saatini_btg2').html(currencyFormat((sd_btg / btg * 100).toFixed(0)) + "%");
            $("#tr-" + kode).find('td.sd_saatini_ton1').html(currencyFormat(sd_ton.toString()));
            $("#tr-" + kode).find('td.sd_saatini_ton2').html(currencyFormat((sd_ton / ton * 100).toFixed(0)) + "%");
            $("#tr-" + kode).find('td.sd_saatini_harga1').html(currencyFormat(sd_harga.toString()));
            $("#tr-" + kode).find('td.sd_saatini_harga2').html(currencyFormat((sd_harga / ttl_sp3 * 100).toFixed(0)) + "%");

            // update sisa
            var ss_btg = btg - sd_btg;
            var ss_ton = (ton - sd_ton).toFixed(2);
            var ss_harga = ttl_sp3 - sd_harga;
            $("#tr-" + kode).find('td.ss_saatini_btg1').html(currencyFormat(ss_btg.toString()));
            $("#tr-" + kode).find('td.ss_saatini_btg2').html(currencyFormat((ss_btg / btg * 100).toFixed(0)) + "%");
            $("#tr-" + kode).find('td.ss_saatini_ton1').html(currencyFormat(ss_ton.toString()));
            $("#tr-" + kode).find('td.ss_saatini_ton2').html(currencyFormat((ss_ton / ton * 100).toFixed(0)) + "%");
            $("#tr-" + kode).find('td.ss_saatini_harga1').html(currencyFormat(ss_harga.toString()));
            $("#tr-" + kode).find('td.ss_saatini_harga2').html(currencyFormat((ss_harga / ttl_sp3 * 100).toFixed(0)) + "%");
        });

    }

    function getTerbilang(nilai){
        let data = {
            '_token': '{{ csrf_token() }}',
            'nilai': nilai
        };
        $.ajax({
            url: "{{ route('bapp.fetch-terbilang') }}",
            type: "POST",
            data: data,
            dataType: 'json',
            beforeSend: function() {
                blockUI.block();
            },
            complete: function() {
                blockUI.release();
            },
            success: function(result) {
                $('#terbilang').val(result.terbilang + " Rupiah");

                // box2();
            },
            error: function(result) {
                blockUI.release();
                alert(result.responseJSON.message)
            }
        });
    }
</script>
