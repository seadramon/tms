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
                            $sp3dVolBtg     = ($spk_d[$pesanan->kd_produk_konfirmasi] ?? null) ? $spk_d[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_akhir; }) : 0;
                            $sp3dVolTon     = ($spk_d[$pesanan->kd_produk_konfirmasi] ?? null) ? $spk_d[$pesanan->kd_produk_konfirmasi]->sum(function ($item) { return $item->first()->vol_ton_akhir; }) : 0;
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
                        {!! Form::text('tgl_spk', $spk ? date('d-m-Y', strtotime($spk->tgl_spk)) : date('d-m-Y'), ['class'=>'form-control datepicker', 'id'=>'tgl_spk']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" style="display: block">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">Nama Pihak Pertama</label>
                {!! Form::text('pihak1', $spk->pihak1 ?? "", ['class'=>'form-control', 'id'=>'pihak1']) !!}
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">Nama Pihak Kedua</label>
                {!! Form::text('pihak2', $spk->pihak2 ?? $trader->pimpinan_nama ?? "", ['class'=>'form-control', 'id'=>'pihak2']) !!}
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">Jabatan Pihak Pertama</label>
                {!! Form::text('pihak1_jabatan', $spk->pihak1_jabatan ?? "", ['class'=>'form-control', 'id'=>'pihak1_jabatan']) !!}
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">Jabatan Pihak Kedua</label>
                {!! Form::text('pihak2_jabatan', $spk->pihak2_jabatan ?? $trader->pimpinan_jabatan ?? "", ['class'=>'form-control', 'id'=>'pihak2_jabatan']) !!}
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">Anggaran Dasar Pihak Pertama</label>
                <div class="" data-bs-theme="light">
                    <textarea name="pihak1_ket" class="ckeditor" id="kt_docs_ckeditor_classic">
                        @if ($spk)
                            {!! $spk->pihak1_ket !!}
                        @else
                            <p style="text-align: justify;">1Suatu Perseroan Terbatas yang tunduk pada hukum Negara Republik Indonesia, berkedudukan di Jakarta Timur dan beralamat di Gedung WIKA Tower 1, Jln. D.I. Panjaitan Kav. 9, Jati Negara, Jakarta Timur, Indonesia, 13340, didirikan berdasarkan Hukum Negara Republik Indonesia, berdasarkan Anggaran Dasar PT Wijaya Karya Beton Tbk., No. 44 tertanggal 11 Maret 1997, yang dibuat dihadapan Achmad Bajumi, S.H., pengganti dari Imas Fatimah, S.H., Notaris di Jakarta, yang telah beberapa kali diubah dan terakhir kali diubah dengan Akta Perubahan Anggaran Dasar No. 72 tanggal 30 Mei 2017 dibuat dihadapan Ir. Nanette Cahyanie Handari Adi Warsito S.H., M.Kn., Notaris di Jakarta Selatan dan telah memperoleh persetujuan Kementerian Hukum dan HAM RI No. AHU-0011827.AH.01.02.Tahun 2017 tanggal 31 Mei 2017, dalam hal ini diwakili oleh {header:APP1_NAMA} selaku {header:APP1_JBT}, bertindak untuk dan atas nama PT Wijaya Karya Beton Tbk. Selanjutnya dalam Perjanjian ini disebut &ldquo;PIHAK KESATU&rdquo;</p>
                        @endif
                    </textarea>
                </div>
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">Anggaran Dasar Pihak Kedua</label>
                <div class="" data-bs-theme="light">
                    <textarea name="pihak2_ket" class="ckeditor" id="kt_docs_ckeditor_classic1">
                        @if ($spk)
                            {!! $spk->pihak2_ket !!}
                        @else
                            <p style="text-align: justify;">nulldalam hal ini diwakili oleh {header:APP2_NAMA} selaku {header:APP2_JBT}, bertindak untuk dan atas nama {header:NAMA_VENDOR}. Selanjutnya dalam Perjanjian ini disebut &ldquo;PIHAK KEDUA&rdquo;</p>
                        @endif
                    </textarea>
                </div>
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">No BAN</label>
                {!! Form::select('no_ban', $ban, $spk->no_ban ?? null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'no_ban'], $opt_ban) !!}
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Tanggal BAN</label>
                {!! Form::text('tgl_ban', $spk ? date('d-m-Y', strtotime($spk->tgl_ban)) : null, ['class'=>'form-control', 'id' => 'tgl_ban', 'readonly']) !!}
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">PIC</label>
                <select class="form-control search-pic" name="pic[]" id="pic" multiple required>
                    @if (in_array($mode, ['edit', 'show']))
                        @foreach (($spk->pic ?? []) as $item)
                            <option selected value="{{$item->employee_id}}">{{$item->employee_id}} - {{$item->employee->fullname}}</option>
                            
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group col-lg-6">
                <label class="form-label">Spesifikasi</label>
                {!! Form::select('spesifikasi', $spesifikasi, $spk->spesifikasi ?? null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'spesifikasi', 'required']) !!}
            </div>

            {{-- <div class="form-group col-lg-6">
                <label class="form-label">&nbsp;</label>
            </div> --}}
            <div class="form-group col-lg-3">
                <label class="form-label">Tanggal Penyerahan</label>
                <div class="col-lg-12">
                    <div class="input-group date">
                        {!! Form::text('jadwal1', $spk ? date('d-m-Y', strtotime($spk->jadwal1)) : null, ['class'=>'form-control datepicker', 'id'=>'jadwal1']) !!}
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
                        {!! Form::text('jadwal2', $spk ? date('d-m-Y', strtotime($spk->jadwal2)) : null, ['class'=>'form-control datepicker', 'id'=>'jadwal2']) !!}
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
        </div>
        @php
            $readonly = $sat_harsat != 'tonase';
        @endphp
        <div class="separator separator-dashed border-primary my-10"></div>
        <h3>Detail Pekerjaan</h3>
        <div class="form-group" style="margin-top: 20px">
            @if (!in_array($mode, ['show']))
                <button type="button" class="btn btn-light-primary" id="add-pekerjaan">
                    <i class="la la-plus"></i>Tambah
                </button>
            @endif
        </div>
        <div class="hover-scroll-overlay-y h-400px">
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
                    @if (in_array($mode, ['edit', 'show']))
                        @foreach ($spk->spk_d as $item)
                            <tr>
                                <td><input name="unit[]" class="unit" type="hidden" value="{{ $item->pat_to }}">{{ $item->pat->ket }}</td> 
                                @if ($pekerjaan == 'laut')
                                    <td><input name="pelabuhan_asal[]" class="pelabuhan_asal" type="hidden" value="{{ $item->port_asal }}">{{ $item->port_asal }}</td> 
                                    <td><input name="pelabuhan_tujuan[]" class="pelabuhan_tujuan" type="hidden" value="{{ $item->port_tujuan }}">{{ $item->port_tujuan }}</td> 
                                    <td><input name="site[]" class="site" type="hidden" value="{{ $item->site }}">{{ $item->site }}</td> 
                                @endif
                                <td><input name="tipe[]" class="tipe" type="hidden" value="{{ $item->kd_produk }}">{{ $item->kd_produk }}<br>{{ $item->produk->tipe }}</td> 
                                <td><input name="jarak[]" class="jarak" type="hidden" value="{{ $item->jarak }}">{{ $item->jarak }}</td> 
                                <td><input name="vol_btg[]" class="vol_btg" type="hidden" value="{{ $item->vol_btg }}">{{ $item->vol_btg }}</td> 
                                <td><input name="vol_ton[]" class="vol_ton" type="hidden" value="{{ $item->vol_ton }}">{{ $item->vol_ton }}</td> 
                                @if ($sat_harsat == 'tonase')
                                    <td><input name="satuan[]" class="satuan" type="hidden" value="{{ $item->satuan }}">{{ $item->satuan }}</td> 
                                @else
                                    <td><input name="ritase[]" class="ritase" type="hidden" value="{{ $item->ritase }}">{{ $item->ritase }}</td> 
                                @endif
                                <td><input name="harsat[]" class="harsat" type="hidden" value="{{ $item->harsat }}">{{ number_format($item->harsat, 2) }}</td> 
                                <td><input name="jumlah[]" class="input-jumlah" type="hidden" value="{{ $item->total }}">{{ number_format($item->total, 2) }}</td> 
                                <td>
                                    @if ($mode != 'show')
                                        <button class="btn btn-danger btn-sm delete_pekerjaan me-1 mb-1" style="padding: 5px 6px;"><span class="bi bi-trash"></span></button><button class="btn btn-warning btn-sm edit_pekerjaan" style="padding: 5px 6px;"><span class="bi bi-pencil-square"></span></button>
                                    @endif
                                </td>
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
                        <td colspan="2">{!! Form::select('ppn', $ppn, in_array($mode, ['edit', 'show']) ? $spk->ppn : null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'ppn']) !!}</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $pekerjaan == 'laut' ? 9 : 6 }}" style="text-align: right; font-weight: bold;">PPH</td>
                        <td colspan="2">{!! Form::select('pph', $pph, in_array($mode, ['edit', 'show']) ? $spk->pph_id.'|'.$spk->pph : null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'pph']) !!}</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $pekerjaan == 'laut' ? 9 : 6 }}" style="text-align: right; font-weight: bold;">Total</td>
                        <td colspan="2" id="total" style="text-align: right; font-weight: bold;"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="separator separator-dashed border-primary my-10"></div>

        <div class="form-group">
            <label class="form-label">Harga Termasuk</label>
            {!! Form::text('harga_include', collect($spk->data['harga_include'] ?? [])->implode(','), ['class'=>'form-control', 'id' => "harga_include"]) !!}
        </div>

        <div class="separator separator-dashed border-primary my-10"></div>

        
        <div id="pasal" data-index="{{$spk->spk_pasal->count()}}">
            <div class="form-group">
                <div class="accordion" id="kt_accordion__">
                    <div data-repeater-list="pasal">
                        @if (in_array($mode, ['edit', 'show']))
                            @foreach ($spk->spk_pasal as $pasal)
                                <div data-repeater-item>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="pasal-header-{{ $pasal->pasal }}">
                                            <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pasal-body-{{ $pasal->pasal }}" aria-expanded="true" aria-controls="pasal-body-{{ $pasal->pasal }}">
                                                Pasal {{ $pasal->pasal }}
                                            </button>
                                        </h2>
                                        <div id="pasal-body-{{ $pasal->pasal }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                                            <div class="accordion-body">
                                                <div class="form-group col-12">
                                                    <label class="form-label">Judul</label>
                                                    {!! Form::text('pasal_judul', $pasal->judul, ['class'=>'form-control', 'id'=>'pasal_judul' . $pasal->judul]) !!}
                                                </div>
                                                <div class="form-group col-12" data-bs-theme="light">
                                                    <textarea name="pasal_isi" class="ckeditor" id="pasal_ckeditor{{ $pasal->pasal }}">
                                                        {!! $pasal->keterangan !!}
                                                    </textarea>
                                                </div>
                                                {{-- <div class="form-group col-12">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-md btn-light-danger mt-md-8">
                                                        <i class="la la-trash-o"></i>Hapus
                                                    </a>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else    
                            <div data-repeater-item>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="kt_accordion_1_header_1">
                                        <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="true" aria-controls="kt_accordion_1_body_1">
                                            Pasal 1
                                        </button>
                                    </h2>
                                    <div id="kt_accordion_1_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                                        <div class="accordion-body">
                                            <div class="form-group col-12">
                                                <label class="form-label">Judul</label>
                                                {!! Form::text('pasal_judul', '', ['class'=>'form-control', 'id'=>'pasal_judul1']) !!}
                                            </div>
                                            <div class="form-group col-12" data-bs-theme="light">
                                                <textarea name="pasal_isi" class="ckeditor" id="">
                                                
                                                </textarea>
                                            </div>
                                            <div class="form-group col-12">
                                                <a href="javascript:;" data-repeater-delete class="btn btn-md btn-light-danger mt-md-8">
                                                    <i class="la la-trash-o"></i>Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group" style="margin-top: 20px">
                <div class="col-md-3">
                    @if (!in_array($mode, ['show']))
                        <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                            <i class="la la-plus"></i>Tambah
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer" style="text-align: right;">
        <a href="{{ route('spk.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
        @if (!in_array($mode, ['show']))
            <input type="submit" class="btn btn-success" id="btn-sumbit" value="Simpan">
        @endif
    </div>
</div>

@include('pages.spk.modal_detail_pekerjaan')

<script type="text/javascript">
    var pasal_ckeditor = [];
    var edit_ = false;
    @if(in_array($mode, ['edit', 'show']))
        edit_ = true;
    @endif
    $(document).ready(function() {
        @if(in_array($mode, ['edit', 'show']))
            calculateTotal()
            initCkEditExisting()
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

    ClassicEditor.create(document.querySelector('#kt_docs_ckeditor_classic')).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
    ClassicEditor.create(document.querySelector('#kt_docs_ckeditor_classic1')).then(editor => { console.log(editor); }).catch(error => { console.error(error); });

    $('#pasal').repeater({
        initEmpty: !edit_,

        show: function () {
            $(this).slideDown();
            
            var index = parseInt($("#pasal").attr('data-index')) + 1;
            $(this).find('.accordion-header').attr('id', 'pasal-header-' + index);
            $(this).find('.accordion-button').attr('data-bs-target', '#pasal-body-' + index);
            $(this).find('.accordion-button').attr('aria-controls', '#pasal-body-' + index);
            $(this).find('.accordion-button').text("Pasal " + index);
            $(this).find('.accordion-collapse').attr('id', 'pasal-body-' + index);
            // reinit ckeditor
            // $(this).find('.ck-editor').remove();
            $(this).find('.ckeditor').attr('id', 'pasal_ckeditor' + index);
            ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index)).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
            $("#pasal").attr('data-index', index);
            reOrganizeItemPasal();
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
            reOrganizeItemPasal();
        },
        ready: function (setIndexes) {
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
    $('#no_ban').on('change', function(){
        $("#tgl_ban").val($("#no_ban option:selected").attr('data-tgl'));
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

    function reOrganizeItemPasal(){
        var index = 1;
        $("div[data-repeater-item]").each(function(){
            // $(this).find('.accordion-header').attr('id', 'pasal-header-' + index);
            // $(this).find('.accordion-button').attr('data-bs-target', '#pasal-body-' + index);
            // $(this).find('.accordion-button').attr('aria-controls', '#pasal-body-' + index);
            $(this).find('.accordion-button').text("Pasal " + index);
            // $(this).find('.accordion-collapse').attr('id', 'pasal-body-' + index);
            // reinit ckeditor
            // $(this).find('.ck-editor').remove();
            // $(this).find('.ckeditor').attr('id', 'pasal_ckeditor' + index);
            // ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index)).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
            index++;
        });
    }
    function initCkEditExisting(){
        var index = 1;
        $("div[data-repeater-item]").each(function(){
            ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index)).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
            index++;
        });
    }
</script>