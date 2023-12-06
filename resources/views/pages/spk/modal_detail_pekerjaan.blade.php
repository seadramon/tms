@php
    $produk = $detailPesanan->mapWithKeys(function($item){
        return [$item->produk->kd_produk => $item->produk->kd_produk . " | " . $item->produk->tipe];
    })->all();
@endphp
<div class="modal fade" id="modal_pekerjaan" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="modal_pekerjaan_header">
                <!--begin::Modal title-->
                <h2>Tambah Detail Pekerjaan</h2>
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
                {{-- <div class="scroll-y me-n7 pe-7" id="modal_pekerjaan_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_pekerjaan_header" data-kt-scroll-wrappers="#modal_pekerjaan_scroll" data-kt-scroll-offset="300px">
                    
                </div> --}}
                <!--end::Scroll-->
                <input type="hidden" id="modal_for" value="add">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="form-label">Unit</label>
                        {!! Form::select('modal_unit', $unit, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_unit']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Site</label>
                        {!! Form::select('modal_site', $site, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_site']) !!}
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
                        <label class="form-label">Tipe</label>
                        {!! Form::select('modal_tipe', $produk, null, ['class'=>'form-control form-select-modal-solid', 'data-control'=>'select2', 'id'=>'modal_tipe']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Jarak</label>
                        {!! Form::text('modal_jarak', "", ['class'=>'form-control decimal modal-text', 'id'=>'modal_jarak']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Vol Btg</label>
                        {!! Form::text('modal_vol_btg', "", ['class'=>'form-control decimal modal-text', 'id'=>'modal_vol_btg']) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-label">Vol Ton</label>
                        {!! Form::text('modal_vol_ton', "", ['class'=>'form-control decimal modal-text', 'id'=>'modal_vol_ton']) !!}
                    </div>
                    @if ($sat_harsat == 'tonase')
                        <div class="form-group col-lg-6">
                            <label class="form-label">Satuan</label>
                            {!! Form::select('modal_satuan', $satuan, null, ['class'=>'form-control form-select-modal-solid modal-select2', 'data-control'=>'select2', 'id'=>'modal_satuan']) !!}
                        </div>
                    @else
                        <div class="form-group col-lg-6">
                            <label class="form-label">Ritase</label>
                            {!! Form::text('modal_ritase', "", ['class'=>'form-control decimal modal-text', 'id'=>'modal_ritase']) !!}
                        </div>
                    @endif
                    <div class="form-group col-lg-6">
                        <label class="form-label">Harga Satuan</label>
                        {!! Form::text('modal_harsat', "", ['class'=>'form-control decimal modal-text', 'id'=>'modal_harsat']) !!}
                    </div>
                    {{-- <div class="form-group col-lg-12">
                        <label class="form-label">Jumlah</label>
                        {!! Form::text('modal_jumlah', "", ['class'=>'form-control decimal', 'id'=>'modal_jumlah', 'readonly']) !!}
                    </div> --}}
                </div>
            </div>
            <!--end::Modal body-->

            <!--begin::Modal footer-->
            <div class="modal-footer flex-right">
                <button type="button" id="modal_pekerjaan_submit" class="btn btn-primary">
                    <span class="indicator-label" id="modal_pekerjaan_btn">Tambah</span>
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
        $('.form-select-modal-solid').select2({
            dropdownParent: $("#modal_pekerjaan")
        });
    });
    $('#modal_pekerjaan_submit').on('click', function(e){
        e.preventDefault();
        var data_ = modalPekerjaanData();
        if($("#sat_harsat").val() == 'tonase'){
            var temp_ = "<td><input name=\"satuan[]\" class=\"satuan\" type=\"hidden\" value=\"" + data_.satuan + "\">" + data_.satuan + "</td>";
        }else{
            var temp_ = "<td><input name=\"ritase[]\" class=\"ritase\" type=\"hidden\" value=\"" + data_.ritase + "\">" + data_.ritase + "</td>";
        }
        var table_row = "<td><input name=\"unit[]\" class=\"unit\" type=\"hidden\" value=\"" + data_.unit + "\">" + data_.unit_teks + "</td>" + 
            "<td><input name=\"pelabuhan_asal[]\" class=\"pelabuhan_asal\" type=\"hidden\" value=\"" + data_.pelabuhan_asal + "\">" + data_.pelabuhan_asal + "</td>" + 
            "<td><input name=\"pelabuhan_tujuan[]\" class=\"pelabuhan_tujuan\" type=\"hidden\" value=\"" + data_.pelabuhan_tujuan + "\">" + data_.pelabuhan_tujuan + "</td>" + 
            "<td><input name=\"site[]\" class=\"site\" type=\"hidden\" value=\"" + data_.site + "\">" + data_.site + "</td>" + 
            "<td><input name=\"tipe[]\" class=\"tipe\" type=\"hidden\" value=\"" + data_.tipe + "\">" + data_.tipe_teks.replace("|", "<br>") + "</td>" + 
            "<td><input name=\"jarak[]\" class=\"jarak\" type=\"hidden\" value=\"" + data_.jarak + "\">" + data_.jarak + "</td>" + 
            "<td><input name=\"vol_btg[]\" class=\"vol_btg\" type=\"hidden\" value=\"" + data_.vol_btg + "\">" + data_.vol_btg + "</td>" + 
            "<td><input name=\"vol_ton[]\" class=\"vol_ton\" type=\"hidden\" value=\"" + data_.vol_ton + "\">" + data_.vol_ton + "</td>" + 
            temp_ + 
            "<td><input name=\"harsat[]\" class=\"harsat\" type=\"hidden\" value=\"" + data_.harsat + "\">" + data_.harsat + "</td>" + 
            "<td><input name=\"jumlah[]\" class=\"input-jumlah\" type=\"hidden\" value=\"" + data_.jumlah + "\">" + currencyFormat(data_.jumlah.toString()) + "</td>" + 
            "<td><button class=\"btn btn-danger btn-sm delete_pekerjaan me-1 mb-1\" style=\"padding: 5px 6px;\"><span class=\"bi bi-trash\"></span></button><button class=\"btn btn-warning btn-sm edit_pekerjaan\" style=\"padding: 5px 6px;\"><span class=\"bi bi-pencil-square\"></span></button></td>";
        
        if($("#modal_for").val() == "add"){
            $("#tbody-pekerjaan").append(
                "<tr>" + table_row + "</tr>"
            );
        }else{
            $(".editing").html(table_row);
        }
        calculateTotal();
        $('#modal_pekerjaan').modal('toggle');
    });

    function modalPekerjaanData(){
        var unit = $("#modal_unit").val();
        var unit_teks = $("#modal_unit option:selected").text();
        var pelabuhan_asal = $("#modal_pelabuhan_asal").val();
        var pelabuhan_tujuan = $("#modal_pelabuhan_tujuan").val();
        var site = $("#modal_site").val();
        var tipe = $("#modal_tipe").val();
        var tipe_teks = $("#modal_tipe option:selected").text();
        var jarak = $("#modal_jarak").val();
        var vol_btg = $("#modal_vol_btg").val();
        var vol_ton = $("#modal_vol_ton").val();
        var harsat = $("#modal_harsat").val();
        var satuan = $("#modal_satuan").val();
        var ritase = $("#modal_ritase").val();
        if($("#sat_harsat").val() == 'tonase'){
            if(satuan == 'btg'){
                var jumlah = harsat.replace(/[^0-9\.]/g,'') * vol_btg.replace(/[^0-9\.]/g,'');
            }else{
                var jumlah = harsat.replace(/[^0-9\.]/g,'') * vol_ton.replace(/[^0-9\.]/g,'');
            }
        }else{
            var jumlah = harsat.replace(/[^0-9\.]/g,'') * ritase.replace(/[^0-9\.]/g,'');
        }

        return {
            unit: unit,
            unit_teks: unit_teks,
            pelabuhan_asal: pelabuhan_asal,
            pelabuhan_tujuan: pelabuhan_tujuan,
            site: site,
            tipe: tipe,
            tipe_teks: tipe_teks,
            jarak: jarak,
            vol_btg: vol_btg,
            vol_ton: vol_ton,
            harsat: harsat,
            satuan: satuan,
            ritase: ritase,
            jumlah: jumlah.toFixed(2),
        };
    }
</script>