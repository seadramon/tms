<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Detail Pesanan NPP</h3>
    </div>

    <div class="card-body">
        <div class="h-400px">
            <table id="tabel_detail_pesanan" class="table table-row-bordered text-center">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle; text-align: left">Nama / Tipe Produk</th>
                        <th colspan="2">Pesanan</th>
                        <th colspan="2">Total SP3/SPK Sebelumnya</th>
                        <th colspan="2">Volume Sisa</th>
                    </tr>
                    
                    <tr>
                        <th>Vol (Btg)</th>
                        <th>Vol (Ton)</th>
                        <th>Vol (Btg)</th>
                        <th>Vol (Ton)</th>
                        <th>Vol (Btg)</th>
                        <th>Vol (Ton)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailPesanan as $key => $pesanan)
                        @php
                            $pesananVolBtg  = $pesanan->vSpprbRi->vol_spprb ?? 0;
                            $pesananVolTon  = ((float)$pesananVolBtg * (float)($pesanan->produk?->vol_m3 ?? 0) * 2.5) ?? 0;
                            $sp3dVolBtg     = ($sp3D[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_akhir; }) : 0;
                            $sp3dVolTon     = ($sp3D[$pesanan->kd_produk_konfirmasi] ?? null) ? $sp3D[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_ton_akhir; }) : 0;
                            $sisaVolBtg     = $pesananVolBtg - $sp3dVolBtg;
                            $sisaVolTon     = $pesananVolTon - $sp3dVolTon;
                        @endphp
                        
                        <tr>
                            <td style="text-align: left">{{ $pesanan->produk->tipe }} {{$pesanan->kd_produk_konfirmasi}}</td>
                            
                            <td>{{ $pesanan->vol_konfirmasi }}</td>
                            <td>{{ nominal($pesananVolTon) }}</td>
                            <td>{{ nominal($sp3dVolBtg) }}</td>
                            <td>{{ nominal($sp3dVolTon) }}</td>
                            <td>{{ nominal($sisaVolBtg) }}</td>
                            <td>{{ nominal($sisaVolTon) }}</td>
                        </tr>
    
                        <input type="hidden" id="pesanan_vol_btg_max_{{ $key }}" row-id={{ $key }} value="{{ (float)$pesanan->vol_konfirmasi ?? 0 }}">
                        <input type="hidden" class="pesanan_kd_produk" row-id={{ $key }} value="{{ $pesanan->produk?->kd_produk }}">
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- <div class="hover-scroll-overlay-y h-400px">
        </div> --}}

        <div class="separator separator-dashed border-primary my-10"></div>

        <div class="row">
            <div class="form-group col-lg-12">
                <label class="form-label">Proyek</label>
                {!! Form::text('proyek', $npp->nama_proyek, ['class'=>'form-control', 'id'=>'proyek', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Pelanggan</label>
                {!! Form::text('pelanggan', $npp->nama_pelanggan, ['class'=>'form-control', 'id'=>'pelanggan', 'readonly']) !!}

            </div>
            
            <div class="form-group col-lg-3">
                <label class="form-label">Tujuan</label>
                {!! Form::text('region', $npp->infoPasar?->region?->kabupaten_name . ', ' . $npp->infoPasar?->region?->kecamatan_name, ['class'=>'form-control', 'id'=>'region', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-3">
                <label class="form-label">Tanggal</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('tgl_sp3', $sp3 ? date('d-m-Y', strtotime($sp3->tgl_sp3)) : null, ['class'=>'form-control datepicker', 'id'=>'tgl_sp3']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" style="display: block">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">No BAN</label>
                {!! Form::select('no_ban', $ban, $sp3->no_ban ?? null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_ban']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">No Induk Kontrak</label>
                {!! Form::select('no_kontrak_induk', $kontrak, $sp3->no_kontrak_induk ?? null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_kontrak_induk']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Nama Pihak Kedua / Vendor</label>
                {!! Form::text('vendor', $trader->pimpinan_nama ?? "", ['class'=>'form-control', 'id'=>'vendor', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Jabatan</label>
                {!! Form::text('jabatan', $trader->pimpinan_jabatan ?? "", ['class'=>'form-control', 'id'=>'jabatan', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">PIC</label>
                <select class="form-control search-pic" name="pic[]" id="pic" multiple required>
                    @if ($mode == 'edit')
                        @foreach ($sp3->pic as $item)
                            <option selected value="{{$item->employee_id}}">{{$item->employee_id}} - {{$item->employee->fullname}}</option>
                            
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Spesifikasi</label>
                {!! Form::select('spesifikasi', $spesifikasi, $sp3->spesifikasi ?? null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'spesifikasi', 'required']) !!}
            </div>

            {{-- <div class="form-group col-lg-6">
                <label class="form-label">&nbsp;</label>
            </div> --}}
            <div class="form-group col-lg-3">
                <label class="form-label">Tanggal Penyerahan</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('jadwal1', $sp3 ? date('d-m-Y', strtotime($sp3->jadwal1)) : null, ['class'=>'form-control datepicker', 'id'=>'jadwal1']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" style="display: block">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-3">
                <label class="form-label">&nbsp;</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('jadwal2', $sp3 ? date('d-m-Y', strtotime($sp3->jadwal2)) : null, ['class'=>'form-control datepicker', 'id'=>'jadwal2']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" style="display: block">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Kondisi Penyerahan</label>
                {!! Form::text('kondisi_penyerahan', $kondisiPenyerahanDipilih, ['class'=>'form-control', 'id'=>'kondisi_penyerahan', 'readonly']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Estimasi Total Ritase</label>
                {!! Form::text('rit', $sp3->rit ?? 0, ['class'=>'form-control decimal', 'id' => $sat_harsat != 'tonase' ? 'est-rit' : 'rit', 'required']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Jarak</label>
                {!! Form::number('jarak_pesanan', $mode == 'edit' ? $sp3->jarak_km : $jarak, ['class'=>'form-control', 'id'=>'jarak_pesanan', 'required']) !!}
            </div>
            @if ($sat_harsat == 'ritase')
                <div class="form-group col-lg-6">
                    <label class="form-label">Harga Satuan Ritase</label>
                    {!! Form::text('harga_satuan_ritase', $sp3->data['harga_satuan_ritase'] ?? 0, ['class'=>'form-control decimal', 'id'=>'harga_satuan_ritase']) !!}
                </div>
            @endif
        </div>
        @php
            $readonly = $sat_harsat != 'tonase';
        @endphp
        <div class="separator separator-dashed border-primary my-10"></div>
        <h3>Detail Pekerjaan</h3>
        <div class="form-group" style="margin-top: 20px">
            <button type="button" class="btn btn-light-primary" id="add-pekerjaan">
                <i class="la la-plus"></i>Tambah
            </button>
        </div>
        <div class="h-400px">
            <table id="tabel_detail_pekerjaan" class="table table-row-bordered text-center">
                <thead>
                    <tr style="font-weight: bold;">
                        <th>Unit</th>
                        @if ($pekerjaan == 'laut')
                            <th>Pelabuhan Asal</th>
                            <th>Pelabuhan Tujuan</th>
                            <th>Site</th>
                        @endif
                        <th>Tipe</th>
                        <th>Jarak</th>
                        <th>Vol(Btg)</th>
                        <th>Vol(Ton)</th>
                        @if ($sat_harsat == 'tonase')
                            <th>Satuan</th>
                        @else
                            <th>Ritase</th>
                        @endif
                        <th>Harsat</th>
                        <th>Jumlah</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tbody-pekerjaan">
                    @if ($mode == 'edit')
                        @foreach ($sp3->sp3D as $item)
                            <tr>
                                <td><input name="unit[]" class="unit" type="hidden" value="{{ $item->pat_to }}">{{ $item->pat->ket }}</td> 
                                @if ($pekerjaan == 'laut')
                                    <td><input name="pelabuhan_asal[]" class="pelabuhan_asal" type="hidden" value="{{ $item->port_asal }}">{{ $item->port_asal }}</td> 
                                    <td><input name="pelabuhan_tujuan[]" class="pelabuhan_tujuan" type="hidden" value="{{ $item->port_tujuan }}">{{ $item->port_tujuan }}</td> 
                                    <td><input name="site[]" class="site" type="hidden" value="{{ $item->site }}">{{ $item->site }}</td> 
                                @endif
                                <td><input name="tipe[]" class="tipe" type="hidden" value="{{ $item->kd_produk }}">{{ $item->kd_produk }}<br>{{ $item->produk->tipe }}</td> 
                                <td><input name="jarak[]" class="jarak" type="hidden" value="{{ $item->jarak_km }}">{{ $item->jarak_km }}</td> 
                                <td><input name="vol_btg[]" class="vol_btg" type="hidden" value="{{ $item->vol_awal }}">{{ $item->vol_awal }}</td> 
                                <td><input name="vol_ton[]" class="vol_ton" type="hidden" value="{{ $item->vol_ton_awal }}">{{ $item->vol_ton_awal }}</td> 
                                @if ($sat_harsat == 'tonase')
                                    <td><input name="satuan[]" class="satuan" type="hidden" value="{{ $item->sat_harsat }}">{{ $item->sat_harsat }}</td> 
                                @else
                                    <td><input name="ritase[]" class="ritase" type="hidden" value="{{ $item->ritase }}">{{ $item->ritase }}</td> 
                                @endif
                                <td><input name="harsat[]" class="harsat" type="hidden" value="{{ $item->harsat_awal }}">{{ number_format($item->harsat_awal, 2) }}</td> 
                                <td><input name="jumlah[]" class="input-jumlah" type="hidden" value="{{ $item->total }}">{{ number_format($item->total, 2) }}</td> 
                                <td><button class="btn btn-danger btn-sm delete_pekerjaan me-1 mb-1" style="padding: 5px 6px;"><span class="bi bi-trash"></span></button><button class="btn btn-warning btn-sm edit_pekerjaan" style="padding: 5px 6px;"><span class="bi bi-pencil-square"></span></button></td>
                            </tr>
                        @endforeach
                    @endif              
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="{{ $pekerjaan == 'laut' ? 9 : 6 }}" style="text-align: right; font-weight: bold;">Subtotal</td>
                        <td colspan="2" id="subtotal" style="text-align: right; font-weight: bold;"></td>
                    </tr>
                    <tr>
                        <td colspan="{{ $pekerjaan == 'laut' ? 9 : 6 }}" style="text-align: right; font-weight: bold;">PPN</td>
                        <td colspan="2">{!! Form::select('ppn', $ppn, $mode == 'edit' ? $sp3->ppn*100 : null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'ppn']) !!}</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $pekerjaan == 'laut' ? 9 : 6 }}" style="text-align: right; font-weight: bold;">PPH</td>
                        <td colspan="2">{!! Form::select('pph', $pph, $mode == 'edit' ? $sp3->pph_id.'|'.$sp3->pph : null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'pph']) !!}</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $pekerjaan == 'laut' ? 9 : 6 }}" style="text-align: right; font-weight: bold;">Total</td>
                        <td colspan="2" id="total" style="text-align: right; font-weight: bold;"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="separator separator-dashed border-primary my-10"></div>

        <h3>Material Tambahan</h3>
        <div id="material_tambahan">
            <div class="form-group">
                <div data-repeater-list="material_tambahan">
                    @if ($mode == 'edit')
                        @foreach ($sp3->sp3D2 as $d2)
                            <div data-repeater-item>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label class="form-label">Material</label>
                                        {!! Form::text('material', $d2->material, ['class'=>'form-control', 'required']) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Spesifikasi</label>
                                        {!! Form::text('spesifikasi', $d2->spesifikasi, ['class'=>'form-control', 'required']) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Volume</label>
                                        {!! Form::number('volume', $d2->volume, ['class'=>'form-control', 'required']) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:;" data-repeater-delete class="btn btn-md btn-light-danger mt-md-8">
                                            <i class="la la-trash-o"></i>Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else    
                        <div data-repeater-item>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="form-label">Material</label>
                                    {!! Form::text('material', null, ['class'=>'form-control', 'required']) !!}
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Spesifikasi</label>
                                    {!! Form::text('spesifikasi', null, ['class'=>'form-control', 'required']) !!}
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Volume</label>
                                    {!! Form::number('volume', null, ['class'=>'form-control', 'required']) !!}
                                </div>
                                <div class="col-md-3">
                                    <a href="javascript:;" data-repeater-delete class="btn btn-md btn-light-danger mt-md-8">
                                        <i class="la la-trash-o"></i>Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        
            <div class="form-group" style="margin-top: 20px">
                <div class="col-md-3">
                    <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                        <i class="la la-plus"></i>Tambah
                    </a>
                </div>
            </div>
        </div>

        <div class="separator separator-dashed border-primary my-10"></div>

        @if ($pekerjaan == 'laut')
            <div class="form-group">
                <label class="form-label">Harga Termasuk</label>
                {!! Form::text('harga_include', collect($sp3->data['harga_include'] ?? [])->implode(','), ['class'=>'form-control', 'id' => "harga_include"]) !!}
            </div>
        @else
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="5" class="col-md-12">{{ $sp3->keterangan }}</textarea>
            </div>
        @endif

        <div class="separator separator-dashed border-primary my-10"></div>

        <div class="form-group">
            <label class="form-label">Dokumen Tagihan harus melampirkan :</label>
            
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Asli</th>
                        <th>Copy</th>
                        <th>&nbsp;</th>
                        <th></th>
                        <th>Asli</th>
                        <th>Copy</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $dokumen = $sp3 ? $sp3->dokumen->groupBy('dok_id') : [];
                    @endphp
                    @for ($i = 1; $i <= 9; $i+=2)
                        <tr>
                            <th>{{ $documents[$i] }}</th>
                            <td>
                                {!! Form::number('dokumen_asli['.$i.']', ($mode == 'edit' && ($dokumen[$i] ?? false)) ? $dokumen[$i]->first()->asli : null, ['class'=>'form-control']) !!}
                            </td>
                            <td>
                                {!! Form::number('dokumen_copy['.$i.']', ($mode == 'edit' && ($dokumen[$i] ?? false)) ? $dokumen[$i]->first()->asli : null, ['class'=>'form-control']) !!}
                            </td>
                            
                            <th>&nbsp;</th>

                            <th>{{ $documents[$i+1] }}</th>
                            <td>
                                {!! Form::number('dokumen_asli['.($i+1).']', ($mode == 'edit' && ($dokumen[$i+1] ?? false)) ? $dokumen[$i+1]->first()->asli : null, ['class'=>'form-control']) !!}
                            </td>
                            <td>
                                {!! Form::number('dokumen_copy['.($i+1).']', ($mode == 'edit' && ($dokumen[$i+1] ?? false)) ? $dokumen[$i+1]->first()->copy : null, ['class'=>'form-control']) !!}
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer" style="text-align: right;">
        <a href="{{ route('sp3.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
        <input type="submit" class="btn btn-success" id="btn-sumbit" value="Simpan">
    </div>
</div>

@include('pages.sp3.v2.modal_detail_pekerjaan')
<style>
    tbody {
        display:block;
        max-height: 300px;
        overflow-y: scroll;
    }
    tfoot, thead, tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
    tfoot, thead {
        width: calc( 100% - 10px ); 
    }
    table {
        width: 100%;
    }
</style>
<script type="text/javascript">
    var edit_ = false;
    @if($mode == 'edit')
        edit_ = true;
    @endif
    $(document).ready(function() {
        @if($mode == 'edit')
            calculateTotal()
        @endif

        var input1 = document.querySelector("#harga_include");
        new Tagify(input1);
        
        $('.form-select-solid').select2();
        
        if($("#kd_jpekerjaan").val() == 'darat'){
            $("#spesifikasi").parent().addClass('hidden');
        }else{
            $("#spesifikasi").parent().removeClass('hidden');
        }
    });

    $('#material_tambahan').repeater({
        initEmpty: !edit_,

        show: function () {
            $(this).slideDown();
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
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

    $('.search-pic').select2({
        placeholder: 'Cari...',
        ajax: {
            url: "{{ route('sp3.search-pic') }}",
            minimumInputLength: 2,
            dataType: 'json',
            cache: true,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.employee_id + ' - ' + item.first_name + ' ' + (item.last_name ?? ''),
                            id: item.employee_id
                        }
                    })
                };
            },
        }
    });

    $('#form-edit').on('submit', function(){
        $("#form-edit :disabled").removeAttr('disabled');
    });

    $('#add-pekerjaan').on('click', function(){
        resetModalPekerjaan()
        $('#modal_pekerjaan').modal('toggle');
    });

    $('#ppn, #pph').on('change', function(){
        calculateTotal();
    });

    $(document).on('click', '.delete_pekerjaan', function(event){
        event.preventDefault();
        $(this).parent().parent().remove();
        calculateTotal();
    });
    $(document).on('click', '.edit_pekerjaan', function(event){
        event.preventDefault();
        resetModalPekerjaan()

        $(this).parent().parent().addClass('editing');
        $("#modal_for").val("edit");
        $("#modal_pekerjaan_btn").text("Edit");

        $("#modal_unit").val($(this).parent().parent().find("input.unit").val()).trigger("change");
        $("#modal_site").val($(this).parent().parent().find("input.site").val()).trigger("change");
        $("#modal_pelabuhan_asal").val($(this).parent().parent().find("input.pelabuhan_asal").val()).trigger("change");
        $("#modal_pelabuhan_tujuan").val($(this).parent().parent().find("input.pelabuhan_tujuan").val()).trigger("change");
        $("#modal_tipe").val($(this).parent().parent().find("input.tipe").val()).trigger("change");
        $("#modal_jarak").val($(this).parent().parent().find("input.jarak").val());
        $("#modal_vol_btg").val($(this).parent().parent().find("input.vol_btg").val());
        $("#modal_vol_ton").val($(this).parent().parent().find("input.vol_ton").val());
        if($("#sat_harsat").val() == 'tonase'){
            $("#modal_satuan").val($(this).parent().parent().find("input.satuan").val()).trigger("change");
        }else{
            $("#modal_ritase").val($(this).parent().parent().find("input.ritase").val());
        }
        $("#modal_harsat").val($(this).parent().parent().find("input.harsat").val());
        $("#modal_harsat").trigger('keyup');
        $('#modal_pekerjaan').modal('toggle');
        // calculateTotal();
    });
    
    function calculateTotal(){
        var sum = 0;
        $('.input-jumlah').each(function() {
            sum += parseFloat($(this).val());
        });
        $("#subtotal").text(currencyFormat(sum.toFixed(2).toString()))
        var ppn = $("#ppn").val();
        var pph = $("#pph").val().split("|")[1];
        var ppn_ = 0;
        var pph_ = 0;
        if(ppn != "0"){
            ppn_ = sum * ppn / 100;
        }
        if(pph != "0"){
            pph_ = sum * pph / 100;
        }
        var total = (sum + ppn_ + pph_).toFixed(2);
        $("#total").text(currencyFormat(total.toString()));
    }

    function resetModalPekerjaan(){
        $(".modal-select2").val("").trigger("change");
        $(".modal-text").val("");
        $("#modal_for").val("add");
        $("#modal_pekerjaan_btn").text("Tambah");
        if($("#spesifikasi").val() == "DTD"){
            $("#modal_site").removeAttr('disabled');
        }else{
            $("#modal_site").attr('disabled', true);
        }
        $("#modal_jarak").val($("#jarak_pesanan").val());
        if($("#sat_harsat").val() != 'tonase'){
            $("#modal_vol_btg").val(1);
            $("#modal_vol_ton").val(1);
            $("#modal_ritase").val($("#est-rit").val());
            $("#modal_harsat").val($("#harga_satuan_ritase").val());
        }
    }
</script>