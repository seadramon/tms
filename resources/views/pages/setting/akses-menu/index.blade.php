@extends('layout.layout2')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
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
				<div class="card-body py-3">
					<!--begin::Table container-->
					<div class="table-responsive">


						<!--begin::Table-->
						<table class="table table-striped gy-7 gs-7" id="table_group_menu">
							<!--begin::Table head-->
							<thead>
								<tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
									<th>Group ID</th>
									<th>Nama</th>
									<th>Menu List</th>
									<th>Menu</th>
								</tr>
							</thead>
							<!--end::Table head-->
							<!--begin::Table body-->
							<tbody>
								
							</tbody>
							<!--end::Table body-->
						</table>
						<!--end::Table-->
					</div>
					<!--end::Table container-->
				</div>
				<!--begin::Body-->
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
	<script type="text/javascript">
		"use strict";

		// Class definition
		var KTDatatablesServerSide = function () {
			// Shared variables
			var table;
			var dt;
			var filterPayment;

			// Private functions
			var initDatatable = function () {
				dt = $("#table_group_menu").DataTable({
					language: {
						lengthMenu: "Show _MENU_",
					},
					dom:
						"<'row'" +
						"<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
						"<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
						">" +

						"<'table-responsive'tr>" +

						"<'row'" +
						"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
						"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
						">",
					searchDelay: 500,
					processing: true,
					serverSide: true,
					order: [[0, 'desc']],
					stateSave: true,
					ajax: "{{ route('setting.akses.menu.data') }}",
					columns: [
						{data: 'grpid', name: 'grpid', defaultContent: '-'},
						{data: 'name', name: 'name', defaultContent: '-'},
                        {data: 'menu_list', name: 'menu_list',"searchable": false,"sortable": false },
						{data: 'menu', orderable: false, searchable: false}
					],
				});

				table = dt.$;
			}
			
			// Public methods
			return {
				init: function () {
					initDatatable();
				}
			}
		}();

		// On document ready
		KTUtil.onDOMContentLoaded(function () {
			KTDatatablesServerSide.init();
		});

	</script>
@endsection