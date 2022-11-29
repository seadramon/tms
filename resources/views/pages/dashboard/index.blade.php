@extends('layout.layout2')

@section('page-title')
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Dashboard</h1>
</div>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="row g-5 g-xl-8">
        <div class="col-4 mb-md-2 mb-xl-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">SP3 / SPK</h3>
                </div>
            
                <div class="card-body">
                    <div class="box-ui-loading-box-data row">
                        <div id="box1-container" class="col-12">
                            <table id="tabel_box1" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                                <tr>
                                    <th style="font-size: 20px; font-weight: bold">Draft</th>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-md-2 mb-xl-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">SPP</h3>
                </div>
            
                <div class="card-body">
                    <div class="box-ui-loading-box-data row">
                        <div id="box2-container" class="col-12">
                            <table id="tabel_box2" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                                <tr>
                                    <th style="font-size: 20px; font-weight: bold">Belum Verifikasi</th>
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
                                <tr>
                                    <th>&nbsp;</th>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-md-2 mb-xl-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">SPM</h3>
                </div>
            
                <div class="card-body">
                    <div class="box-ui-loading-box-data row">
                        <div id="box3-container" class="col-12">
                            <table id="tabel_box3" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                                <tr>
                                    <th style="font-size: 20px; font-weight: bold">Belum Terkonfirmasi</th>
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
                                <tr>
                                    <th>&nbsp;</th>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-md-2 mb-xl-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">e-SPTB</h3>
                </div>
            
                <div class="card-body">
                    <div class="box-ui-loading-box-data row">
                        <div id="box3-container" class="col-12">
                            <table id="tabel_box3" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                                <tr>
                                    <th style="font-size: 20px; font-weight: bold">Proses Pengiriman</th>
                                    <th style="font-size: 36px;">45</th>
                                </tr>

                                <tr>
                                    <th>Sampai Tujuan</th>
                                    <td>7</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-md-2 mb-xl-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Potensi Kebutuhan</h3>
                </div>
            
                <div class="card-body">
                    <div class="box-ui-loading-box-data row">
                        <div id="box3-container" class="col-12">
                            <table id="tabel_box3" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                                <tr>
                                    <th style="font-size: 20px; font-weight: bold">Belum diset</th>
                                    <th style="font-size: 36px;">45</th>
                                </tr>

                                <tr>
                                    <th>Aktif</th>
                                    <td>7</td>
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