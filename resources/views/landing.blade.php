<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.png') }}">
	<title>Nigeria Security & Civil Defence Corps - Personnel Management Platform</title>
	<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
	<style>
		:root {
			--primary-bg-dark: #164f6b; 
			--primary-bg-mid: #0e75a7; 
			--primary-bg-light: #039be5;  
			
			--primary-trans-bg-dark: #164f6b;
			--primary-trans-bg-light: #039be5;
			
			--secondary-bg-dark: #8d1003; 
			--secondary-bg-light: #c91e0b; 
			
			--switch-dark: #164f6b; 
			--switch-light: #039be5; 

			--button-dark: #164f6b; 
			--button-light: #039be5;
			--button-secondary: #8d1003;
		}
	</style>
	<link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet"> <!-- font-awesome -->
	<link rel="stylesheet" href="{{asset('css/material-icons.css')}}">
	
	<script src="{{asset('materialize-css/js/materialize.min.js')}}"></script>
    <link rel="stylesheet" charset="utf-8" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" charset="utf-8" href="{{asset('materialize-css/css/materialize.min.css')}}">

	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<link rel="stylesheet" href="{{asset('css/medium.css')}}">
	<link rel="stylesheet" href="{{asset('css/small.css')}}">
</head>
<body>
	<div class="app" style="display:flex; flex-direction:column; justify-content:center; align-items:center;">
		<div class="card form_wrap">
			<div class="progress">
				<div class="indeterminate"></div>
			</div>
			<div class="heading">
				<img src="{{ asset('storage/pdfLogo.png') }}" alt="logo" width="100px" height="100px">
				<h6 class="">Nigeria Security & Civil Defence Corps</h6>
				<p class="">Personnel Management Platform</p>
			</div>
			<form action="{{ route('login') }}" method="POST" class="form row" id="login_form">
				@csrf
				<div class="input-field col s12">
					<i class="material-icons prefix">person</i>
					<input id="service_number" name="service_number" type="number" required autofocus>
					@if ($errors->has('service_number'))
						<span class="helper-text red-text">
							<strong>{{ $errors->first('service_number') }}</strong>
						</span>
					@endif
					<label for="service_number">{{ __('Service No') }}</label>
				</div>
				<div class="input-field col s12">
					<i class="material-icons prefix">vpn_key</i>
					<input id="password" name="password"  type="password">
					@if ($errors->has('password'))
						<span class="helper-text red-text">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
					@endif
					<label for="password">{{ __('Password') }}</label>
				</div>
				<p class="row">
					<label>
						<input type="checkbox" name="remember" class="filled-in" />
						<span>Remember me!</span>
					</label>
					<button class="login_btn btn waves-effect waves-light" type="submit">Sign In
						<i class="material-icons right">send</i>
					</button>
				</p>
				
				
			</form>
		</div>
	</div>
	<script src="{{ asset('/sw.js') }}"></script>
	<script>
		if (!navigator.serviceWorker.controller) {
			navigator.serviceWorker.register("/sw.js").then(function (reg) {
				console.log("Service worker has been registered for scope: " + reg.scope);
			});
		}
		$(function() {
			$('#login_form').submit(function (e) {
				$('.progress').fadeIn();
				$('.login_btn').prop('disabled', true).html('SIGNING IN <i class="fas fa-circle-notch fa-spin"></i>');
			});
		});
	</script>
</body>
</html>