@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SPM</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
    <div class="col-12 mb-md-5 mb-xl-10">
        <form class="form-control" method="POST" enctype="multipart/form-data" action="">
            <div id="box1">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Baru SPM</h3>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-danger alert-dismissible fade" id="alert-box1" role="alert">
                            NPP, Vendor, dan Pekerjaan harus diisi!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 required ">No. SPP</label>
                                <select class="form-control" data-control="select2" name="no_spp" id="no_spp"  data-placeholder="Pilih No. SPP">
                                    <option></option>
                                    @foreach ( $no_spp as $row)
                                        <option value="{{ $row->no_sppb }}">{{ $row->no_sppb }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 custom-label">No. SPM</label>
                                <input class="form-control" type="text" value="AUTO" readonly />
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 required ">Tanggal</label>
                                <input  name="tanggal" class="form-control flatpickr-input active" placeholder="Pilih Tanggal" id="kt_datepicker_3" type="text" readonly="readonly">
                            </div>

                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 required ">Jenis SPM</label>
                                <select class="form-control" data-control="select2" data-placeholder="Pilih Jenis SPM" name="jenis_spm" id="jenis_spm">
                                    <option></option>
                                    <option value="2">Stok Titipan</option>
                                    <option value="0">Stok Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <div class="col-lg-6 custom-form">
                                <label class="form-label col-sm-3 required">PBB Muat</label>
                                <select class="form-control" data-control="select2"  data-placeholder="Pilih PBB Muat" name="pbb_muat" id="pbb_muat">
                                    <option>asdasd</option>
                                    <option>asdasd</option>
                                    <option>asdasd</option>
                                    <option>asdasd</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="text-align: right;">
                        <input type="button" class="btn btn-primary" id="buat_draft" value="Buat Draft">
                    </div>
                </div>
            </div>
        </form>

        <div id="box2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Detail SPP</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered gy-7 gs-7">
                        <thead>
                            <tr>
                                <th rowspan="2">Type</th>
                                <th colspan="2" class="text-center">SPP</th>
                                <th colspan="2" class="text-center">SPP Terdistribusi</th>
                                <th colspan="3" class="text-center">Volume Sisa</th>
                            </tr>
                            <tr class="table table-striped gy-7 gs-7">
                                <th>Vol (Btg)</th>
                                <th>Vol (Ton)</th>
                                <th>Vol (Btg)</th>
                                <th>Vol (Ton)</th>
                                <th>Vol (Btg)</th>
                                <th>Vol (Ton)</th>
                                <th>%</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="card-footer" style="text-align: right;">
                    <input type="button" class="btn btn-primary" id="buat_draft" value="Submit">
                </div>
            </div>
        </div>
    </div>
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


// Start field tanggal
$("#kt_datepicker_3").flatpickr({
    dateFormat: "d-m-Y",
});
// end of field tanggal

// field PBB Muat
// $('#pbb_muat').select2({
//     placeholder: 'Cari...',
//     ajax: {
//         url: "{{ route('spm.getPbbMuat') }}",
//         minimumInputLength: 2,
//         dataType: 'json',
//         cache: true,
//         processResults: function (data) {
//             return {
//                 results: $.map(data, function (item) {
//                     return {
//                         text: item.no_npp + ' | ' + item.nama_proyek,
//                         id: item.no_npp
//                     }
//                 })
//             };
//         },
//     }
// });
// end of PBB Muat


$('#no_spp').on("change", function(e) { 
    var no_spp = ($('#no_spp :selected').val());
    $.ajax({
        type: 'POST',
        url: "{{ route('spm.getPbbMuat') }}",
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        data: {
            no_spp: no_spp,
        },
        success: function(data)
        {
            console.log(data);
        }
    });
});

</script>

@endsection
