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
	<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/super-build/ckeditor.js"></script>
	<script type="text/javascript">
		var empty_ = true;

		var option = {
			// https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
			toolbar: {
				items: [
					// 'exportPDF','exportWord', '|',
					// 'findAndReplace', 'selectAll', '|',
					'heading', '|',
					'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
					'bulletedList', 'numberedList', 'todoList', '|',
					'outdent', 'indent', '|',
					'undo', 'redo',
					'-',
					// 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
					'fontSize', 'fontFamily', 'fontColor', '|',
					'alignment', '|',
					// 'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
					// 'specialCharacters', 'horizontalLine', 'pageBreak', '|',
					// 'textPartLanguage', '|',
					'sourceEditing'
				],
				shouldNotGroupWhenFull: true
			},
			// Changing the language of the interface requires loading the language file using the <script> tag.
			// language: 'es',
			list: {
				properties: {
					styles: true,
					startIndex: true,
					reversed: true
				}
			},
			// https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
			heading: {
				options: [
					{ model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
					{ model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
					{ model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
					{ model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
					{ model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
					{ model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
					{ model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
				]
			},
			// https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
			// placeholder: 'Welcome to CKEditor 5!',
			// https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
			fontFamily: {
				options: [
					'default',
					'Arial, Helvetica, sans-serif',
					'Courier New, Courier, monospace',
					'Georgia, serif',
					'Lucida Sans Unicode, Lucida Grande, sans-serif',
					'Tahoma, Geneva, sans-serif',
					'Times New Roman, Times, serif',
					'Trebuchet MS, Helvetica, sans-serif',
					'Verdana, Geneva, sans-serif'
				],
				supportAllValues: true
			},
			// https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
			fontSize: {
				options: [ 10, 12, 14, 'default', 18, 20, 22 ],
				supportAllValues: true
			},
			// Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
			// https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
			htmlSupport: {
				allow: [
					{
						name: /.*/,
						attributes: true,
						classes: true,
						styles: true
					}
				]
			},
			// Be careful with enabling previews
			// https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
			htmlEmbed: {
				showPreviews: true
			},
			// https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
			link: {
				decorators: {
					addTargetToExternalLinks: true,
					defaultProtocol: 'https://',
					toggleDownloadable: {
						mode: 'manual',
						label: 'Downloadable',
						attributes: {
							download: 'file'
						}
					}
				}
			},
			// https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
			mention: {
				feeds: [
					{
						marker: '@',
						feed: [
							'@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
							'@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
							'@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
							'@sugar', '@sweet', '@topping', '@wafer'
						],
						minimumCharacters: 1
					}
				]
			},
			// The "superbuild" contains more premium features that require additional configuration, disable them below.
			// Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
			removePlugins: [
				// These two are commercial, but you can try them out without registering to a trial.
				'ExportPdf',
				'ExportWord',
				'AIAssistant',
				'CKBox',
				'CKFinder',
				'EasyImage',
				// This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
				// https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
				// Storing images as Base64 is usually a very bad idea.
				// Replace it on production website with other solutions:
				// https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
				// 'Base64UploadAdapter',
				'RealTimeCollaborativeComments',
				'RealTimeCollaborativeTrackChanges',
				'RealTimeCollaborativeRevisionHistory',
				'PresenceList',
				'Comments',
				'TrackChanges',
				'TrackChangesData',
				'RevisionHistory',
				'Pagination',
				'WProofreader',
				// Careful, with the Mathtype plugin CKEditor will not load when loading this sample
				// from a local file system (file://) - load this site via HTTP server if you enable MathType.
				'MathType',
				// The following features are part of the Productivity Pack and require additional license.
				'SlashCommand',
				'Template',
				'DocumentOutline',
				'FormatPainter',
				'TableOfContents',
				'PasteFromOfficeEnhanced',
				'CaseChange'
			],
            format_tags: 'p;;h1;h2;h3;h4;h5;h6;h7;pre;address;div',
            format_p: {
                element: 'p',
                name: 'p',
                attributes: { 'class': 'editorTitle1' },
                style: {
                    'margin-top': '0px',
                    'margin-bottom': '0px',
                }
            }
		}

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
					CKEDITOR.ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index), option).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
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
				// CKEDITOR.ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index)).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
				index++;
			});
		}
		function initCkEditExisting(){
			var index = 1;
			$("div[data-repeater-item]").each(function(){
				CKEDITOR.ClassicEditor.create(document.querySelector('#pasal_ckeditor' + index), option).then(editor => { console.log(editor); }).catch(error => { console.error(error); });
				index++;
			});
		}
	</script>
@endsection
