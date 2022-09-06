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
            @if (isset($data))
                {!! Form::model($data, ['route' => ['master-driver.update', $data->id], 'class' => 'form', 'method' => 'put', 'enctype' => 'multipart/form-data']) !!}
            @else
                {!! Form::open(['url' => route('master-driver.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Tambah Baru Driver</h3>
                </div>
            
                <div class="card-body">
                    @if (count($errors) > 0)
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label mt-2">Rekomendasi Rute</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="">
                                    </div>
                                    <div class="col-md-2">
                                        <a href="#" class="btn btn-icon btn-dark"><i class="fas fa-add"></i></a>
                                    </div>
                                </div>   
                            </div>
                        </div>

                        <div class="pac-card" id="pac-card">
                            <div>
                                <div id="label">Location search</div>
                            </div>
                            <div id="pac-container">
                                <input id="pac-input" type="text" placeholder="Enter a location">
                                <div id="location-error"></div>
                            </div>
                        </div>
                        <div id="map" style="height:786px;"></div>
                        <div id="current" sty>Nothing yet...</div>
                        <div id="infowindow-content">
                            <img src="" width="16" height="16" id="place-icon"> <span
                                id="place-name" class="title"></span><br> <span
                                id="place-address"></span>
                        </div>
                        
                        

                    </div>
                </div>
            
                <div class="card-footer" style="text-align: right;">
                    <a href="{{ route('master-driver.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    <input type="submit" class="btn btn-success" value="Simpan">
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
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
<style type="text/css">
    #map {
        height: 100%;
    }
</style>
@endsection

@section('js')
<script>
	function initMap() {
		var centerCoordinates = new google.maps.LatLng(37.6, -95.665);
		var map = new google.maps.Map(document.getElementById('map'), {
			center : centerCoordinates,
			zoom : 4
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
		});

        google.maps.event.addListener(marker, 'dragend', function (evt) {
            document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(6) + ' Current Lng: ' + evt.latLng.lng().toFixed(6) + '</p>';
            infowindow.close();
			marker.setVisible(false);
			var place = autocomplete.getPlace();
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
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0f2vYkUlCd6XCyu17DBElvuxyf_4quCU&libraries=places&callback=initMap"
    async defer></script>
@endsection