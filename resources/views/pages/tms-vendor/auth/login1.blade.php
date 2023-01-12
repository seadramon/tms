<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic | Bootstrap HTML, VueJS, React, Angular, Asp.Net Core, Blazor, Django, Flask & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head><base href="../../../"/>
		<title>Transport Management System</title>
		<meta charset="utf-8" />
		<meta name="description" content="Transport Management System" />
		<meta name="keywords" content="TMS, WIKA, WTON" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Transport Management System" />
		<meta property="og:url" content="http://tms.wika-beton.co.id" />
		<meta property="og:site_name" content="Keenthemes | Metronic" />
		<link rel="canonical" href="http://tms.wika-beton.co.id" />
		<link rel="shortcut icon" href="{{ asset('content/TMS - icon version - white.png') }}" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
		<!--begin::Theme mode setup on page load-->
		<script>
			var defaultThemeMode = "light"; 
			var themeMode; 
			if ( document.documentElement ) { 
				if ( document.documentElement.hasAttribute("data-theme-mode")) { 
					themeMode = document.documentElement.getAttribute("data-theme-mode"); 
				} else { 
					if ( localStorage.getItem("data-theme") !== null ) { 
						themeMode = localStorage.getItem("data-theme"); 
					} else { themeMode = defaultThemeMode; 
					} 
				} 
				if (themeMode === "system") { 
					themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; 
				} 
				document.documentElement.setAttribute("data-theme", themeMode); 
			}
		</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page bg image-->
			<style>
				body { 
					background-image: url('{{asset('content/proyek.jpeg')}}'); 
				} 
				[data-theme="dark"] body { 
					background-image: url('{{asset('content/proyek.jpeg')}}'); 
				}
			</style>
			<!--end::Page bg image-->
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid flex-lg-row">
				<!--begin::Aside-->
				<div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
					<!--begin::Aside-->
					<div class="d-flex flex-center flex-lg-start flex-column">
						<!--begin::Logo-->
						<a href="../../demo2/dist/index.html" class="mb-7">
							<img alt="Logo" src="{{asset('content/TMS - full version.jpg')}}" style="width: 350px;" />
						</a>
						<!--end::Logo-->
						<!--begin::Title-->
						<h2 class="text-white fw-normal m-0">Transport Management System | PT Wijaya Karya Beton Tbk.</h2>
						<!--end::Title-->
					</div>
					<!--begin::Aside-->
				</div>
				<!--begin::Aside-->
				<!--begin::Body-->
				<div class="d-flex flex-center w-lg-50 p-10">
					<!--begin::Card-->
					<div class="card rounded-3 w-md-550px">
						<!--begin::Card body-->
						<div class="card-body p-10 p-lg-20">
							<!--begin::Form-->
							<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ url('vendor/login') }}" method="POST">
								@csrf
								<!--begin::Heading-->
								<div class="text-center mb-11">
									<!--begin::Title-->
									<h1 class="text-dark fw-bolder mb-3">Login</h1>
									<!--end::Title-->
									<!--begin::Subtitle-->
									<div class="text-gray-500 fw-semibold fs-6">Vendor | Transport Management System</div>
									<!--end::Subtitle=-->
								</div>
								@if ($errors->has('general'))
									<!--begin::Alert-->
									<div class="alert alert-danger">
									    <!--begin::Wrapper-->
									    <div class="d-flex flex-column">
									        <!--begin::Content-->
									        <span>{{ $errors->first('general') }}</span>
									        <!--end::Content-->
									    </div>
									    <!--end::Wrapper-->
									</div>
									<!--end::Alert-->
									<!--begin::Separator-->
									<div class="separator separator-content my-14">
										<span class="w-125px text-gray-500 fw-semibold fs-7"> please login </span>
									</div>
									<!--end::Separator-->
				                @endif
								<!--begin::Heading-->
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Email-->
									<input type="text" placeholder="Email" name="username" autocomplete="off" class="form-control bg-transparent" />
									<!--end::Email-->
									@if ($errors->has('username'))
					                    <span class="text-danger">{{ $errors->first('username') }}</span>
					                @endif
								</div>
								<!--end::Input group=-->
								<div class="fv-row mb-3">
									<!--begin::Password-->
									<input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" />
									<!--end::Password-->
									@if ($errors->has('password'))
					                    <span class="text-danger">{{ $errors->first('password') }}</span>
					                @endif
								</div>
								<!--end::Input group=-->
								
								<!--begin::Submit button-->
								<div class="d-grid mb-10">
									<button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
										<!--begin::Indicator label-->
										<span class="indicator-label">Sign In</span>
										<!--end::Indicator label-->
										<!--begin::Indicator progress-->
										<span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
										<!--end::Indicator progress-->
									</button>
								</div>
								<!--end::Submit button-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Card body-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--end::Main-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used by this page)-->
		{{-- <script src="assets/js/custom/authentication/sign-in/general.js"></script> --}}
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>