@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SP3</h1>
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
            @if (in_array($mode, ['edit', 'show']))
                {!! Form::model($data, ['route' => ['sp3.v2.update', $data->no_sp3], 'class' => 'form', 'method' => 'put', 'enctype' => 'multipart/form-data', 'id' => 'form-edit']) !!}  
                @method('PUT')
                @if ($amandemen)
                    <input type="hidden" name="amandemen" value="{{ $amandemen }}">
                @endif
                @php
                    $disabled = ['disabled'];
                @endphp
            @else
                {!! Form::open(['url' => route('sp3.v2.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                @php
                    $disabled = [];
                @endphp
            @endif

            <div id="box1" style="margin-bottom: 20px">
                @include('pages.sp3.v2.box1')
            </div>

            <div id="box2">
                
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
    
    $('#buat_draft').on('click', function(event){
        event.preventDefault();
        if(!$('#no_npp').val() || !$('#vendor_id').val() || !$('#kd_jpekerjaan').val()){
            $("#alert-box1").show();
            $("#alert-box1").addClass("show");

            setTimeout(function() {
                $("#alert-box1").hide();
            }, 5000);

            return false;
        }else{
            let data = {
                '_token': '{{ csrf_token() }}', 
                'no_npp': $('#no_npp').val(), 
                'sp3': $('#no_sp3').val(), 
                'vendor_id': $('#vendor_id').val(), 
                'sat_harsat': $('#sat_harsat').val(), 
                'kd_jpekerjaan': $('#kd_jpekerjaan').val(),
                'mode': "{{$mode}}"
            };
            
            $.ajax({
                url: "{{ route('sp3.v2.get-data-box2') }}",
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