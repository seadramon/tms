@extends('layout.layout2')

@section('page-title')
<!--begin::Page title-->
<div class="page-title d-flex justify-content-center flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">Potensi Detail Armada</h1>
</div>
<!--end::Page title-->
@endsection

@section('content')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<!--begin::Content container-->
<div id="kt_content_container" class="container-xxl">
     <!--begin::Row-->
     <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12 mb-md-5 mb-xl-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Detail Rute Pengiriman</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped gy-7 gs-7">
                                <tr>
                                    <td>Jalan</td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Baik
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Kurang Baik
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Rusak
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Menanjak
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Berkelok
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Lain - Lain
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jembatan</td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="Jembatan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Baik
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="Jembatan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Kurang Baik
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="Jembatan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Tidak Ada
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jalan Alternatif</td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan_alternatif"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Baik
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan_alternatif"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Kurang Baik
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan_alternatif"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Rusak
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan_alternatif"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Menanjak
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan_alternatif"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Berkelok
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="jalan_alternatif"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Lain - Lain
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Langsir</td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Tidak Ada
                                            </label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid mt-1">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                < 500 M
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Mobil
                                            </label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid mt-1">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                500 s/d 1.000 M
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Gerobak
                                            </label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid mt-1">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                > 1.000 M 
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Roll Geser
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Manusia
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="langsir"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Lain - Lain
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Metode Penurunan</td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="penurunan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Crane
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="penurunan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Portal
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="1" id="flexCheckDefault" name="penurunan"/>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Manual
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>   
                        </div>                     
                    </div>                    
                <!-- end of card-body -->
                </div>            
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-12 mb-md-5 mb-xl-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Prakiraan Pencitraan Peta Rute Pengiriman</h3>
                </div>
            
                <div class="card-body">
                    @if(count($errors) > 0)
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif

                    <div class="row">
                        <div class="col-md-6" style="margin-bottom:10px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label mt-2">PBB Muat</label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" data-control="select2" data-placeholder="Select PBB Muat..">
                                        <option></option>
                                        @foreach($pat as $row)
                                            <option value="{{ $row->kd_pat }}">{{ $row->ket }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div id="list_checkpoint" style="padding-top: 5px;"></div> 
                           
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label mt-2">Lokasi Tujuan</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control input-sm" placeholder="">
                                </div>
                            </div>
                            <div class="row" style="padding-top: 10px;">
                                <div class="col-md-4">
                                    <label class="form-label mt-2">Lat/Long</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" placeholder="Latitude">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" placeholder="Latitude">
                                </div>
                            </div> 
                           
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <a style="width: 100%;" href="javacript:void(0)" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_1"><i class="fas fa-add"></i> Tambah Rute</a>

                        </div>
                        <div class="col-md-6">
                            <a style="width: 100%;" href="javascript:void(0)" class="btn btn-block btn-danger" id="create_rute">Generate Rutes</a>
                        </div>
                    </div>
                    <hr style="border-top: 1px dotted black;">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="rute_map" style="height:500px;"></div>
                        </div>
                        <div class="col-md-4">
                            <div id="sidebar">
                                <p>Total Distance: <span id="total"></span></p>
                                <div id="panel"></div>
                            </div>
                        </div>
                    </div>  
                <!-- end of card-body -->
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ URL::previous() }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    <input type="submit" class="btn btn-success" value="Simpan">
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<!--end::Content container-->

<!-- add checkpoint modals -->
<div class="modal fade" tabindex="-1" id="kt_modal_1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Cari Lokasi (Google Maps)</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="pac-card" id="pac-card">
                    <div>
                        <div id="label">Location search</div>
                    </div>
                    <div id="pac-container" class="mb-1">
                        <input class="form-control input-sm" id="pac-input" type="text" placeholder="Enter a location">
                        <div id="location-error"></div>
                    </div>
                </div>
                <div id="map" style="height:500px;"></div>
                <div id="current" hidden="">Nothing yet...</div>
                <input id="checkpoint_lat" hidden="" type="text" />
                <input id="checkpoint_lng" hidden="" type="text" />
                <div id="infowindow-content">
                    <img src="" width="16" height="16" id="place-icon"> <span
                        id="place-name" class="title"></span><br> <span
                        id="place-address"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="add_checkpoint">Add Checkpoint</button>
            </div>
        </div>
    </div>
</div>
<!-- end of modals -->


@endsection

@section('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style type="text/css">
    #map {
        height: 100%;
    }

    .pac-container {
        z-index: 10000 !important;
    }
    #checkpoint_lat  {
        z-index: 10000 !important;
    }

    #checkpoint_lng {
        z-index: 10000 !important;
    }
</style>
@endsection

@section('js')
<script type="text/javascript">
// delete checkpoint
$(document).ready(function(){
    $(document).on('click', '.delete_rute', function(e) {
        $(this).parent().parent().parent().remove();
    });

   
    $("#add_checkpoint").click(function(){
        var lat = $('#checkpoint_lat').val();
        var lng = $('#checkpoint_lng').val();
        
        $('#list_checkpoint').append(
            '<div class="col-md-12" style="padding-bottom: 5px;">'+
                '<div class="row">'+
                    '<div class="col-md-4">'+
                        '<label class="form-label mt-2"></label>'+
                    '</div>'+
                    '<div class="col-md-6">'+
                        '<input name="checkpoint[]" type="text" class="form-control input-sm" placeholder="" value="'+ lat +','+ lng +'">'+
                    '</div>'+
                    '<div class="col-md-2" style="text-align:center;">'+
                        '<a href="javascript:void(0)" class="btn btn-icon btn-danger delete_rute align-right"><i class="fas fa-times"></i></a>'+
                    '</div>'+
                '</div>'+ 
            '</div>');
    });

    $(document).on('click', '#create_rute', function(e) {
        $('#panel').empty();
        $('#total').empty();

        var waypts = [];
        $("input[name='checkpoint[]']")
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
     
        const map = new google.maps.Map(document.getElementById("rute_map"), {
            zoom: 6,
            // center: { lat: -7.258621, lng: 112.750281 }, // Indonesia.
            center: { lat: -6.2297419, lng: 106.7594782 }, // Jakarta. -6.2297419,106.7594782
        });
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            draggable: true,
            map,
            panel: document.getElementById("panel"),
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
                origin: { location: { lat: -6.218410109901146, lng: 106.79832075524945 } }, //GBK -6.218410109901146, 106.79832075524945
                destination: { location: { lat: -6.180274999666274, lng: 106.82641519051303 } }, // Monas -6.180274999666274, 106.82641519051303
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
            document.getElementById("total").innerHTML = total + " km";
        }

        window.initMap = initMap;

    });
});
</script>


<script type="text/javascript">
	function initMap() {
		var centerCoordinates = new google.maps.LatLng(-0.789275, 113.921327); // indonesia
		var map = new google.maps.Map(document.getElementById('map'), {
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
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0f2vYkUlCd6XCyu17DBElvuxyf_4quCU&libraries=places&callback=initMap&language=id"></script>
@endsection