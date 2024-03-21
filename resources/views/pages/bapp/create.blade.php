@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Berita Acara Pemeriksaan Pekerjaan</h1>
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
            @php
                $disabled = [];
                $editable = [];
            @endphp
            @if (in_array($mode, ['edit', 'show']))
                {!! Form::model($data, ['route' => ['bapp.update', str_replace('/', '|', $data->no_bapp)], 'class' => 'form', 'method' => 'put', 'enctype' => 'multipart/form-data', 'id' => 'form-bapp']) !!}
                @method('PUT')
                @php
                    $disabled = ['disabled'];
                    $editable = ['disabled'];
                    if(in_array($mode, ['edit'])){
                        $editable = [];
                    }
                @endphp
            @else
                {!! Form::open(['url' => route('bapp.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            @endif

            <div id="box1" style="margin-bottom: 20px">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            @if ($mode == 'edit')
                                Edit BAPP
                            @elseif ($mode == 'show')
                                View Data BAPP
                            @else
                                Tambah Baru BAPP
                            @endif
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-danger alert-dismissible fade" id="alert-box1" role="alert">
                            Sp3 harus dipilih!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 custom-label">No. SP3 / SPK</label>
                                {!! Form::select('sp3', $sp3, null, ['class'=>'form-control form-select-solid', 'data-control'=>'select2', 'id'=>'sp3'] + $disabled) !!}
                            </div>
                            <div class="col-lg-2 custom-form">
                                <label class="form-label col-sm-3 custom-label">&nbsp;</label>
                                <button class="form-control btn btn-primary" id="fetch">Ambil Data</button>
                            </div>
                        </div>
                        <div id="box2">

                        </div>
                    </div>
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
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/super-build/ckeditor.js"></script>
<script type="text/javascript">
    var target = document.querySelector("#kt_body");

    var blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
    });

    $(document).ready(function() {
        $("#alert-box1").hide();
        @if(in_array($mode, ['edit', 'show']))
            $('#buat_draft').trigger('click')
        @endif
    });

    $('.search-npp').select2({
        placeholder: 'Cari...',
        ajax: {
            url: "{{ route('sp3.search-npp') }}",
            minimumInputLength: 2,
            dataType: 'json',
            cache: true,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.no_npp + ' | ' + item.nama_proyek,
                            id: item.no_npp
                        }
                    })
                };
            },
        }
    });

    $('#fetch').on('click', function(event){
        event.preventDefault();
        if(!$('#sp3').val()){
            $("#alert-box1").show();
            $("#alert-box1").addClass("show");

            setTimeout(function() {
                $("#alert-box1").hide();
            }, 5000);

            return false;
        }else{
            let data = {
                '_token': '{{ csrf_token() }}',
                'sp3': $('#sp3').val(),
                'mode': "{{$mode}}"
            };

            $.ajax({
                url: "{{ route('bapp.fetch-from-sp3') }}",
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
                    $('#box2').html(result.html);

                    // box2();
                },
                error: function(result) {
                    blockUI.release();
                    alert(result.responseJSON.message)
                }
            });
        }
    });


        // $('.form-select-solid').select2();
</script>
@endsection
