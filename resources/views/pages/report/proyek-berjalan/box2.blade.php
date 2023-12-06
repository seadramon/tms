<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Data Proyek Berjalan</h3>
    </div>

    {{-- <div class="card-body">
        <div class="box-ui-loading-chart">
            <div id="chart-container" style="margin-bottom: 20px;"></div>
        </div>
    </div> --}}

    <div class="card-body">
        @include('pages.report.proyek-berjalan.table')
    </div>
</div>