@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SPTB</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12 mb-md-5 mb-xl-10">
            {!! Form::model($data, ['route' => ['sptb.penilaian-mutu-simpan'], 'class' => 'form', 'id' => "form-driver", 'method' => 'post']) !!}
            @csrf
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">SPTB - Penilaian Mutu Produk</h3>
                </div>
            
                <div class="card-body">
                    @if (count($errors) > 0)
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif

                    <div style="margin-bottom: 50px">
                        <div class="form-group row">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-2 custom-label">No. SPTB</label>
                                {!! Form::text('no_sptb', null, ['class'=>'form-control col-sm-4 custom-width', 'id'=>'no_sptb', 'readonly']) !!}
                            </div>
                        </div>
                    </div>

                    
                    <table class="table table-bordered" id="table-produk" style="margin-top: 30px;">
                        <thead>
                            <tr>
                                <th width='50%'>Tipe Produk</th>
                                <th width='20%'>Volume</th>
                                <th width='30%'>Tanggal Produksi</th>
                            </tr>
                        </thead>
                    </table>

                    <div class="row" id="container-produk">
                        @php
                            $sptb_d2 = $data->sptbd2->groupBy('kd_produk');
                        @endphp
                        @foreach ($data->sptbd as $key => $sptbd)
                            <div class="form-group col-lg-6">
                                <input type="text" class="form-control" value="{{ $sptbd->kd_produk . ' - ' . $sptbd->ket }}" readonly style="background-color: var(--kt-input-disabled-bg);">
                                <input type="hidden" name="kd_produk[]" class="form-control" value="{{ $sptbd->kd_produk }}">
                            </div>
                            <div class="form-group col-lg-3">
                                <input type="text" name="vol[]" class="form-control" value="{{ $sptbd->vol }}" readonly style="background-color: var(--kt-input-disabled-bg);">
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="input-group date">
                                    <input type="text" data-id={{ $key }} name="tgl_produksi[]" readonly class="form-control tgl_produksi" value="{{($sptb_d2[$sptbd->kd_produk][0]->tgl_produksi ?? false) ? date('d-m-Y', strtotime($sptb_d2[$sptbd->kd_produk][0]->tgl_produksi)) : ''}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="display: block">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($sptbd->vol > 0)
                                <label class="form-label col-lg-6">Tanggal Produksi</label>
                                <label class="form-label col-lg-3">No. Produk</label>
                                <label class="form-label col-lg-3">Kondisi Produk</label>
                                @foreach (($sptb_d2[$sptbd->kd_produk] ?? []) as $i => $d2)
                                    <input type="hidden" name="child_trxid_tpd2[{{$sptbd->kd_produk}}][]" value="{{$d2->trxid_tpd2 ?? ''}}">
                                    <div class="form-group col-lg-6">
                                        <div class="input-group date">
                                            <input type="text" name="child_tgl_produksi[]" readonly class="form-control child_tgl_produksi_{{ $key }}" 
                                                value="{{ !blank($data->sptbd2) ? date('d-m-Y', strtotime($data->sptbd2[$i]->tgl_produksi)) : date('d-m-Y') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="display: block">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <input type="text" name="child_kd_produk[]" readonly class="form-control" 
                                            value="{{ !blank($data->sptbd2) ? $data->sptbd2[$i]->stockid : 'test' }}">
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <select name="child_kondisi_produk[{{$sptbd->kd_produk}}][]" class="form-control" >
                                            <option @selected(blank($data->sptbd2) ? false : $data->sptbd2[$i]->kondisi_produk == 'baik') value="baik">Baik</option>
                                            <option @selected(blank($data->sptbd2) ? false : $data->sptbd2[$i]->kondisi_produk == 'rusak') value="rusak">Rusak</option>
                                        </select>
                                    </div>
                                    
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('sptb.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    <input type="submit" class="btn btn-success" value="Simpan">
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<!--end::Content container-->
@endsection

@section('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style>
    .custom-form {
        display: flex;
    }

    .custom-label {
        display: flex; 
        align-items: center;
        margin-bottom: 0px;
    }

    .custom-width{
        width: 85%;
    }

    #jenis_spm {
        text-align: center;
        color: white;
        background-color: red;
    }

    .form-group{
        margin-bottom: 10px;
    }

    #table-produk thead {
        background-color: rgb(0, 119, 255);
        color: white;
        border: 1px solid white;
    }

    #table-produk thead tr th {
        padding-left: 10px;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script type="text/javascript">
    $('.form-select-solid').select2();
    
    var target = document.querySelector("#kt_body");
            
    var blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
    });
    
    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: 1901,
        autoApply: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    $('.tgl_produksi').on('change', function(){
        $('.child_tgl_produksi_' + $(this).data('id')).val($(this).val()).trigger('change');
    });
</script>
@endsection