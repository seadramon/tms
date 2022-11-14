@extends('layout.layout2')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/custom/jstree/jstree.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		
	<!--begin::Post-->
	<div class="post d-flex flex-column-fluid" id="kt_post">
		<!--begin::Container-->
		<div id="kt_content_container" class="container-xxl">
			
			<!--begin::Tables Widget 12-->
			<div class="card mb-5 mb-xl-8">
				<!--begin::Header-->
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">Akses Menu</span>
						<!-- <span class="text-muted mt-1 fw-bold fs-7">Over 500 new members</span> -->
					</h3>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
                <form class="form" method="post">
                    <div class="card-body py-3">
                        <input type="text" name="_token" hidden="" value="{{csrf_token()}}">
                        <input type="text" name="id" hidden="" value="{{ $id }}" id="role_id">
                        <div id="kt_docs_jstree_basic"></div>
                    </div>
                    <div class="card-footer" >
                        <a href="javascript:void(0)" class="btn btn-primary mr-2" id="updateBtn">Update</a>
                        <a href="{{ URL::previous() }}" class="btn btn-secondary">Cancel</a>
                        <a href="{{ route('setting.akses.menu.delete.setting', ['id' =>  $id]) }}" 
                            onClick="return confirm('Are you sure ?')"  class="btn btn-secondary">Remove All Menus</a>
                    </div>
                </form>
			</div>
			<!--end::Tables Widget 12-->

		</div>
		<!--end::Container-->
	</div>
	<!--end::Post-->
</div>
<!--end::Content-->
@endsection

@section('js')
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/custom/jstree/jstree.bundle.js')}}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript">
        $.ajax({
            type: "POST",
            url: "{{ route('setting.akses.menu.tree.data') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                    id : $('#role_id').val(),
                },
            success: function(result) {
                $('#kt_docs_jstree_basic').jstree({ 
                    "core" : result, 
                    "plugins": ["types","checkbox"]
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

        $('#updateBtn').on("click", function(e) { 

            var selectedElmsIds = $('#kt_docs_jstree_basic').jstree("get_selected");
            var selectedElmsIds = [];
            var selectedElms = $('#kt_docs_jstree_basic').jstree("get_selected", true);
            $.each(selectedElms, function() {
                selectedElmsIds.push(this.li_attr.val_id);
            });

            swal({
                title: "Apakah anda yakin ?",
                text: "Semua menu terpilih akan diperbarui/ditambahkan",
                icon: "success",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('setting.akses.menu.update.setting') }}",
                        headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}"
                        },
                        data: {
                                data: selectedElmsIds,
                                role_id : $('#role_id').val(),
                            },
                        success: function(result) {
                            // swal("Menu Successfully Update");
                            flasher.success("Data telah berhasil ditambahkan!");
                            setTimeout(function() { 
                                window.location=window.location;
                            },1000);
                            console.log(result);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                } else {
                    swal("Update Canceled", {
                        icon: "success",
                    }); 
                }
            });
        });

		
	</script>
@endsection