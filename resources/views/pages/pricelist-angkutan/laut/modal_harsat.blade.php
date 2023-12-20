<div class="modal fade" id="modal_harsat" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="modal_harsat_header">
                <!--begin::Modal title-->
                <h2>Tambah Harga Satuan</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Form-->
            <!--begin::Modal body-->
            <div class="modal-body px-lg-10">
                <!--begin::Scroll-->
                {{-- <div class="scroll-y me-n7 pe-7" id="modal_harsat_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_harsat_header" data-kt-scroll-wrappers="#modal_harsat_scroll" data-kt-scroll-offset="300px">
                    
                </div> --}}
                <!--end::Scroll-->
                <input type="hidden" id="modal_for" value="add">
                <input type="hidden" id="modal_for_index" value="0">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="form-label">Kondisi Penyerahan</label>
                        {!! Form::select('modal_kondisi', $kondisi, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_kondisi']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Unit</label>
                        {!! Form::select('modal_unit', $unit, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_unit']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Pelabuhan Asal</label>
                        {!! Form::select('modal_pelabuhan_asal', $pelabuhan, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_pelabuhan_asal']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Pelabuhan Tujuan</label>
                        {!! Form::select('modal_pelabuhan_tujuan', $pelabuhan, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_pelabuhan_tujuan']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Site</label>
                        {!! Form::select('modal_site', $site, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_site']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Harga Satuan</label>
                        {!! Form::text('modal_hargasatuan', "", ['class'=>'form-control decimal modal-text', 'id'=>'modal_hargasatuan']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Satuan</label>
                        {!! Form::select('modal_satuan', $satuan, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_satuan']) !!}
                    </div>
                </div>
            </div>
            <!--end::Modal body-->

            <!--begin::Modal footer-->
            <div class="modal-footer flex-right">
                <button type="button" id="modal_harsat_submit" class="btn btn-primary">
                    <span class="indicator-label" id="modal_harsat_btn">Tambah</span>
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
            <!--end::Modal footer-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>