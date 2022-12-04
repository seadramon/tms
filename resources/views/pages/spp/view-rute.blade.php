<div class="col-12" id="div-rute">
	<!--begin::Accordion-->
	<div class="accordion" id="kt_accordion_1">
		@php $i=1; @endphp
		@foreach($muat as $item)
		<div class="accordion-item">
			<h2 class="accordion-header" id="kt_accordion_{{ $i }}_header_{{ $i }}">
					<button class="accordion-button fs-4 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_{{ $i }}_body_{{ $i }}" aria-expanded="false" aria-controls="kt_accordion_{{ $i }}_body_{{ $i }}">
						Rute Pengiriman {{ $i }}
					</button>
				</h2>
				<div id="kt_accordion_{{ $i }}_body_{{ $i }}" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_{{ $i }}_header_{{ $i }}">
					<div class="accordion-body">
						<div class="row">
							<div class="col-md-6" style="margin-bottom:10px;">
								<div class="row">
									<div class="col-md-12">
										<label class="form-label mt-2">PBB Muat : {{ $item->pat ?? 'Tidak diketahui' }}</label>
										<input type="text" name="ppb_muat[]" value="{{ $item->ppb_muat ?? null }}" hidden="" />
									</div>
								</div>
								<div id="list_checkpoint_{{ $i }}" style="padding-top: 5px;">
									@if($item->potensiH != null && !in_array($item->potensiH->checkpoints, [null, "null"]))
										@foreach( json_decode($item->potensiH->checkpoints ?? "[]",true) as $row)
											<div class="row">
												<div class="col-md-12" style="padding-bottom: 5px;">
													<div class="row">
														<div class="col-md-10">
															<input name="checkpoint_{{ $i }}[]" type="text" class="form-control input-sm" placeholder="" value="{{ $row }}">
														</div>
														<div class="col-md-2" style="text-align:center;">
															<a href="javascript:void(0)" class="btn btn-icon btn-danger hidden delete_rute align-right"><i class="fas fa-times"></i></a>
														</div>
													</div>
												</div>
											</div>
										@endforeach
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<input
									name="source_lat[]"
									id="lat_source_{{ $i }}"
									type="text"
									class="form-control input-sm"
									hidden=""
									value="{{ $item->lat_source }}">

								<input
									name="source_long[]"
									id="long_source_{{ $i }}"
									type="text"
									class="form-control input-sm"
									hidden=""
									value="{{ $item->long_source }}">

								<div class="row">
									<div class="col-md-4">
										<label class="form-label mt-2">Lokasi Tujuan</label>
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control input-sm" readonly value="{{ $item->destination ?? 'Tidak ditemukan' }}">
									</div>
								</div>
								<div class="row" style="padding-top: 10px;">
									<div class="col-md-4">
										<label class="form-label mt-2">Lat/Long</label>
									</div>
									<div class="col-md-4">
										<input name="dest_lat[]" type="text" id="lat_dest_{{ $i }}" class="form-control input-sm" placeholder="Latitude" readonly value="{{ $item->lat_dest }}">
									</div>
									<div class="col-md-4">
										<input name="dest_long[]" type="text" id="long_dest_{{ $i }}" class="form-control input-sm" placeholder="Longitude" readonly value="{{ $item->long_dest }}">
									</div>
								</div>

							</div>
						</div>
						<div class="row mt-5">
							<div class="col-md-6">
								<a
									style="width: 100%;"
									href="javacript:void(0)"
									class="btn btn-success open-AddBookDialog hidden"
									data-bs-toggle="modal"
									data-bs-target="#kt_modal_1"
									data-map="{{ $i }}">
									<i class="fas fa-add"></i> Tambah Rute
								</a>

							</div>
							<div class="col-md-6">
								<a style="width: 100%;"
									class="btn btn-block btn-danger create_rute hidden"
									id="create_rute_{{ $i }}"
									onclick="generate_map({{ $i }}); return false;">Generate Rutes
								</a>

							</div>
						</div>
						<hr style="border-top: 1px dotted black;">
						<div class="row">
							<div class="col-md-8">
								<div id="rute_map_{{ $i }}" style="height:500px;"></div>
							</div>
							<div class="col-md-4">
								<div id="sidebar_{{ $i }}" class="scroll h-500px px-5">
									<p>Total Distance: <span id="total_{{ $i }}"></span></p>
									<div id="panel_{{ $i }}"></div>
								</div>
							</div>
						</div>

						<hr style="border-top: 1px dotted black;">

						<table class="table table-striped">
							<tr>
								<td>Jalan</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="baik" id="flexCheckDefault" name="jalan_{{ $i }}"
											@if(!empty($item->potensiH))
												@if($item->potensiH->jalan == 'baik')
													checked=""
												@endif
											@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Baik
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="kurang_baik" id="flexCheckDefault" name="jalan_{{ $i }}"
											@if(!empty($item->potensiH))
												@if($item->potensiH->jalan == 'kurang_baik')
													checked=""
												@endif
											@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Kurang Baik
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="rusak" id="flexCheckDefault" name="jalan_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan == 'rusak')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Rusak
										</label>
									</div>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="menanjak" id="flexCheckDefault" name="jalan2_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan2 == 'menanjak')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Menanjak
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="berkelok" id="flexCheckDefault" name="jalan2_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan2 == 'berkelok')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Berkelok
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="lain_lain" id="flexCheckDefault" name="jalan2_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan2 == 'lain_lain')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Lain - Lain
										</label>
									</div>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Jembatan</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="baik" id="flexCheckDefault" name="jembatan_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jembatan == 'baik')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Baik
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="kurang_baik" id="flexCheckDefault" name="jembatan_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jembatan == 'kurang_baik')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Kurang Baik
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="tidak_ada" id="flexCheckDefault" name="jembatan_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jembatan == 'tidak_ada')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Tidak Ada
										</label>
									</div>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Jalan Alternatif</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="baik" id="flexCheckDefault" name="jalan_alternatif_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan_alt == 'baik')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Baik
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="kurang_baik" id="flexCheckDefault" name="jalan_alternatif_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan_alt == 'kurang_baik')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Kurang Baik
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="rusak" id="flexCheckDefault" name="jalan_alternatif_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan_alt == 'rusak')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Rusak
										</label>
									</div>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="menanjak" id="flexCheckDefault" name="jalan_alternatif2_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan_alt2 == 'menanjak')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Menanjak
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="berkelok" id="flexCheckDefault" name="jalan_alternatif2_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan_alt2 == 'berkelok')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Berkelok
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="lain_lain" id="flexCheckDefault" name="jalan_alternatif2_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jalan_alt2 == 'lain_lain')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Lain - Lain
										</label>
									</div>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Langsir</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="tidak_ada" id="flexCheckDefault" name="langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->langsir == 'tidak_ada')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Tidak Ada
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="mobil" id="flexCheckDefault" name="langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->langsir == 'mobil')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Mobil
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="gerobak" id="flexCheckDefault" name="langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->langsir == 'gerobak')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Gerobak
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="roll_geser" id="flexCheckDefault" name="langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->langsir == 'roll_geser')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Roll Geser
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="manusia" id="flexCheckDefault" name="langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->langsir == 'manusia')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Manusia
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="lain_lain" id="flexCheckDefault" name="langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->langsir == 'lain_lain')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Lain - Lain
										</label>
									</div>
								</td>
							</tr>
							<tr>
								<td>Jarak Langsir</td>
								<td>
									<div class="form-check form-check-custom form-check-solid mt-1">
										<input class="form-check-input" type="radio" disabled value="500" id="flexCheckDefault" name="jarak_langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jarak_langsir == '500')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											< 500 M
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid mt-1">
										<input class="form-check-input" type="radio" disabled value="500_1000" id="flexCheckDefault" name="jarak_langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jarak_langsir == '500_1000')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											500 s/d 1.000 M
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid mt-1">
										<input class="form-check-input" type="radio" disabled value="1000" id="flexCheckDefault" name="jarak_langsir_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->jarak_langsir == '1000')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											> 1.000 M
										</label>
									</div>
								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Metode Penurunan</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="crene" id="flexCheckDefault" name="metode_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->metode == 'crene')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Crane
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="portal" id="flexCheckDefault" name="metode_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->metode == 'portal')
												checked=""
											@endif
										@endif
										/>
										<label class="form-check-label" for="flexCheckDefault">
											Portal
										</label>
									</div>
								</td>
								<td>
									<div class="form-check form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" disabled value="manual" id="flexCheckDefault" name="metode_{{ $i }}"
										@if(!empty($item->potensiH))
											@if($item->potensiH->metode == 'manual')
												checked=""
											@endif
										@endif
										/>
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
			</div>
		@php $i++; @endphp
		@endforeach
	</div>
	<!--end::Accordion-->
</div>