<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Data Evaluasi Vendor</h3>
    </div>

    <div class="card-body">
        <div class="box-ui-loading-chart">
            <div id="chart-container" style="margin-bottom: 20px;"></div>
        </div>
    </div>

    <div class="card-body">
        @include('pages.report.evaluasi-vendor.table-sp3')
        @include('pages.report.evaluasi-vendor.table-vendor-semester')
        @include('pages.report.evaluasi-vendor.table-vendor-monthly')
    </div>
</div>