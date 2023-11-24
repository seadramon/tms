<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Detail Pesanan NPP</h3>
    </div>

    <div class="card-body">
        <div class="hover-scroll-overlay-y h-400px">
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
                        {!! Form::text('tgl_sp3', null, ['class'=>'form-control datepicker', 'id'=>'tgl_sp3']) !!}
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
                {!! Form::select('no_ban', $ban, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_ban']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">No Induk Kontrak</label>
                {!! Form::select('no_kontrak_induk', $kontrak, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_kontrak_induk']) !!}
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
                <select class="form-control search-pic" name="pic[]" id="pic" multiple required></select>
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Spesifikasi</label>
                {!! Form::select('sumber_daya', $sumber_daya, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'sumber_daya', 'required']) !!}
            </div>

            {{-- <div class="form-group col-lg-6">
                <label class="form-label">&nbsp;</label>
            </div> --}}
            <div class="form-group col-lg-3">
                <label class="form-label">Tanggal Penyerahan</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('jadwal1', null, ['class'=>'form-control datepicker', 'id'=>'jadwal1']) !!}
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
                        {!! Form::text('jadwal2', null, ['class'=>'form-control datepicker', 'id'=>'jadwal2']) !!}
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
                {!! Form::text('rit', 0, ['class'=>'form-control decimal', 'id' => $sat_harsat != 'tonase' ? 'est-rit' : 'rit', 'required']) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Jarak</label>
                {!! Form::number('jarak_pesanan', $jarak, ['class'=>'form-control', 'id'=>'jarak_pesanan', 'required']) !!}
            </div>
            @if ($sat_harsat == 'ritase')
            <div class="form-group col-lg-6">
                <label class="form-label">Harga Satuan Ritase</label>
                {!! Form::text('harga_satuan_ritase', 0, ['class'=>'form-control decimal', 'id'=>'harga_satuan_ritase']) !!}
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
        <div class="hover-scroll-overlay-y h-400px">
            <table id="tabel_detail_pekerjaan" class="table table-row-bordered text-center">
                <thead>
                    <tr style="font-weight: bold;">
                        <th>Unit</th>
                        <th>Pelabuhan Asal</th>
                        <th>Pelabuhan Tujuan</th>
                        <th>Site</th>
                        <th>Tipe</th>
                        <th>Jarak</th>
                        <th>Vol(Btg)</th>
                        <th>Vol(Ton)</th>
                        <th>Satuan</th>
                        <th>Harsat</th>
                        <th>Jumlah</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tbody-pekerjaan">              
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="9" style="text-align: right; font-weight: bold;">Subtotal</td>
                        <td colspan="2" id="subtotal" style="text-align: right; font-weight: bold;"></td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: right; font-weight: bold;">PPN</td>
                        <td colspan="2">{!! Form::select('ppn', $ppn, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'ppn']) !!}</td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: right; font-weight: bold;">PPH</td>
                        <td colspan="2">{!! Form::select('pph', $pph, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'pph']) !!}</td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: right; font-weight: bold;">Total</td>
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

        <div class="form-group">
            <label class="form-label">Harga Termasuk</label>
            {!! Form::text('harga_include', null, ['class'=>'form-control', 'id' => "harga_include"]) !!}
        </div>

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
                    <tr>
                        <th>Faktur / Invoice / Kwitansi</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>Packing List</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Faktur Pajak</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BAPB</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>SP3 / SPK</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BA Pemeriksaan / Opname</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Surat Jalan / SPtB</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>BA Pembayaran</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Rekap Surat Jalan / SPtB</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                        
                        <th>&nbsp;</th>

                        <th>Lembar Kendali Pembayaran</th>
                        <td>
                            {!! Form::number('dokumen_asli[]', null, ['class'=>'form-control']) !!}
                        </td>
                        <td>
                            {!! Form::number('dokumen_copy[]', null, ['class'=>'form-control']) !!}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer" style="text-align: right;">
        <a href="{{ route('sp3.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
        <input type="submit" class="btn btn-success" value="Simpan">
    </div>
</div>

@include('pages.sp3.v2.modal_detail_pekerjaan')

<script type="text/javascript">
    $(document).ready(function() {
        var input1 = document.querySelector("#harga_include");
        new Tagify(input1);
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

    $('#add-pekerjaan').on('click', function(){
        resetModalPekerjaan()
        $('#modal_pekerjaan').modal('toggle');
    });

    $('#ppn, #pph').on('change', function(){
        calculateTotal();
    });

    $(document).on('click', '.delete_pekerjaan', function(){
        $(this).parent().parent().remove();
        calculateTotal();
    });
    $(document).on('click', '.edit_pekerjaan', function(){
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
        $("#modal_satuan").val($(this).parent().parent().find("input.satuan").val()).trigger("change");
        $("#modal_harsat").val($(this).parent().parent().find("input.harsat").val());
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
        if($("#sumber_data").val() == "DTD"){
            $("#modal_site").removeAttr('disabled');
        }else{
            $("#modal_site").attr('disabled', true);
        }
    }
</script>