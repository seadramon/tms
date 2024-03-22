
<div class="modal fade" id="modal_saatini" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="modal_saatini_header">
                <!--begin::Modal title-->
                <h2>Input Data Saat Ini</h2>
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
                {{-- <div class="scroll-y me-n7 pe-7" id="modal_saatini_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_saatini_header" data-kt-scroll-wrappers="#modal_saatini_scroll" data-kt-scroll-offset="300px">

                </div> --}}
                <!--end::Scroll-->
                <input type="hidden" id="modal_for" value="add">
                <input type="hidden" id="modal_tr" value="">
                <div class="row">
                    <div class="form-floating col-12 mb-5">
                        {!! Form::text('modal_vol_btg', "", ['class'=>'form-control decimal modal-text', 'id'=>'modal_vol_btg', 'placeholder' => "Volume Btg"]) !!}
                        <label for="modal_vol_btg">Volume Btg</label>
                    </div>
                    <div class="form-floating col-12 mb-5">
                        {!! Form::text('modal_vol_ton', "", ['class'=>'form-control decimal modal-text', 'id'=>'modal_vol_ton', 'placeholder' => "Volume Ton"]) !!}
                        <label for="modal_vol_ton">Volume Ton</label>
                    </div>
                </div>
            </div>
            <!--end::Modal body-->

            <!--begin::Modal footer-->
            <div class="modal-footer flex-right">
                <button type="button" id="modal_saatini_submit" class="btn btn-primary">
                    <span class="indicator-label" id="modal_saatini_btn">Simpan</span>
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

<script type="text/javascript">
    $(document).ready(function() {

    });

    $(document).on('keyup', '#modal_vol_btg, #modal_vol_ton', function(){
        var vol = parseFloat($(this).val().replace(",", ''));
        var maks = parseFloat($(this).attr('data-max'));
        if(vol > maks){
            alert($(this).attr('placeholder') + ' Tidak boleh melebihi ' + maks);
            $(this).val(maks)
            $(this).trigger('keyup');
        }
    });

    $('#modal_saatini_submit').on('click', function(e){
        e.preventDefault();
        var data_ = modalPekerjaanData();
        // kd_jpekerjaan
        var temp_saatini = "";
        var kode = $("#modal_tr").val();
        var satuan = $("#tr-" + kode).find("input.sp3_satuan").val();
        var harsat = $("#tr-" + kode).find("input.sp3_harsat").val();
        var ttl_sp3 = $("#tr-" + kode).find("input.sp3_ttl").val();

        if(satuan == 'ton'){
            var harga = data_.vol_ton * harsat;
        }else{
            var harga = data_.vol_btg * harsat;
        }

        $("#tr-" + kode).find('td.saatini_btg1').html(currencyFormat(data_.vol_btg) + "<input type=\"hidden\" name=\"saatini_btg[" + kode + "]\" class=\"saatini_btg\" value=\"" +data_.vol_btg + "\">");
        $("#tr-" + kode).find('td.saatini_btg2').html(currencyFormat((data_.vol_btg / data_.vol_btg_sp3 * 100).toFixed(0)) + "%");
        $("#tr-" + kode).find('td.saatini_ton1').html(currencyFormat(data_.vol_ton) + "<input type=\"hidden\" name=\"saatini_ton[" + kode + "]\" class=\"saatini_ton\" value=\"" +data_.vol_ton + "\">");
        $("#tr-" + kode).find('td.saatini_ton2').html(currencyFormat((data_.vol_ton / data_.vol_ton_sp3 * 100).toFixed(0)) + "%");
        $("#tr-" + kode).find('td.saatini_harga1').html(currencyFormat(harga.toString()) + "<input type=\"hidden\" name=\"saatini_harga[" + kode + "]\" class=\"saatini_harga\" value=\"" + harga + "\">");
        $("#tr-" + kode).find('td.saatini_harga2').html(currencyFormat((harga / ttl_sp3 * 100).toFixed(0)) + "%");

        calculateSisa();
        calculateTotal();
        $('#modal_saatini').modal('toggle');
    });

    function modalPekerjaanData(){
        var vol_btg = $("#modal_vol_btg").val().replace(/[^0-9\.]/g,'');
        var vol_btg_sp3 = $("#modal_vol_btg").attr('data-sp3');
        var vol_ton = $("#modal_vol_ton").val().replace(/[^0-9\.]/g,'');
        var vol_ton_sp3 = $("#modal_vol_ton").attr('data-sp3');

        return {
            vol_btg: vol_btg,
            vol_btg_sp3: vol_btg_sp3,
            vol_ton: vol_ton,
            vol_ton_sp3: vol_ton_sp3
        };
    }
</script>
