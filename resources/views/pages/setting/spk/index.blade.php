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
			{!! Form::open(['url' => route('setting-spk.store'), 'class' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
			<!--begin::Tables Widget 12-->
			<div class="card mb-5 mb-xl-8">
				<!--begin::Header-->
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bolder fs-3 mb-1">Setting SPK Pasal</span>
						<!-- <span class="text-muted mt-1 fw-bold fs-7">Over 500 new members</span> -->
					</h3>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body py-3">
					<div id="pasal" data-index="{{ $spk ? $spk->count() : 0 }}">
						<div class="form-group">
							<div class="accordion" id="kt_accordion__">
								<div data-repeater-list="pasal">
									@if ($spk->count() > 0)
										@foreach ($spk as $index => $pasal)
											<div data-repeater-item>
												<div class="accordion-item">
													<h2 class="accordion-header" id="pasal-header-{{ $index + 1 }}">
														<button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pasal-body-{{ $index + 1 }}" aria-expanded="true" aria-controls="pasal-body-{{ $index + 1 }}">
															Pasal {{ $index + 1 }}
														</button>
													</h2>
													<div id="pasal-body-{{ $index + 1 }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
														<div class="accordion-body">
															<div class="form-group col-12">
																<label class="form-label">Judul</label>
																{!! Form::text('pasal_judul', $pasal->data['judul'], ['class'=>'form-control', 'id'=>'pasal_judul' . ($index + 1)]) !!}
															</div>
															<div class="form-group col-12" data-bs-theme="light">
																<textarea name="pasal_isi" class="ckeditor" id="pasal_ckeditor{{ $index + 1 }}">
																	{!! $pasal->data['isi'] !!}
																</textarea>
															</div>
															{{-- <div class="form-group col-12">
																<a href="javascript:;" data-repeater-delete class="btn btn-md btn-light-danger mt-md-8">
																	<i class="la la-trash-o"></i>Hapus
																</a>
															</div> --}}
														</div>
													</div>
												</div>
											</div>
										@endforeach
									@else
										<div data-repeater-item>
											<div class="accordion-item">
												<h2 class="accordion-header" id="kt_accordion_1_header_1">
													<button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="true" aria-controls="kt_accordion_1_body_1">
														Pasal 1
													</button>
												</h2>
												<div id="kt_accordion_1_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
													<div class="accordion-body">
														<div class="form-group col-12">
															<label class="form-label">Judul</label>
															{!! Form::text('pasal_judul', '', ['class'=>'form-control', 'id'=>'pasal_judul1']) !!}
														</div>
														<div class="form-group col-12" data-bs-theme="light">
															<textarea name="pasal_isi" class="ckeditor" id="">
															
															</textarea>
														</div>
														<div class="form-group col-12">
															<a href="javascript:;" data-repeater-delete class="btn btn-md btn-light-danger mt-md-8">
																<i class="la la-trash-o"></i>Hapus
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endif
								</div>
							</div>
						</div>
						<div class="form-group" style="margin-top: 20px">
							<div class="col-md-3">
								<a href="javascript:;" data-repeater-create class="btn btn-light-primary">
									<i class="la la-plus"></i>Tambah
								</a>
							</div>
						</div>
					</div>
				</div>
				<!--begin::Body-->
				<div class="card-footer" style="text-align: right;">
					<input type="submit" class="btn btn-success" id="btn-sumbit" value="Simpan">
				</div>
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
	<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
	<script src="{{ asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
	<script type="text/javascript">
		var empty_ = true;
		$(document).ready(function(){
			@if($spk->count() > 0)
				initCkEditExisting();
				empty_ = false;
			@endif
			$('#pasal').repeater({
				initEmpty: empty_,
	
				show: function () {
					$(this).slideDown();
					
					var index = parseInt($("#pasal").attr('data-index')) + 1;
					$(this).find('.accordion-header').attr('id', 'pasal-header-' + index);
					$(this).find('.accordion-button').attr('data-bs-target', '#pasal-body-' + index);
					$(this).find('.accordion-button').attr('aria-controls', '#pasal-body-' + index);
					$(this).find('.accordion-button').text("Pasal " + index);
					$(this).find('.accordion-collapse').attr('id', 'pasal-body-' + index);
					// reinit ckeditor
					// $(this).find('.ck-editor').remove();
					$(this).find('.ckeditor').attr('id', 'pasal_ckeditor' + index);
					ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index)).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
					$("#pasal").attr('data-index', index);
					reOrganizeItemPasal();
				},
	
				hide: function (deleteElement) {
					$(this).slideUp(deleteElement);
					reOrganizeItemPasal();
				},
				ready: function (setIndexes) {
				}
			});
		});

		function reOrganizeItemPasal(){
			var index = 1;
			$("div[data-repeater-item]").each(function(){
				// $(this).find('.accordion-header').attr('id', 'pasal-header-' + index);
				// $(this).find('.accordion-button').attr('data-bs-target', '#pasal-body-' + index);
				// $(this).find('.accordion-button').attr('aria-controls', '#pasal-body-' + index);
				$(this).find('.accordion-button').text("Pasal " + index);
				// $(this).find('.accordion-collapse').attr('id', 'pasal-body-' + index);
				// reinit ckeditor
				// $(this).find('.ck-editor').remove();
				// $(this).find('.ckeditor').attr('id', 'pasal_ckeditor' + index);
				// ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index)).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
				index++;
			});
		}
		function initCkEditExisting(){
			var index = 1;
			$("div[data-repeater-item]").each(function(){
				ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index)).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
				index++;
			});
		}
	</script>
@endsection