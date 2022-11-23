@extends('layout.layout2')
@section('css')
<style type="text/css">
</style>
@endsection

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
	<h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">{{ ucwords($tipe) }} SPP</h1>
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
					<h3 class="card-title">VIEW SPPB</h3>
				</div>
				
				<div class="card-body">
					<div class="mb-5 hover-scroll-x">
						<div class="d-grid">
							<ul class="nav nav-tabs flex-nowrap text-nowrap">
								<li class="nav-item">
									<a class="nav-link active btn btn-active-light btn-color-gray-600 btn-active-light-primary rounded-bottom-0" data-bs-toggle="tab" href="#spp">
										<span class="fs-4 fw-bold">SPP</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-light-success rounded-bottom-0" data-bs-toggle="tab" href="#rute_pengiriman">
										<span class="fs-4 fw-bold">Rute Pengiriman</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-light-info rounded-bottom-0" data-bs-toggle="tab" href="#kontrak">
										<span class="fs-4 fw-bold">Kontrak</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-light-danger rounded-bottom-0" data-bs-toggle="tab" href="#spprb">
										<span class="fs-4 fw-bold">List SPPRB</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-light-warning rounded-bottom-0" data-bs-toggle="tab" href="#angkutan">
										<span class="fs-4 fw-bold">List SP3</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
					
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="spp" role="tabpanel">
							@include('pages.spp.view-spp')
						</div>
						<div class="tab-pane fade" id="rute_pengiriman" role="tabpanel">
							@include('pages.spp.view-rute')
						</div>
						<div class="tab-pane fade" id="kontrak" role="tabpanel">
							@include('pages.spp.view-kontrak')
						</div>
						<div class="tab-pane fade" id="spprb" role="tabpanel">
							@include('pages.spp.view-spprb')
						</div>
						<div class="tab-pane fade" id="angkutan" role="tabpanel">
							@include('pages.spp.view-angkutan')
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!--end::Row-->
</div>
<!--end::Content container-->
@endsection
@section('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0f2vYkUlCd6XCyu17DBElvuxyf_4quCU&libraries=places&callback=initMap&language=id"></script>
<style>
	.custom-form {
		display: flex;
	}
	.custom-label {
		display: flex; 
		align-items: center;
		margin-bottom: 0px;
	}
</style>
@endsection
@section('js')
<script type="text/javascript">

$( document ).ready(function() {	
	// loadRute();
	$("#daterange").daterangepicker({
		locale: {
			format: 'DD-MM-YYYY'
		}
	});

	$(".select2spprb").select2({
		ajax: {
			url: '/select2/spprb',
			dataType: 'json',
			data: function (params) {
				return {
					q: $.trim(params.term)
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true,
			minimumInputLength:2
		}
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

	var target = document.querySelector("#kt_block_ui_target");

	var imgLoading = "{{ asset('assets/image_loader.gif') }}";

	var blockUI = new KTBlockUI(target, {
	    message: '<div class="blockui-message"><span class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></span> Loading...</div>',
	});

	$("#draft").submit(function(event) {
		event.preventDefault();

		blockUI.block();

		let data = $(this).serialize();
		let url = $(this).attr('action');

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type:"post",
			url: url,
			data: data,
			success: function(res) {
				$("#kt_block_ui_target").html("");

				$("#kt_block_ui_target").html(res);

				blockUI.release();
			},
			error: function(res) {
				console.log(res);
				blockUI.release();
			}
		})
	});
});
function sdSaatIni(vol, urutan) {
	let saatIni = $("#id-saatini-" + urutan).val();
	let hitungan = parseInt(vol) + parseInt(saatIni);

	$("#id-sdsaatini-" + urutan).val(hitungan);
}

</script>
<script type="text/javascript">
$(document).ready(function () {
    for (i = 1; i <= $('.create_rute').length; i++) {
        generate_map(i);
    }
});

// show detail list on table
$(function() {
    $('.expandChildTable').on('click', function() {
        $(this).toggleClass('selected').closest('tr').next().toggle();
    })
});

$(document).on("click", ".open-AddBookDialog", function () {
    var mapId = $(this).data('map');
    $(".modal-body #mapId").val( mapId );
    $('#add_checkpoint').attr('onClick', 'addCheckpoint(' +mapId+ ');');
});

// delete checkpoint
$(document).ready(function(){
    $(document).on('click', '.delete_rute', function(e) {
        $(this).parent().parent().parent().remove();
    });
});
</script>

<script type="text/javascript">
function addCheckpoint(increment){
    var lat = $('#checkpoint_lat').val();
    var lng = $('#checkpoint_lng').val();

    $('#list_checkpoint_' + increment).append(
    '<div class="row">'+
        '<div class="col-md-12" style="padding-bottom: 5px;">'+
            '<div class="row">'+
                '<div class="col-md-10">'+
                    '<input name="checkpoint_'+ increment +'[]" type="text" class="form-control input-sm" placeholder="" value="'+ lat +','+ lng +'">'+
                '</div>'+
                '<div class="col-md-2" style="text-align:center;">'+
                    '<a href="javascript:void(0)" class="btn btn-icon btn-danger delete_rute align-right"><i class="fas fa-times"></i></a>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>');
}

function initMap() {
    var centerCoordinates = new google.maps.LatLng(-0.789275, 113.921327); // indonesia
    var map = new google.maps.Map(document.getElementById('map_add'), {
        center : centerCoordinates,
        zoom : 6
    });
    var card = document.getElementById('pac-card');
    var input = document.getElementById('pac-input');
    var infowindowContent = document.getElementById('infowindow-content');

    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

    var autocomplete = new google.maps.places.Autocomplete(input);
    var infowindow = new google.maps.InfoWindow();
    infowindow.setContent(infowindowContent);

    var marker = new google.maps.Marker({
        map : map,
        draggable: true
    });

    autocomplete.addListener('place_changed',function() {
        document.getElementById("location-error").style.display = 'none';
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            document.getElementById("location-error").style.display = 'inline-block';
            document.getElementById("location-error").innerHTML = "Cannot Locate '" + input.value + "' on map";
            return;
        }

        map.fitBounds(place.geometry.viewport);
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        infowindowContent.children['place-icon'].src = place.icon;
        infowindowContent.children['place-name'].textContent = place.name;
        infowindowContent.children['place-address'].textContent = input.value;
        infowindow.open(map, marker);

        $('#checkpoint_lat').val(place.geometry.location.lat().toFixed(6));
        $('#checkpoint_lng').val(place.geometry.location.lng().toFixed(6));
    });

    google.maps.event.addListener(marker, 'dragend', function (evt) {
        $('#checkpoint_lat').val(evt.latLng.lat().toFixed(6));
        $('#checkpoint_lng').val(evt.latLng.lng().toFixed(6));

        infowindow.close();
        marker.setVisible(false);
        marker.setMap(map);
        marker.setVisible(true);
    });

    google.maps.event.addListener(marker, 'dragstart', function (evt) {
        document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
    });

    map.setCenter(marker.position);
    marker.setMap(map);
}

// map for each rute
function generate_map(increment) {
    $('#panel_' + increment).empty();
    $('#total_' + increment).empty();

    var waypts = [];
    $("input[name='checkpoint_"+ increment +"[]']")
        .map(function(){
            var temp = $(this).val().split(',');
            waypts.push({
                location: {
                    lat: parseFloat(temp[0]),
                    lng: parseFloat(temp[1])
                },
                stopover: true
            });
    });

    const map = new google.maps.Map(document.getElementById("rute_map_"+ increment), {
        zoom: 6,
        center: { lat: -6.2297419, lng: 106.7594782 }, // Jakarta. -6.2297419,106.7594782
    });
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        draggable: true,
        map,
        panel: document.getElementById("panel_"+ increment),
    });

    directionsRenderer.addListener("directions_changed", () => {
        const directions = directionsRenderer.getDirections();

        if (directions) {
            computeTotalDistance(directions);
        }
    });
    displayRoute(
        // "Surabaya, Surabaya City, East Java, Indonesia",
        // "Sidoarjo, Sidoarjo Regency, East Java, Indonesia",
        directionsService,
        directionsRenderer
    );


    function displayRoute(service, display) {
        service.route({
            // origin: { location: { lat: -6.218410109901146, lng: 106.79832075524945 } }, //GBK -6.218410109901146, 106.79832075524945
            // destination: { location: { lat: -6.180274999666274, lng: 106.82641519051303 } }, // Monas -6.180274999666274, 106.82641519051303
            origin: {
                location: {
                    lat: parseFloat($('#lat_source_' + increment).val()),
                    lng: parseFloat($('#long_source_' + increment).val())
                }
            },
            destination: {
                location: {
                    lat: parseFloat($('#lat_dest_' + increment).val()),
                    lng: parseFloat($('#long_dest_' + increment).val())
                }
            },
            waypoints: waypts,
            // [ values
            //     // { location: { lat: -7.258621, lng: 112.750281 } },
            //     // { location: "Broken Hill, NSW" },
            // ],
            travelMode: google.maps.TravelMode.DRIVING,
            // avoidTolls: true,
            })
            .then((result) => {
                display.setDirections(result);
            })
            .catch((e) => {
                alert("Could not display directions due to: " + e);
            });
    }

    function computeTotalDistance(result) {
        let total = 0;
        const myroute = result.routes[0];

        if (!myroute) {
            return;
        }

        for (let i = 0; i < myroute.legs.length; i++) {
            total += myroute.legs[i].distance.value;
        }

        total = total / 1000;
        document.getElementById("total_"+ increment).innerHTML = total + " km";
    }

    window.initMap = initMap;
}
</script>



<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0f2vYkUlCd6XCyu17DBElvuxyf_4quCU&libraries=places&callback=initMap&language=id"></script>

@endsection