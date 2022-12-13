@extends('layout.layout2')
@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Kalender Pengiriman</h1>
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
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Kalender Pengiriman</h3>
                    {{-- <div class="card-toolbar">
                        <a href="{{route('master-armada.create')}}" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Tambah Data</a>
                    </div> --}}
                </div>

                <div class="card-body py-5">
					<div class="form-group row">
                        <div class="col-10 custom-form mb-2">
						</div>
                        <div class="col-2 custom-form mb-2">
							<a href="{{route('kalender-pengiriman.detail-weekly')}}" class="btn btn-light-primary" style="width: 100%" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Weekly Data</a>
						</div>
                    </div>
					<div id="kalender-pengiriman"></div>
					<p class="text-inverse-primary p-3 fw-semibold fw-6 mt-5 col-3" style="background-color: #1a428b; font-weight: bold">SP3</p>
					<p class="text-inverse-primary p-3 fw-semibold fw-6 mt-2 col-3" style="background-color: #af96e2; font-weight: bold">SPP</p>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<!--end::Content container-->
@endsection
@section('css')
{{-- <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/> --}}
<link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style>
    .custom-form {
        display: flex;
    }
    .custom-label {
        display: flex; 
        align-items: center;
        margin-bottom: 0px;
    }
	.fc-h-event {
		border: none!important;
		background-color: transparent!important;
	}
</style>
@endsection
@section('js')
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script type="text/javascript">
 	$(document).ready(function() {
		
    });
	const element = document.getElementById("kalender-pengiriman");

	var todayDate = moment().startOf("day");
	var YM = todayDate.format("YYYY-MM");
	var YESTERDAY = todayDate.clone().subtract(1, "day").format("YYYY-MM-DD");
	var TODAY = todayDate.format("YYYY-MM-DD");
	var TOMORROW = todayDate.clone().add(1, "day").format("YYYY-MM-DD");

	var calendarEl = document.getElementById("kalender-pengiriman");
	var calendar = new FullCalendar.Calendar(calendarEl, {
		headerToolbar: {
			left: "prev,next",
			center: "title",
			right: ""
		},

		height: 800,
		contentHeight: 780,
		aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio

		nowIndicator: true,
		now: TODAY + "T09:25:00", // just for demo

		views: {
			dayGridMonth: { buttonText: "Bulan" },
			timeGridWeek: { buttonText: "Minggu" },
		},

		initialView: "dayGridMonth",
		initialDate: TODAY,

		// editable: true,
		dayMaxEvents: true, // allow "more" link when too many events
		navLinks: true,
		// events: [
		// 	{
		// 		title: '20',
		// 		// display: 'list-item',
		// 		start: '2022-10-04',
		// 		backgroundColor: '#d5f2cc',
		// 		textColor: '#186000',
		// 		color: 'white'
		// 	},
		// ],
		eventSources: [

			{
				url: "{{route('kalender-pengiriman.spm')}}", // use the `url` property
				// backgroundColor: 'none',
				// textColor: '#186000',
				// color: 'white'
			},
			{
				url: "{{route('kalender-pengiriman.spp')}}", // use the `url` property
				display: "background"
				// backgroundColor: '#c9b9ec',
				// textColor: '#186000',
				// color: 'white'
			}

			// any other sources...

		],
		// eventContent: function (info) {
		// 	var element = $(info.el);

		// 	if (info.event.extendedProps && info.event.extendedProps.description) {
		// 		if (element.hasClass("fc-day-grid-event")) {
		// 			element.data("content", info.event.extendedProps.description);
		// 			element.data("placement", "top");
		// 			KTApp.initPopover(element);
		// 		} else if (element.hasClass("fc-time-grid-event")) {
		// 			element.find(".fc-title").append("<div class=\"fc-description\">" + info.event.extendedProps.description + "</div>");
		// 		} else if (element.find(".fc-list-item-title").lenght !== 0) {
		// 			element.find(".fc-list-item-title").append("<div class=\"fc-description\">" + info.event.extendedProps.description + "</div>");
		// 		}
		// 	}
		// }
		dayCellContent: function(arg) {
			class_ = 'light';
			if(arg.isToday){
				class_ = 'warning';
			}
			return {html: '<span class="badge badge-sm badge-square badge-' + class_ + '" style=\"margin-right: 2px; margin-top: 2px;\">' + arg.dayNumberText + '</span>'};
		},
		eventContent: function(arg) {
			content = '';
			if(arg.event.extendedProps.withText){
				content = '<span class=\"badge badge-circle badge-light-success\" style=\"margin-left: 5px;\">' + arg.event.title + '</span>';
			}
			return { html: content };
		},
		viewRender: function(view, element) {
			var b = $('#calendar').fullCalendar('getDate');
			alert(b.format('L'));
		}
	});

	calendar.render();

	// $('body').on('click', 'button.fc-prev-button, button.fc-next-button', function() {
	// 	start = calendar.getCurrentData().dateProfile.activeRange.start.toISOString().slice(0, 10);
	// 	end = calendar.getCurrentData().dateProfile.activeRange.end.toISOString().slice(0, 10);
	// 	alert(start + " " + end);
	// });
</script>
@endsection