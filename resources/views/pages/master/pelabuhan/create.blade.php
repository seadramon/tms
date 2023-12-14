@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Driver</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-6 mb-md-5 mb-xl-10">
            @if (isset($data))
                {!! Form::model($data, ['route' => ['master-pelabuhan.update', $data->id], 'class' => 'form', 'id' => "form-pelabuhan", 'method' => 'put', 'enctype' => 'multipart/form-data']) !!}
            @else
                {!! Form::open(['url' => route('master-pelabuhan.store'), 'class' => 'form', 'method' => 'post', 'id' => "form-pelabuhan", 'enctype' => 'multipart/form-data']) !!}
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">@if (isset($data))Edit @else Tambah @endif Pelabuhan</h3>
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
                        <div class="fv-row form-group col-12">
                            <label class="form-label required">Nama</label>
                            {!! Form::text('nama', null, ['class'=>'form-control', 'id'=>'nama', 'autocomplete'=>'off']) !!}
                        </div>
                        <div class="col-lg-6 custom-form">
                            <label class="form-label col-sm-3 custom-label">Pengelolaan</label>
                            {!! Form::select('tipe', $tipe, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'tipe']) !!}
                        </div>
                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('master-pelabuhan.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    <input type="submit" class="btn btn-success" id="btn-submit" value="Simpan">
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
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        if("{{isset($data)}}" == ""){
            $(".datepicker").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            }).val('');
    
            $("#sim_expired").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minDate: new Date(),
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            }).val('');
        }else{
            $(".datepicker").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });
    
            $("#sim_expired").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minDate: new Date(),
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });         
        }
    });

    

    $('.form-select-solid').select2();

    const form = document.getElementById('form-pelabuhan');
    var validator = FormValidation.formValidation(
        form,
        {
            fields: {
                'nama': {
                    validators: {
                        notEmpty: {
                            message: 'Nama wajib diisi'
                        }
                    }
                },
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                })
            }
        }
    );

</script>
@endsection