@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Verifikasi Armada</h1>
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
            {!! Form::model($data, ['route' => ['verifikasi-armada.verify', $data->id], 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Verifikasi Armada</h3>
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

                    <div class="row">
                        <div class="form-group mb-3 col-lg-12">
                            <label class="form-label">Jenis Armada</label>
                            <input type="text" name="jenis" class="form-control" disabled value="{{$data->jenis->kd_material}} | {{$data->jenis->uraian}} - {{$data->jenis->spesifikasi}}">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tahun Pembuatan</label>
                            <input type="text" name="_" class="form-control" disabled value="{{$data->tahun}}">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Nopol</label>
                            <input type="text" name="_" class="form-control" disabled value="{{$data->nopol}}">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Status</label>
                            <input type="text" name="_" class="form-control" disabled value="{{$data->status}}">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Driver</label>
                            <input type="text" name="_" class="form-control" disabled value="{{$data->driver->nama ?? '-'}}">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Berlaku STNK</label>
                            <input type="text" name="_" class="form-control tanggal" disabled id="tgl_stnk" value="{{ $data->tgl_stnk ? date('d-m-Y', strtotime($data->tgl_stnk)) : '-'}}" data-inc-value="5" data-inc-type="years">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Perpanjang STNK</label>
                            {!! Form::text('tgl_stnk_expired', null, ['class'=>'form-control', 'id'=>'tgl_stnk_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Foto STNK</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-100px me-3 images">
                                    <img src="{{asset('content/loader.gif')}}" data-src="{{ full_url_from_path($data->foto_stnk ?? 'foto_stnk.jpg') }}" class="lazy" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label required">Verifikasi</label>
                            {!! Form::select('v_stnk', $verify, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'v_stnk']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Berlaku KIR Head</label>
                            <input type="text" name="_" class="form-control tanggal" disabled id="tgl_kir_head" value="{{ $data->tgl_kir_head ? date('d-m-Y', strtotime($data->tgl_kir_head)) : '-'}}" data-inc-value="6" data-inc-type="months">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Perpanjang KIR Head</label>
                            {!! Form::text('tgl_kir_head_expired', null, ['class'=>'form-control', 'id'=>'tgl_kir_head_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Foto KIR Head</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-100px me-3 images">
                                    <img src="{{asset('content/loader.gif')}}" data-src="{{ full_url_from_path($data->foto_kir_head ?? 'foto_kir_head.jpg') }}" class="lazy" alt="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label required">Verifikasi</label>
                            {!! Form::select('v_kir_head', $verify, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'v_kir_head']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Berlaku KIR Trailer</label>
                            <input type="text" name="_" class="form-control tanggal" disabled id="tgl_kir_trailer" value="{{ $data->tgl_kir_trailer ? date('d-m-Y', strtotime($data->tgl_kir_trailer)) : '-'}}" data-inc-value="6" data-inc-type="months">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Perpanjang KIR Trailer</label>
                            {!! Form::text('tgl_kir_trailer_expired', null, ['class'=>'form-control', 'id'=>'tgl_kir_trailer_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Foto KIR Trailer</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-100px me-3 images">
                                    <img src="{{asset('content/loader.gif')}}" data-src="{{ full_url_from_path($data->foto_kir_trailer ?? 'foto_kir_trailer.jpg') }}" class="lazy" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label required">Verifikasi</label>
                            {!! Form::select('v_kir_trailer', $verify, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'v_kir_trailer']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Berlaku Pajak</label>
                            <input type="text" name="_" class="form-control tanggal" disabled id="tgl_pajak" value="{{ $data->tgl_pajak ? date('d-m-Y', strtotime($data->tgl_pajak)) : '-'}}" data-inc-value="1" data-inc-type="years">
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Tanggal Perpanjang Pajak</label>
                            {!! Form::text('tgl_pajak_expired', null, ['class'=>'form-control', 'id'=>'tgl_pajak_expired', 'disabled']) !!}
                        </div>

                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label">Foto Pajak</label>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-100px me-3 images">
                                    <img src="{{asset('content/loader.gif')}}" data-src="{{ full_url_from_path($data->foto_pajak ?? 'foto_pajak.jpg') }}" class="lazy" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3 col-lg-3">
                            <label class="form-label required">Verifikasi</label>
                            {!! Form::select('v_pajak', $verify, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'v_pajak']) !!}
                        </div>

                        <div class="form-group mb-3 mt-5 col-lg-4">
                            <label class="form-label required">Visual</label>
                            {!! Form::select('visual', $verify, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'visual']) !!}
                        </div>
                        <div class="form-group mb-3 mt-5 col-lg-4">
                            <label class="form-label required">Kelengkapan Armada</label>
                            {!! Form::select('kelengkapan', $verify, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kelengkapan']) !!}
                        </div>
                        <div class="form-group mb-3 mt-5 col-lg-4">
                            <label class="form-label required">Kondisi Ban</label>
                            {!! Form::select('kondisi_ban', $verify, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'kondisi_ban']) !!}
                        </div>
                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('verifikasi-armada.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    @if ($data->driver)
                        <input type="submit" class="btn btn-success" value="Simpan">
                    @endif
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
<link href="{{asset('css/viewer.css')}}" rel="stylesheet">
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{asset('js/viewerjs/viewer.js')}}"></script>
<script src="{{asset('js/viewerjs/jquery-viewer.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.form-select-solid').select2();
        $('.images').viewer({
            title: [4, (image, imageData) => `${image.alt} (${imageData.naturalWidth} × ${imageData.naturalHeight})`]
        });
        $(".tanggal").each(function(){
            getNextExpiredDate($(this).attr('id'), $(this).attr('data-inc-value'), $(this).attr('data-inc-type'))
        })
    });

    function getNextExpiredDate(id, inc, type){
        var date = $("#" + id).val();
        var idExpired = "#" + id + '_expired';
        if(date == ''){
            $(idExpired).val('');
        }else{
            $(idExpired).val(moment(date, 'DD-MM-YYYY').add(inc, type).format('DD-MM-YYYY'));
        }
    }
</script>
@endsection