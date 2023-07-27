@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">SP3</h1>
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
            {!! Form::open(['url' => route('sp3.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

            <div id="box1" style="margin-bottom: 20px">
                @include('pages.sp3.box1')
            </div>

            <div id="box2">
                
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
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script type="text/javascript">
    var target = document.querySelector("#kt_body");
            
    var blockUI = new KTBlockUI(target, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading data...</div>',
    });
    
    $(document).ready(function() {
        $("#alert-box1").hide();
    });

    $('.search-npp').select2({
        placeholder: 'Cari...',
        ajax: {
            url: "{{ route('sp3.search-npp') }}",
            minimumInputLength: 2,
            dataType: 'json',
            cache: true,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.no_npp + ' | ' + item.nama_proyek,
                            id: item.no_npp
                        }
                    })
                };
            },
        }
    });
    
    $('#buat_draft').on('click', function(){
        if(!$('#no_npp').val() || !$('#vendor_id').val() || !$('#kd_jpekerjaan').val()){
            $("#alert-box1").show();
            $("#alert-box1").addClass("show");

            setTimeout(function() {
                $("#alert-box1").hide();
            }, 5000);

            return false;
        }else{
            let data = {
                '_token': '{{ csrf_token() }}', 
                'no_npp': $('#no_npp').val(), 
                'vendor_id': $('#vendor_id').val(), 
                'sat_harsat': $('#sat_harsat').val(), 
                'kd_jpekerjaan': $('#kd_jpekerjaan').val()
            };
            
            $.ajax({
                url: "{{ route('sp3.get-data-box2') }}",
                type: "POST",
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    blockUI.block();
                },
                complete: function() {
                    blockUI.release();
                },
                success: function(result) {
                    $('#box2').html(result.html);

                    box2();
                },
                error: function(result) {
                }
            });
        }
    });
    
    function box2() {
        $(".datepicker").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            autoApply: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        $('.search-pic').select2({
            placeholder: 'Cari...',
            ajax: {
                url: "{{ route('sp3.search-pic') }}",
                minimumInputLength: 2,
                dataType: 'json',
                cache: true,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.employee_id + ' - ' + item.first_name + ' ' + (item.last_name ?? ''),
                                id: item.employee_id
                            }
                        })
                    };
                },
            }
        });

        $(document).on('change', '.vol_btg', function(){
            let rowId = $(this).attr('row-id');
            
            let volBtg = parseFloat($('#vol_btg_' + rowId).val());
            let volBtgMax = parseFloat($('#vol_btg_max_' + rowId).val());

            $('.tipe').each(function(){
                if($(this).attr('row-id') != rowId && $(this).val() == $('#tipe_' + rowId).val()){
                    volBtg = parseFloat(volBtg) + parseFloat($('#vol_btg_' + $(this).attr('row-id')).val());
                }
            });

            $('.pesanan_kd_produk').each(function(){
                if($(this).val() == $('#tipe_' + rowId).val()){
                    $('#vol_btg_max_' + rowId).val($('#pesanan_vol_btg_max_' + $(this).attr('row-id')).val());

                    volBtgMax = $('#vol_btg_max_' + rowId).val();
                }
            });
            
            if(volBtg && volBtg > volBtgMax){
                alert('Nilai Vol (Btg) Pekerjaan tidak Boleh lebih dari Vol (Btg) Pesanan!');

                $('#vol_btg_' + rowId).val(0);

                return false;
            }
        });

        $(document).on('change', '.vol_btg, .vol_ton, .harsat, .satuan', function(){
            let rowId = $(this).attr('row-id');

            calculateJumlah(rowId);
        });

        $('#ppn, #pph').on('change', function(){
            calculateTotal();
        });

        $(document).on('change', '.tipe', function(){
            let rowId = $(this).attr('row-id');

            $('#vol_btg_' + rowId).val(0);
        });

        function calculateJumlah(rowId){
            if($('#satuan_' + rowId).val()){
                if($('#satuan_' + rowId).val() == 'btg'){
                    $('#jumlah_' + rowId).val(reFormat($('#harsat_' + rowId).val().replaceAll(",", "") * $('#vol_btg_' + rowId).val().replaceAll(",", "")));
                }else{
                    $('#jumlah_' + rowId).val(reFormat($('#harsat_' + rowId).val().replaceAll(",", "") * $('#vol_ton_' + rowId).val().replaceAll(",", "")));
                }
            }

            calculateSubTotal(rowId);
        }

        function calculateSubTotal(rowId = null){
            let subTotal = 0;

            for(let i=0; i < $('.detail_pekerjaan').length; i++){
                if($('#jumlah_' + i).val()){
                    subTotal = parseFloat(subTotal) + parseFloat($('#jumlah_' + i).val().replaceAll(",", ""));
                }
            }

            $('#subtotal').val(reFormat(subTotal, false));

            calculateTotal();
        }

        function calculateTotal(){
            let pph_ = $('#pph').val().split("|")
            let ppn = parseFloat($('#subtotal').val().replaceAll(",", "")) * parseFloat($('#ppn').val() / 100);
            let pph = parseFloat($('#subtotal').val().replaceAll(",", "")) * parseFloat(pph_[1] / 100);
            let total = parseFloat($('#subtotal').val().replaceAll(",", "")) + parseFloat(ppn) + parseFloat(pph);

            $('#total').val(reFormat(total, false));
        }

        $(document).on('click', '.delete_pekerjaan', function(){
            let rowId = $(this).attr('row-id');
            let deletedRow = $('#detail_pekerjaan_' + rowId);

            deleteRow(deletedRow);
        });

        function deleteRow(deletedRow){
            $(deletedRow.remove());

            calculateSubTotal();
        }

        $('#material_tambahan').repeater({
			initEmpty: true,

			show: function () {
				$(this).slideDown();
			},

			hide: function (deleteElement) {
				$(this).slideUp(deleteElement);
			}
		});

        $('#jarak_pesanan').on('change', function(){
            $('.jarak_pekerjaan').each(function(){
                $(this).val($('#jarak_pesanan').val());
            });
        });

        $('#harga_satuan_ritase').on('change', function(){
            $('.harsat').each(function(){
                $(this).val($('#harga_satuan_ritase').val());
            });

            $('.harsat').trigger('change');
        });
        
        $('#est-rit').on('change', function(){
            est = parseFloat($('#est-rit').val().replaceAll(",", "")) / $('.harsat').length;
            $('.harsat').each(function(){
                $(this).val(reFormat(est));
            });
            $('.jumlah').each(function(){
                $(this).val(reFormat(est));
            });
            $('#subtotal').val($('#est-rit').val());
            calculateTotal();
        });

        $('#add-detail').on('click', function(){
            var newIndex = parseInt($('#tabel_detail_pekerjaan tr:last').attr('row-id')) + 1;

            var row = document.getElementById('detail_pekerjaan_clone'); // find row to copy
            var table = document.getElementById('tabel_detail_pekerjaan'); // find table to append to
            var clone = row.cloneNode(true); // copy children too

            var sat_harsat = $("#sat_harsat").val().toLowerCase();

            //Set Row Id
            clone.id = 'detail_pekerjaan_' + newIndex;
            
            //Set Id
            clone.getElementsByTagName('select')[0].id = 'unit_' + newIndex;
            clone.getElementsByTagName('select')[1].id = 'tipe_' + newIndex;
            clone.getElementsByTagName('input')[1].id = 'jarak_pekerjaan_' + newIndex;
            clone.getElementsByTagName('input')[2].id = 'vol_btg_' + newIndex;
            clone.getElementsByTagName('input')[3].id = 'vol_btg_max_' + newIndex;
            clone.getElementsByTagName('input')[4].id = 'vol_ton_' + newIndex;
            if(sat_harsat == 'tonase'){
                clone.getElementsByTagName('select')[2].id = 'satuan_' + newIndex;
            }
            clone.getElementsByTagName('input')[5].id = 'harsat_' + newIndex;
            clone.getElementsByTagName('input')[6].id = 'jumlah_' + newIndex;
            clone.getElementsByTagName('button')[0].id = 'delete_pekerjaan_' + newIndex;
            
            $(table).find('tbody').append(clone) // add new row to end of table
            
            //Set Row Id
            $('#detail_pekerjaan_' + newIndex).attr('row-id', newIndex);
            
            //Set Col Id
            $('#unit_' + newIndex).attr('row-id', newIndex);
            $('#tipe_' + newIndex).attr('row-id', newIndex);
            $('#tipe_' + newIndex).addClass('tipe');
            $('#jarak_pekerjaan_' + newIndex).attr('row-id', newIndex);
            $('#vol_btg_' + newIndex).attr('row-id', newIndex);
            $('#vol_btg_max_' + newIndex).attr('row-id', newIndex);
            $('#vol_ton_' + newIndex).attr('row-id', newIndex);
            if(sat_harsat == 'tonase'){
                $('#satuan_' + newIndex).attr('row-id', newIndex);
            }
            $('#harsat_' + newIndex).attr('row-id', newIndex);
            $('#jumlah_' + newIndex).attr('row-id', newIndex);
            $('#delete_pekerjaan_' + newIndex).attr('row-id', newIndex);

            //Remove Disable
            $('#unit_' + newIndex).removeAttr('disabled');
            $('#tipe_' + newIndex).removeAttr('disabled');
            $('#jarak_pekerjaan_' + newIndex).removeAttr('disabled');
            $('#vol_btg_' + newIndex).removeAttr('disabled');
            $('#vol_btg_max_' + newIndex).removeAttr('disabled');
            $('#vol_ton_' + newIndex).removeAttr('disabled');
            if(sat_harsat == 'tonase'){
                $('#satuan_' + newIndex).removeAttr('disabled');
            }
            $('#harsat_' + newIndex).removeAttr('disabled');
            $('#jumlah_' + newIndex).removeAttr('disabled');
            $('#delete_pekerjaan_' + newIndex).removeAttr('disabled');
            
            //Set Select2
            $('#unit_' + newIndex).select2();
            $('#tipe_' + newIndex).select2();

            if(sat_harsat == 'tonase'){
                $('#satuan_' + newIndex).select2();
            }
            
            //Show New Row
            $('#detail_pekerjaan_' + newIndex).show();
        });

        $('.form-select-solid').select2();
    }
</script>
@endsection