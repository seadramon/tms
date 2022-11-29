@extends('layout.layout2')

@section('page-title')
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Dashboard</h1>
</div>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="row g-5 g-xl-8">
        <div class="col-12 mb-md-5 mb-xl-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Dashboard</h3>
                </div>
            
                <div class="card-body">
                    <div class="box-ui-loading-box-data row">
                        <div id="box1-container" class="col-lg-4">
                            <h4>SP3 / SPK</h4>
            
                            <table id="tabel_box1" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                                <tr>
                                    <th>Draft</th>
                                    <th style="font-size: 36px;">3</th>
                                </tr>

                                <tr>
                                    <th>Belum Verifikasi</th>
                                    <td>10</td>
                                </tr>

                                <tr>
                                    <th>Aktif</th>
                                    <td>27</td>
                                </tr>
                                
                                <tr>
                                    <th>Selesai</th>
                                    <td>12</td>
                                </tr>
                            </table>
                        </div>
            
                        <div id="box2-container" class="col-lg-4">
                            <h4>SPP</h4>
            
                            <table id="tabel_box1" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                                <tr>
                                    <th>Belum Verifikasi</th>
                                    <th style="font-size: 36px;">7</th>
                                </tr>

                                <tr>
                                    <th>Aktif</th>
                                    <td>34</td>
                                </tr>
                                
                                <tr>
                                    <th>Selesai</th>
                                    <td>29</td>
                                </tr>
                            </table>
                        </div>

                        <div id="box3-container" class="col-lg-4">
                            <h4>SPM</h4>
            
                            <table id="tabel_box1" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                                <tr>
                                    <th>Belum Terkonfirmasi</th>
                                    <th style="font-size: 36px;">45</th>
                                </tr>

                                <tr>
                                    <th>On Progress</th>
                                    <td>7</td>
                                </tr>
                                
                                <tr>
                                    <th>Terbit eSPtB</th>
                                    <td>153</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Content container-->
@endsection