<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Data Pemenuhan Armada</h3>
    </div>

    <div class="card-body">
        <div class="box-ui-loading-chart">
            <div id="chart-container" style="margin-bottom: 20px;"></div>
        </div>
    </div>

    <div class="card-body">
        <div class="box-ui-loading-box-data row">
            <div id="box1-container" class="col-lg-4">
                @if (Auth::check())
                    <h4>Volume Pemenuhan Armada (btg)</h4>
                @else
                    <h4>Top 5 Vendor Pemenuhan Armada</h4>
                @endif

                <table id="tabel_box1" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                    <thead>
                        <tr class="fw-semibold fs-6 text-muted">
                            <th class="text-center">No.</th>
                            <th>Nama Vendor</th>
                            <th class="text-center">Vol</th>
                        </tr>
                    </thead>
                    <tbody id="box1-content">
                        
                    </tbody>
                </table>
            </div>

            <div id="box2-container" class="text-center col-lg-4">
                <h4>Presentase (%) Pemenuhan</h4>

                <br><br><br><br><br>

                <p id="box2-content1" class="box2-style1"></p>
                <p id="box2-content2" class="box2-style2"></p>

                <br><br><br><br><br><br>

                <p id="box2-content3" class="box2-style3"></p>
                <p id="box2-content4" class="box2-style4"></p>
                <p id="box2-content5" class="box2-style4"></p>
            </div>

            <div id="box3-container" class="col-lg-4">
                <h4>Presentase (%) Ketepatan Waktu Pemenuhan</h4>

                <table id="tabel_box3" class="table table-row-bordered gy-5" style="vertical-align: middle;">
                    <thead>
                        <tr class="fw-semibold fs-6 text-muted">
                            <th>Keterangan</th>
                            <th class="text-center">%</th>
                        </tr>
                    </thead>
                    <tbody id="box3-content">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table id="tabel_pemenuhan_armada" class="table table-row-bordered gy-5" style="vertical-align: middle;">
            <thead>
                <tr class="fw-semibold fs-6 text-muted">
                    <th>NPP</th>
                    <th>PBB MUAT</th>
                    <th>NO SPM</th>
                    <th>TGL SPM</th>
                    <th>NO SPTB</th>
                    <th>TGL SPTB</th>
                    <th>VENDOR</th>
                    <th>NOPOL</th>
                    <th>JENIS ARMADA</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>