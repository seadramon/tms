@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <!--begin::Title-->
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Dashboard</h1>
    <!--end::Title-->
    <!--begin::Breadcrumb-->
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 pt-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
            <a href="../../demo8/dist/index.html" class="text-muted text-hover-primary">Home</a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">Dashboards</li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-dark">Default</li>
        <!--end::Item-->
    </ul>
    <!--end::Breadcrumb-->
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
                    <h3 class="card-title">LIST SURAT PERJANJIAN PELAKSANAAN PEKERJAAN WIKA BETON</h3>
                    <div class="card-toolbar">
                        
                    </div>
                </div>
                <div class="card-body py-5">
                    <table id="kt_datatable_zero_configuration" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>No SP3</th>
                                <th>No NPP</th>
                                <th>Tanggal</th>
                                <th>Vendor</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TP.02.01/WB-1C.0001/2022P00</td>
                                <td>211C1385IF</td>
                                <td>01-11-2021</td>
                                <td>ADIL JAYA PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0002/2022P00</td>
                                <td>211C1803BF</td>
                                <td>20-12-2021</td>
                                <td>Emitraco Investama Mandiri,PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0002/2022P01</td>
                                <td>211C1803BF</td>
                                <td>02-05-2022</td>
                                <td>Emitraco Investama Mandiri,PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0003/2022P00</td>
                                <td>211C2286BF</td>
                                <td>08-12-2021</td>
                                <td>SIBA SURYA, PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0001/2022P00</td>
                                <td>211C1385IF</td>
                                <td>01-11-2021</td>
                                <td>ADIL JAYA PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0002/2022P00</td>
                                <td>211C1803BF</td>
                                <td>20-12-2021</td>
                                <td>Emitraco Investama Mandiri,PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0002/2022P01</td>
                                <td>211C1803BF</td>
                                <td>02-05-2022</td>
                                <td>Emitraco Investama Mandiri,PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0003/2022P00</td>
                                <td>211C2286BF</td>
                                <td>08-12-2021</td>
                                <td>SIBA SURYA, PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0001/2022P00</td>
                                <td>211C1385IF</td>
                                <td>01-11-2021</td>
                                <td>ADIL JAYA PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0002/2022P00</td>
                                <td>211C1803BF</td>
                                <td>20-12-2021</td>
                                <td>Emitraco Investama Mandiri,PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0002/2022P01</td>
                                <td>211C1803BF</td>
                                <td>02-05-2022</td>
                                <td>Emitraco Investama Mandiri,PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                            <tr>
                                <td>TP.02.01/WB-1C.0003/2022P00</td>
                                <td>211C2286BF</td>
                                <td>08-12-2021</td>
                                <td>SIBA SURYA, PT</td>
                                <td><span class="badge badge-light-success fs-7">Approved</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Menu
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item">View</a></li>
                                            <li><a class="dropdown-item">Edit</a></li>
                                            <li><a class="dropdown-item">Adendum</a></li>
                                            <li><a class="dropdown-item">Approve</a></li>
                                            <li><a class="dropdown-item">Print</a></li>
                                            <li><a class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div> 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#kt_datatable_zero_configuration").DataTable({
            language: {
                lengthMenu: "Show _MENU_",
            },
            dom:
                "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">",
        });
    });
</script>
@endsection