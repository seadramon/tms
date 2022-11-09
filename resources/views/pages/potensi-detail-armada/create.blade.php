@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Potensi Detail Armada</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
{{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
<!--begin::Content container-->
<form action="{{ route('potensi.detail.armada.store') }}" method="post" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Col-->
        <div class="col-12 mb-md-5 mb-xl-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">List Potensi Kebutuhan Armada</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped table-condensed gy-2 gs-2">
                                <thead style="background-color: #1e1e2d; color:white;">
                                    <tr class="text-lg-center border border-gray-400" style="font-size: 10px; font-weight: bold;">
                                        <th style="vertical-align: middle;">NPP</th>
                                        <th>VOL TOTAL (BTG)</th>
                                        <th>VOL TOTAL (TON)</th>
                                        <th style="vertical-align: middle;">TANGGAL AWAL DISTRIBUSI</th>
                                        <th style="vertical-align: middle;">TANGGAL AKHIR DISTRIBUSI</th>
                                        <th style="vertical-align: middle;">JENIS ARMADA</th>
                                        <th style="vertical-align: middle;">TOTAL RIT</th>
                                        <th style="vertical-align: middle;">RIT PER HARI</th>
                                        <th style="vertical-align: middle;">PBB MUAT</th>
                                        <th style="vertical-align: middle;">JARAK</th>
                                        <th style="vertical-align: middle;">opsi</th>
                                    </tr>
                                </thead>
                                <tbody style="border-bottom: 1px solid grey;">
                                    @if($muat == null)
                                        <tr class="text-lg-center border border-gray-400">
                                            <td class="text-lg-center" colspan="10">data tidak ditemukan..</td>
                                        </tr>
                                    @else
                                        @php $i=1; @endphp
                                        @foreach($muat as $row)
                                            <tr class="text-lg-center border border-gray-400">
                                                <td>
                                                    {{ $row->no_npp }}
                                                    <input type="text" value="{{ $row->no_npp }}" name="no_npp[]" hidden="" />
                                                </td>
                                                <td>{{ number_format($row->vol_btg) }}</td>
                                                <td>{{ number_format($row->tonase) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->jadwal3)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->jadwal4)) }}</td>
                                                <td>
                                                    <select class="form-select" data-control="select2" data-placeholder="Select Armada.." name="kd_material[]">
                                                        <option></option>
                                                        @foreach($trmaterial as $item)
                                                            @if(!empty($row->potensiH))
                                                                @if($row->potensiH->kd_material == $item->kd_material)
                                                                    <option value="{{ $item->kd_material }}|{{ $item->uraian }} {{ $item->spesifikasi }}" selected="">{{ $item->uraian }} {{ $item->spesifikasi }}</option>
                                                                @else
                                                                    <option value="{{ $item->kd_material }}|{{ $item->uraian }} {{ $item->spesifikasi }}">{{ $item->uraian }} {{ $item->spesifikasi }}</option>
                                                                @endif
                                                            @else
                                                                <option value="{{ $item->kd_material }}|{{ $item->uraian }} {{ $item->spesifikasi }}">{{ $item->uraian }} {{ $item->spesifikasi }}</option>

                                                            @endif

                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>{{ number_format(round($row->jml_rit)) ?? '0' }}</td>
                                                <td></td>
                                                <td>{{ $row->pat ?? 'Tidak diketahui' }}</td>
                                                <td>{{ $row->jarak_km ?? '0' }}</td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-secondary expandChildTable"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                            <tr class="childTableRow text-lg-center border border-gray-400" style="display: none;">
                                                <td colspan="11">
                                                    <table class="table table-condensed table-striped" id="childTable">
                                                        <thead>
                                                            <tr class="text-lg-center fw-semibold fs-6 text-gray-800 border border-gray-400">
                                                                <th>Tipe Produk</th>
                                                                <th>Kode Produk</th>
                                                                <th>Vol Total BTG</th>
                                                                <th>Vol Total TON</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                                @foreach ($row->spprbri as $childItem)
                                                                <tr class="text-lg-center border border-gray-400" >
                                                                    <td>{{ $childItem->produk->tipe }}</td>
                                                                    <td>{{ $childItem->kd_produk }}</td>
                                                                    <td>{{ $childItem->vol_spprb }}</td>
                                                                    <td>{{ ($childItem->vol_spprb * $childItem->produk->vol_m3) * 2.5  }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            @php $i++; @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- end of card-body -->
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col for Accordion-->
        <div class="col-12 mb-md-5 mb-xl-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Prakiraan Pencitraan Peta Rute Pengiriman</h3>
                </div>

                <div class="card-body">
                    <!--begin::Accordion-->
                    <div class="accordion" id="kt_accordion_1">
                        @php $i=1; @endphp
                        @foreach($muat as $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="kt_accordion_{{ $i }}_header_{{ $i }}">
                                    <button class="accordion-button fs-4 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_{{ $i }}_body_{{ $i }}" aria-expanded="false" aria-controls="kt_accordion_{{ $i }}_body_{{ $i }}">
                                        Rute Pengiriman {{ $i }}
                                    </button>
                                </h2>
                                <div id="kt_accordion_{{ $i }}_body_{{ $i }}" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_{{ $i }}_header_{{ $i }}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-bottom:10px;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label mt-2">PBB Muat : {{ $item->pat ?? 'Tidak diketahui' }}</label>
                                                        <input type="text" name="ppb_muat[]" value="{{ $item->ppb_muat ?? null }}" hidden="" />
                                                    </div>
                                                </div>
                                                <div id="list_checkpoint_{{ $i }}" style="padding-top: 5px;">
                                                    @if($item->potensiH != null && !in_array($item->potensiH->checkpoints, [null, "null"]))
                                                        @foreach( json_decode($item->potensiH->checkpoints ?? "[]",true) as $row)
                                                            <div class="row">
                                                                <div class="col-md-12" style="padding-bottom: 5px;">
                                                                    <div class="row">
                                                                        <div class="col-md-10">
                                                                            <input name="checkpoint_{{ $i }}[]" type="text" class="form-control input-sm" placeholder="" value="{{ $row }}">
                                                                        </div>
                                                                        <div class="col-md-2" style="text-align:center;">
                                                                            <a href="javascript:void(0)" class="btn btn-icon btn-danger delete_rute align-right"><i class="fas fa-times"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input
                                                    name="source_lat[]"
                                                    id="lat_source_{{ $i }}"
                                                    type="text"
                                                    class="form-control input-sm"
                                                    hidden=""
                                                    value="{{ $item->lat_source }}">

                                                <input
                                                    name="source_long[]"
                                                    id="long_source_{{ $i }}"
                                                    type="text"
                                                    class="form-control input-sm"
                                                    hidden=""
                                                    value="{{ $item->long_source }}">

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="form-label mt-2">Lokasi Tujuan</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" readonly value="{{ $item->destination ?? 'Tidak ditemukan' }}">
                                                    </div>
                                                </div>
                                                <div class="row" style="padding-top: 10px;">
                                                    <div class="col-md-4">
                                                        <label class="form-label mt-2">Lat/Long</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input name="dest_lat[]" type="text" id="lat_dest_{{ $i }}" class="form-control input-sm" placeholder="Latitude" readonly value="{{ $item->lat_dest }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input name="dest_long[]" type="text" id="long_dest_{{ $i }}" class="form-control input-sm" placeholder="Longitude" readonly value="{{ $item->long_dest }}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-6">
                                                <a
                                                    style="width: 100%;"
                                                    href="javacript:void(0)"
                                                    class="btn btn-success open-AddBookDialog"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_1"
                                                    data-map="{{ $i }}">
                                                    <i class="fas fa-add"></i> Tambah Rute
                                                </a>

                                            </div>
                                            <div class="col-md-6">
                                                <a style="width: 100%;"
                                                    class="btn btn-block btn-danger create_rute"
                                                    id="create_rute_{{ $i }}"
                                                    onclick="generate_map({{ $i }}); return false;">Generate Rutes
                                                </a>

                                            </div>
                                        </div>
                                        <hr style="border-top: 1px dotted black;">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div id="rute_map_{{ $i }}" style="height:500px;"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div id="sidebar_{{ $i }}" class="scroll h-500px px-5">
                                                    <p>Total Distance: <span id="total_{{ $i }}"></span></p>
                                                    <div id="panel_{{ $i }}"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr style="border-top: 1px dotted black;">

                                        <table class="table table-striped">
                                            <tr>
                                                <td>Jalan</td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="baik" id="flexCheckDefault" name="jalan_{{ $i }}"
                                                            @if(!empty($item->potensiH))
                                                                @if($item->potensiH->jalan == 'baik')
                                                                    checked=""
                                                                @endif
                                                            @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Baik
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="kurang_baik" id="flexCheckDefault" name="jalan_{{ $i }}"
                                                            @if(!empty($item->potensiH))
                                                                @if($item->potensiH->jalan == 'kurang_baik')
                                                                    checked=""
                                                                @endif
                                                            @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Kurang Baik
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="rusak" id="flexCheckDefault" name="jalan_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan == 'rusak')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Rusak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="menanjak" id="flexCheckDefault" name="jalan2_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan2 == 'menanjak')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Menanjak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="berkelok" id="flexCheckDefault" name="jalan2_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan2 == 'berkelok')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Berkelok
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="lain_lain" id="flexCheckDefault" name="jalan2_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan2 == 'lain_lain')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Lain - Lain
                                                        </label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Jembatan</td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="baik" id="flexCheckDefault" name="jembatan_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jembatan == 'baik')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Baik
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="kurang_baik" id="flexCheckDefault" name="jembatan_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jembatan == 'kurang_baik')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Kurang Baik
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="tidak_ada" id="flexCheckDefault" name="jembatan_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jembatan == 'tidak_ada')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Tidak Ada
                                                        </label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Jalan Alternatif</td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="baik" id="flexCheckDefault" name="jalan_alternatif_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan_alt == 'baik')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Baik
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="kurang_baik" id="flexCheckDefault" name="jalan_alternatif_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan_alt == 'kurang_baik')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Kurang Baik
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="rusak" id="flexCheckDefault" name="jalan_alternatif_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan_alt == 'rusak')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Rusak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="menanjak" id="flexCheckDefault" name="jalan_alternatif2_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan_alt2 == 'menanjak')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Menanjak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="berkelok" id="flexCheckDefault" name="jalan_alternatif2_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan_alt2 == 'berkelok')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Berkelok
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="lain_lain" id="flexCheckDefault" name="jalan_alternatif2_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jalan_alt2 == 'lain_lain')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Lain - Lain
                                                        </label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Langsir</td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="tidak_ada" id="flexCheckDefault" name="langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->langsir == 'tidak_ada')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Tidak Ada
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="mobil" id="flexCheckDefault" name="langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->langsir == 'mobil')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Mobil
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="gerobak" id="flexCheckDefault" name="langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->langsir == 'gerobak')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Gerobak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="roll_geser" id="flexCheckDefault" name="langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->langsir == 'roll_geser')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Roll Geser
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="manusia" id="flexCheckDefault" name="langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->langsir == 'manusia')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Manusia
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="lain_lain" id="flexCheckDefault" name="langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->langsir == 'lain_lain')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Lain - Lain
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jarak Langsir</td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid mt-1">
                                                        <input class="form-check-input" type="radio" value="500" id="flexCheckDefault" name="jarak_langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jarak_langsir == '500')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            < 500 M
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid mt-1">
                                                        <input class="form-check-input" type="radio" value="500_1000" id="flexCheckDefault" name="jarak_langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jarak_langsir == '500_1000')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            500 s/d 1.000 M
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid mt-1">
                                                        <input class="form-check-input" type="radio" value="1000" id="flexCheckDefault" name="jarak_langsir_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->jarak_langsir == '1000')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            > 1.000 M
                                                        </label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Metode Penurunan</td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="crene" id="flexCheckDefault" name="metode_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->metode == 'crene')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Crane
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="portal" id="flexCheckDefault" name="metode_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->metode == 'portal')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Portal
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="manual" id="flexCheckDefault" name="metode_{{ $i }}"
                                                        @if(!empty($item->potensiH))
                                                            @if($item->potensiH->metode == 'manual')
                                                                checked=""
                                                            @endif
                                                        @endif
                                                        />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Manual
                                                        </label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        @php $i++; @endphp
                        @endforeach
                    </div>
                    <!--end::Accordion-->
                </div>

                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('potensi.detail.armada.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    <input type="submit" class="btn btn-success" value="Simpan">
                </div>

            </div>
        </div>
    </div>
</form>
<!--end::Content container-->

<!-- add checkpoint modals -->
<div class="modal fade" tabindex="-1" id="kt_modal_1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Cari Lokasi (Google Maps)</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="pac-card" id="pac-card">
                    <div>
                        <div id="label">Location search</div>
                    </div>
                    <div id="pac-container" class="mb-1">
                        <input class="form-control input-sm" id="pac-input" type="text" placeholder="Enter a location">
                        <div id="location-error"></div>
                    </div>
                </div>
                <div id="map_add" style="height:500px;"></div>
                <div id="current" hidden="">Nothing yet...</div>
                <input id="checkpoint_lat" hidden="" type="text" />
                <input id="checkpoint_lng" hidden="" type="text" />
                <input id="mapId" type="text" hidden="" />
                <div id="infowindow-content">
                    <img src="" width="16" height="16" id="place-icon"> <span
                        id="place-name" class="title"></span><br> <span
                        id="place-address"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="add_checkpoint">Add Checkpoint</button>
            </div>
        </div>
    </div>
</div>
<!-- end of checkpoint modals -->

@endsection

@section('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style type="text/css">
    #map {
        height: 100%;
    }

    .pac-container {
        z-index: 10000 !important;
    }
    #checkpoint_lat  {
        z-index: 10000 !important;
    }

    #checkpoint_lng {
        z-index: 10000 !important;
    }

    #childTable tbody tr:last-child, .table tfoot tr:last-child {
        border-bottom: 1px solid grey !important;
    }
</style>
@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function () {
    for (i = 1; i <= $('.create_rute').length; i++) {
        generate_map(i);
    }
});

// show detail list on table
$(function() {
    $('.expandChildTable').on('click', function() {
        $(this).toggleClass('selected').closest('tr').next().toggle();
    })
});

$(document).on("click", ".open-AddBookDialog", function () {
    var mapId = $(this).data('map');
    $(".modal-body #mapId").val( mapId );
    $('#add_checkpoint').attr('onClick', 'addCheckpoint(' +mapId+ ');');
});

// delete checkpoint
$(document).ready(function(){
    $(document).on('click', '.delete_rute', function(e) {
        $(this).parent().parent().parent().remove();
    });
});
</script>

<script type="text/javascript">
function addCheckpoint(increment){
    var lat = $('#checkpoint_lat').val();
    var lng = $('#checkpoint_lng').val();

    $('#list_checkpoint_' + increment).append(
    '<div class="row">'+
        '<div class="col-md-12" style="padding-bottom: 5px;">'+
            '<div class="row">'+
                '<div class="col-md-10">'+
                    '<input name="checkpoint_'+ increment +'[]" type="text" class="form-control input-sm" placeholder="" value="'+ lat +','+ lng +'">'+
                '</div>'+
                '<div class="col-md-2" style="text-align:center;">'+
                    '<a href="javascript:void(0)" class="btn btn-icon btn-danger delete_rute align-right"><i class="fas fa-times"></i></a>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>');
}

function initMap() {
    var centerCoordinates = new google.maps.LatLng(-0.789275, 113.921327); // indonesia
    var map = new google.maps.Map(document.getElementById('map_add'), {
        center : centerCoordinates,
        zoom : 6
    });
    var card = document.getElementById('pac-card');
    var input = document.getElementById('pac-input');
    var infowindowContent = document.getElementById('infowindow-content');

    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

    var autocomplete = new google.maps.places.Autocomplete(input);
    var infowindow = new google.maps.InfoWindow();
    infowindow.setContent(infowindowContent);

    var marker = new google.maps.Marker({
        map : map,
        draggable: true
    });

    autocomplete.addListener('place_changed',function() {
        document.getElementById("location-error").style.display = 'none';
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            document.getElementById("location-error").style.display = 'inline-block';
            document.getElementById("location-error").innerHTML = "Cannot Locate '" + input.value + "' on map";
            return;
        }

        map.fitBounds(place.geometry.viewport);
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        infowindowContent.children['place-icon'].src = place.icon;
        infowindowContent.children['place-name'].textContent = place.name;
        infowindowContent.children['place-address'].textContent = input.value;
        infowindow.open(map, marker);

        $('#checkpoint_lat').val(place.geometry.location.lat().toFixed(6));
        $('#checkpoint_lng').val(place.geometry.location.lng().toFixed(6));
    });

    google.maps.event.addListener(marker, 'dragend', function (evt) {
        $('#checkpoint_lat').val(evt.latLng.lat().toFixed(6));
        $('#checkpoint_lng').val(evt.latLng.lng().toFixed(6));

        infowindow.close();
        marker.setVisible(false);
        marker.setMap(map);
        marker.setVisible(true);
    });

    google.maps.event.addListener(marker, 'dragstart', function (evt) {
        document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
    });

    map.setCenter(marker.position);
    marker.setMap(map);
}

// map for each rute
function generate_map(increment) {
    $('#panel_' + increment).empty();
    $('#total_' + increment).empty();

    var waypts = [];
    $("input[name='checkpoint_"+ increment +"[]']")
        .map(function(){
            var temp = $(this).val().split(',');
            waypts.push({
                location: {
                    lat: parseFloat(temp[0]),
                    lng: parseFloat(temp[1])
                },
                stopover: true
            });
    });

    const map = new google.maps.Map(document.getElementById("rute_map_"+ increment), {
        zoom: 6,
        center: { lat: -6.2297419, lng: 106.7594782 }, // Jakarta. -6.2297419,106.7594782
    });
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        draggable: true,
        map,
        panel: document.getElementById("panel_"+ increment),
    });

    directionsRenderer.addListener("directions_changed", () => {
        const directions = directionsRenderer.getDirections();

        if (directions) {
            computeTotalDistance(directions);
        }
    });
    displayRoute(
        // "Surabaya, Surabaya City, East Java, Indonesia",
        // "Sidoarjo, Sidoarjo Regency, East Java, Indonesia",
        directionsService,
        directionsRenderer
    );


    function displayRoute(service, display) {
        service.route({
            // origin: { location: { lat: -6.218410109901146, lng: 106.79832075524945 } }, //GBK -6.218410109901146, 106.79832075524945
            // destination: { location: { lat: -6.180274999666274, lng: 106.82641519051303 } }, // Monas -6.180274999666274, 106.82641519051303
            origin: {
                location: {
                    lat: parseFloat($('#lat_source_' + increment).val()),
                    lng: parseFloat($('#long_source_' + increment).val())
                }
            },
            destination: {
                location: {
                    lat: parseFloat($('#lat_dest_' + increment).val()),
                    lng: parseFloat($('#long_dest_' + increment).val())
                }
            },
            waypoints: waypts,
            // [ values
            //     // { location: { lat: -7.258621, lng: 112.750281 } },
            //     // { location: "Broken Hill, NSW" },
            // ],
            travelMode: google.maps.TravelMode.DRIVING,
            // avoidTolls: true,
            })
            .then((result) => {
                display.setDirections(result);
            })
            .catch((e) => {
                alert("Could not display directions due to: " + e);
            });
    }

    function computeTotalDistance(result) {
        let total = 0;
        const myroute = result.routes[0];

        if (!myroute) {
            return;
        }

        for (let i = 0; i < myroute.legs.length; i++) {
            total += myroute.legs[i].distance.value;
        }

        total = total / 1000;
        document.getElementById("total_"+ increment).innerHTML = total + " km";
    }

    window.initMap = initMap;
}
</script>



<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0f2vYkUlCd6XCyu17DBElvuxyf_4quCU&libraries=places&callback=initMap&language=id"></script>
@endsection
