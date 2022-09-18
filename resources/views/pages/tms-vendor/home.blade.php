@extends('layout.layout-vendor')
@section('css')
<style type="text/css">
</style>
@endsection

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Home</h1>
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
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Home</h3>
                </div>

                <div class="card-body">
                    Hi, You're Logged In as Vendor
                </div>                
            </div>
        </div>
         <!--end::Col-->
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
</style>
@endsection
@section('js')
<script type="text/javascript">

$( document ).ready(function() {    
    $("#daterange").daterangepicker();

    $(".select2spprb").select2({
        ajax: {
            url: '/select2/spprb',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true,
            minimumInputLength:2
        }
    });

    var target = document.querySelector("#kt_block_ui_target");

    var imgLoading = "{{ asset('assets/image_loader.gif') }}";

    var blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></span> Loading...</div>',
    });

    $("#draft").submit(function(event) {
        event.preventDefault();

        blockUI.block();

        let data = $(this).serialize();
        let url = $(this).attr('action');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"post",
            url: url,
            data: data,
            success: function(res) {
                $("#kt_block_ui_target").html("");

                $("#kt_block_ui_target").html(res);

                blockUI.release();
            }
        })
    });
});
</script>
@endsection