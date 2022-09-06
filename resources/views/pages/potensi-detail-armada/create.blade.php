@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Potensi Detail Armada</h1>
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
            @if (isset($data))
                {!! Form::model($data, ['route' => ['master-driver.update', $data->id], 'class' => 'form', 'method' => 'put', 'enctype' => 'multipart/form-data']) !!}
            @else
                {!! Form::open(['url' => route('master-driver.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Tambah Baru Driver</h3>
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
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label mt-2">Rekomendasi Rute</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <a href="#" class="btn btn-icon btn-dark"><i class="fas fa-add"></i></a>
                                    </div>
                                </div>   
                            </div>
                        </div>
                        

                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('master-driver.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
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
@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        
    });

</script>
@endsection