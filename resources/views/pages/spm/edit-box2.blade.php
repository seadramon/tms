<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Detail SPP</h3>
    </div>
    <form method="POST" action="{{ $source == 'edit' ? route('spm.store-edit') : route('spm.konfirmasi')  }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input class="d-none" name="no_spm" id="spm_select"/>
        <input class="d-none" name="no_spp" id="spp_select"/>
        <input class="d-none" name="pbb_muat" id="muat_select"/>
        <input class="d-none" name="jenis_spm" id="jenis_spm_select"/>
        <input class="d-none" name="tanggal" id="tanggal_select"/>

    <div class="card-body">
        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" vertical-align="center">Type</th>
                    <th colspan="2" class="text-center">SPP</th>
                    <th colspan="2" class="text-center">SPP Terdistribusi</th>
                    <th colspan="3" class="text-center">Volume Sisa</th>
                </tr>
                <tr class="table table-striped gy-7 gs-7">
                    <th class="text-center">Vol (Btg)</th>
                    <th class="text-center">Vol (Ton)</th>
                    <th class="text-center">Vol (Btg)</th>
                    <th class="text-center">Vol (Ton)</th>
                    <th class="text-center">Vol (Btg)</th>
                    <th class="text-center">Vol (Ton)</th>
                    <th class="text-center">%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail_spp as $row)
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                    <td class="text-center">{{ $row->type_produk }}</td>
                    <td class="text-center">{{ $row->spp_vol_btg }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center">{{ $row->sppdis_vol_btg }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center">{{ ($row->spp_vol_btg - $row->sppdis_vol_btg) }}</td>
                    <td class="text-center">0</td>
                    <td class="text-center">{{ $row->spp_vol_btg == 0 ? 0 : round(((($row->spp_vol_btg - $row->sppdis_vol_btg) / $row->spp_vol_btg) * 100), 2) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="separator separator-dashed border-primary my-10"></div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">No. NPP</label>
                <input class="form-control" type="text" readonly  value="{{ $no_npp }}" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">No. SPPrB</label>
                <input class="form-control" type="text" readonly value="{{ $no_spprb }}" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">Pelanggan</label>
                <input class="form-control" type="text" readonly  value="{{ $pelanggan }}" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Nama Proyek</label>
                <input class="form-control" type="text" readonly value="{{ $nama_proyek }}" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-8 required ">Perusahaan / Pemilik Angkutan</label>
                <select class="form-control form-select-solid" @if(in_array($source, ['show', 'konfirmasi'])) disabled @endif data-control="select2" data-placeholder="Pilih Perusahaan / Pemilik Angkutan" name="vendor" id="vendor">
                    <option value="{{ $selected_vendor_id }}" selected>{{ $selected_vendor_name }}</option>
                    @foreach ($vendor_angkutan as $row)
                        <option value="{{ $row->vendor_id }}">{{ $row->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Tujuan</label>
                <input class="form-control" type="text" readonly value="{{ $tujuan->infoPasar->region->kabupaten_name }} - {{ $tujuan->infoPasar->region->kecamatan_name }}" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">Jarak</label>
                <input name="jarak" class="form-control" type="text" readonly  value="{{ $jarak ?? 0 }}" />
            </div>

            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 custom-label">Kondisi Penyerahan</label>
                <input class="form-control" type="text" readonly value="{{ $kp }}" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6 custom-form">
                <label class="form-label col-sm-3 required ">Jalur</label>
                @php
                    $attr = ['class'=>'form-control form-select-solid col-sm-3', "multiple" => true, "disabled" => false, 'data-control'=>'select2', 'id'=>'jalur'];
                    if($source = 'show'){
                        $attr['disabled'] = true;
                    }
                @endphp
                {!! Form::select('jalur[]', $jalur, null, $attr) !!}
            </div>
        </div>
        <div class="separator separator-dashed border-primary my-10"></div>
        <div class="form-group row mt-5">
            <div class="mb-5 align-right">
                <a href="javascript:void(0)" class="btn btn-success" id="show-detail">Show All</a>
            </div>
            <br>
            <table class="table">
                <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border border-gray-400">
                        <th class="text-center" width="30%">Tipe Produk</th>
                        <th class="text-center">Volume</th>
                        <th class="text-center">Jumlah Segmen</th>
                        <th class="text-center">Volume SPPB</th>
                        <th class="text-center">Volume SPM</th>
                        <th class="text-center">Volume Titipan</th>
                        <th class="text-center" width="30%">Keterangan</th>
                        @if($source=='edit')
                            <th class="text-center" >opsi</th>
                        @endif
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid grey;">
                    @php $i = 1 @endphp
                    @foreach($detail_spp as $row)
                    <tr class="fw-semibold fs-6 text-gray-800 border border-gray-400 aa_aa d-none" value="{{ $row->vol ?? 0 }}" id="row_{{ $i }}">
                        <td class="text-center" style="padding-left: 10px;">
                            <input class="form-control" name="tipe_produk_select[]" readonly value="{{ $row->type_produk }}" />
                        </td>
                        <td class="text-center" width="15%">
                            <input type="number"
                                    name="volume_produk_select[]"
                                    segmen="{{ $row->segmen }}"
                                    sppb="{{ $row->vol_sppb }}"
                                    spm="{{ $row->spm }}"
                                    class="form-control volume-show"
                                    step="any"
                                    @if(in_array($source, ['show', 'konfirmasi'])) readonly @endif
                                    onkeyup="validate_vol(this)"
                                    value="{{ $row->vol ?? 0 }}" />
                        </td>
                        <td class="text-center" width="10%">
                            <label class="text-center">{{ $row->segmen }}</label>
                            <input class="segmen-show d-none" name="segmen_select[]" value="{{ $row->segmen }}" />
                        </td>
                        <td  class="text-center"  width="10%">
                            <label class="text-center">{{ $row->vol_sppb }}</label>
                            <input class="d-none" name="volsppb_select[]" value="{{ $row->vol_sppb }}" />
                        </td>
                        <td  class="text-center" width="10%">
                            <label class="text-center">{{ $row->spm }}</label>
                            <input class="volspm-show d-none" name="volspm_select[]" value="{{ $row->spm }}" />
                        </td>
                        <td  class="text-center">0</td>
                        <td  class="text-center">
                            <input type="text" name="keterangan_select[]" @if(in_array($source, ['show', 'konfirmasi'])) readonly @endif class="form-control" value="{{ $row->ket }}"/>
                        </td>
                        @if($source=='edit')
                            <td style="padding-right: 10px;">
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete_muat"><i class="fa fa-times"></i></a>
                            </td>
                        @endif
                    </tr>
                    @php $i++; @endphp
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer" style="text-align: right;">
        @if(in_array($source, ['edit', 'konfirmasi']))
            <button type="submit" class="btn btn-success"> Submit </button>
        @endif
    </div>

</div>

<script type="text/javascript">

$(document).ready(function () {
    for (i = 1; i <= $('.aa_aa').length; i++) {
        var tmp = '#row_' + i;
        var id = $(tmp).attr('value');
        if(id > 0){
            $(tmp).removeClass('d-none');
        }
        // alert('aa');
    }
});

$('#show-detail').on('click', function(){
    for (i = 1; i <= $('.aa_aa').length; i++) {
        var tmp = '#row_' + i;
        var id = $(tmp).attr('value');
        if(id == 0){
            $(tmp).removeClass('d-none');
        }
    }
});

</script>
