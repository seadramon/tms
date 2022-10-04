@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Konfirmasi Vendor</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <div class="col-12 mb-3">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="form-group row mt-2">
                    <div class="col-lg-6 custom-form">
                        <label class="form-label col-sm-3 required ">Tanggal</label>
                        <input readonly name="tanggal" class="form-control flatpickr-input active" placeholder="Pilih Tanggal" id="kt_datepicker_3" type="text" readonly="readonly" value="{{ $data->tgl_spm }}">
                    </div>

                    <div class="col-lg-6 custom-form">
                        <label class="form-label col-sm-3 required ">No SPM</label>
                        <input readonly class="form-control" readonly name="no_spm" value="{{ $data->no_spm }}" />
                    </div>
                </div>
            </div>
            <div class="card-footer" style="text-align: right;">
                <input type="button" class="btn btn-primary" id="buat_draft" value="Buat Draft">
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
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
                        <select class="form-control form-select-solid" data-control="select2" data-placeholder="Pilih Perusahaan / Pemilik Angkutan" name="vendor" id="vendor">
                            <option></option>
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
                        <input name="jarak" class="form-control" type="text" readonly  value="{{ $jarak->jarak_km ?? 0 }}" />
                    </div>
        
                    <div class="col-lg-6 custom-form">
                        <label class="form-label col-sm-3 custom-label">Kondisi Penyerahan</label>
                        <input class="form-control" type="text" readonly value="{{ $kp }}" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6 custom-form">
                        <label class="form-label col-sm-3 required ">Lokasi Stockyard Pemuatan</label>
                        <input name="jarak" class="form-control" type="text" readonly  value="{{ $jarak->jarak_km ?? 0 }}" />
                    </div>
        
                    <div class="col-lg-6 custom-form">
                        <label class="form-label col-sm-3 custom-label">Lokasi Jalur / Gang Stockyard</label>
                        <input class="form-control" type="text" readonly value="AUTO" />
                    </div>
                </div>

            </div>
            <div class="card-footer" style="text-align: right;">
                <input type="button" class="btn btn-primary" id="buat_draft" value="Buat Draft">
            </div>
        </div>
    </div>
</div>
<!--end::Content container-->
@endsection

@section('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

<script type="text/javascript">


// Start field tanggal
$("#kt_datepicker_3").flatpickr({
    dateFormat: "d-m-Y",
});
// end of field tanggal

</script>

@endsection
