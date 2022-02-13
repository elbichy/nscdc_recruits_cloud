@extends('layouts.app', ['title' => 'Add New Records'])

@section('content')
    <div id="new" class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SECTION HEADING --}}
                <h6 class="center sectionHeading">NEW PERSONNEL</h6>

                {{-- SECTION TABLE --}}
                <div class="sectionFormWrap z-depth-1" style="padding:24px;">
					<p class="formMsg grey lighten-3 left-align">
						Fill the form below with the personnel information and submit, or click the green floating button by the right buttom corner of this screen to import multiple personnel records from an excel document.
					</p>
					<form action="{{ route('store_personnel') }}" method="POST" enctype="multipart/form-data" name="create_form" id="create_form" class="create_new_form">
						@csrf
						<fieldset class="row">
							<legend>PERSONAL DATA</legend>
							<div class="row">
								{{-- Fullname --}}
								<div class="input-field col s12 l6">
									<input id="name" name="name" type="text" value="{{ old('name') }}" required>
									@if ($errors->has('name'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('name') }}</strong>
										</span>
									@endif
									<label for="name">* Fullname</label>
								</div>
								{{-- Date of Birth --}}
								<div class="input-field col s12 l3">
									<input id="dob" name="dob" type="date" value="{{ old('dob') }}" required>
									@if ($errors->has('dob'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('dob') }}</strong>
										</span>
									@endif
									<label for="dob">* Date of Birth</label>
								</div>
								{{-- Gender --}}
								<div class="col s12 l3">
									<label for="sex">* Select Sex</label>
									<select id="sex" name="sex" class=" browser-default" required>
										<option disabled>Select Type</option>
										<option value="male" selected>Male</option>
										<option value="female">Female</option>
										<option value="other">Other</option>
									</select>
									@if ($errors->has('sex'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('sex') }}</strong>
										</span>
									@endif
								</div>
							</div>

							<div class="row">
								{{-- Marital Status --}}
								<div class="col s12 l4">
									<label for="marital_status">Marital status</label>
									<select id="marital_status" name="marital_status" class=" browser-default">
										<option disabled selected>Select status</option>
										<option value="single" >Single</option>
										<option value="married" >Married</option>
										<option value="separated" >Separated</option>
										<option value="widowed" >Widowed</option>
										<option value="divorced" >Divorced</option>
									</select>
									@if ($errors->has('marital_status'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('marital_status') }}</strong>
										</span>
									@endif
								</div>
								{{-- Name of spouse --}}
								<div class="input-field col s12 l4">
									<input id="name_of_spouse" name="name_of_spouse" type="text" value="{{ old('name_of_spouse') }}">
									@if ($errors->has('name'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('name_of_spouse') }}</strong>
										</span>
									@endif
									<label for="name_of_spouse">Name of spouse</label>
								</div>
								{{-- Date of Marriage --}}
								<div class="input-field col s12 l4">
									<input id="date_of_marriage" name="date_of_marriage" type="date" value="{{ old('date_of_marriage') }}">
									@if ($errors->has('name'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('date_of_marriage') }}</strong>
										</span>
									@endif
									<label for="date_of_marriage">Date of marriage</label>
								</div>
							</div>
							<div class="row">
								{{-- Religion --}}
								<div class="col s12 l4">
									<label for="religion">Select religion</label>
									<select id="religion" name="religion" class=" browser-default">
										<option disabled selected>Select religion</option>
										<option value="christianity" >Christianity</option>
										<option value="islam" >Islam</option>
										<option value="other" >Other</option>
									</select>
									@if ($errors->has('religion'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('religion') }}</strong>
										</span>
									@endif
								</div>
								{{-- Blood Group --}}
								<div class="col s12 l4">
									<label for="blood_group">Select Blood Group</label>
									<select id="blood_group" name="blood_group" class=" browser-default">
										<option disabled selected>Select Type</option>
										<option value="o+">O+</option>
										<option value="o-">O-</option>
										<option value="a+">A+</option>
										<option value="a-">A-</option>
										<option value="b+">B+</option>
										<option value="b-">B-</option>
										<option value="ab+">AB+</option>
										<option value="ab-">AB-</option>
									</select>
									@if ($errors->has('blood_group'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('blood_group') }}</strong>
										</span>
									@endif
								</div>
								{{-- Genotype --}}
								<div class="col s12 l4">
									<label for="genotype">Select Gynotype</label>
									<select id="genotype" name="genotype" class=" browser-default">
										<option disabled selected>Select Type</option>
										<option value="aa">AA</option>
										<option value="as-">AS</option>
										<option value="ac">AC</option>
										<option value="ss">SS</option>
									</select>
									@if ($errors->has('genotype'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('genotype') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="row">
								{{-- Height --}}
								<div class="input-field col s12 l4">
									<input id="height" name="height" type="text" value="{{ old('height') }}">
									@if ($errors->has('height'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('height') }}</strong>
										</span>
									@endif
									<label for="height">Height</label>
									<span class="helper-text">e.g. 6.5m</span>
								</div>
								{{-- Weight --}}
								<div class="input-field col s12 l4">
									<input id="weight" name="weight" type="text" value="{{ old('weight') }}">
									@if ($errors->has('weight'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('weight') }}</strong>
										</span>
									@endif
									<label for="weight">Weight</label>
									<span class="helper-text">e.g. 85kg</span>
								</div>
							</div>
						</fieldset>
						<fieldset class="row">
							<legend>CONTACT DATA</legend>
							<div class="row">
								{{-- State of Origin --}}
								<div class="col s12 l3">
									<label for="soo">* State of Origin</label>
									<select id="soo" name="soo" class="browser-default" required>
										<option disabled selected>Select State</option>
										<option value="1">Abia</option>
										<option value="2">Adamawa</option>
										<option value="3">Akwa-ibom</option>
										<option value="4">Anambra</option>
										<option value="5">Bauchi</option>
										<option value="6">Bayelsa</option>
										<option value="7">Benue</option>
										<option value="8">Borno</option>
										<option value="9">Cross-river</option>
										<option value="10">Delta</option>
										<option value="11">Ebonyi</option>
										<option value="12">Edo</option>
										<option value="13">Ekiti</option>
										<option value="14">Enugu</option>
										<option value="15">Fct</option>
										<option value="16">Gombe</option>
										<option value="17">Imo</option>
										<option value="18">Jigawa</option>
										<option value="19">Kaduna</option>
										<option value="20">Kano</option>
										<option value="21">Katsina</option>
										<option value="22">Kebbi</option>
										<option value="23">Kogi</option>
										<option value="24">Kwara</option>
										<option value="25">Lagos</option>
										<option value="26">Nasarawa</option>
										<option value="27">Niger</option>
										<option value="28">Ogun</option>
										<option value="29">Ondo</option>
										<option value="30">Osun</option>
										<option value="31">Oyo</option>
										<option value="32">Plateau</option>
										<option value="33">Rivers</option>
										<option value="34">Sokoto</option>
										<option value="35">Taraba</option>
										<option value="36">Yobe</option>
										<option value="37">Zamfara</option>
									</select>
									@if ($errors->has('soo'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('soo') }}</strong>
										</span>
									@endif
								</div>
								{{-- Local Govt --}}
								<div class="col s12 l3">
									<label for="lgoo">* Local Govt.</label>
									<select id="lgoo" name="lgoo" class="browser-default" required>
										<option disabled selected>Select State first</option>
									</select>
									@if ($errors->has('lgoo'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('lgoo') }}</strong>
										</span>
									@endif
								</div>
								{{-- Residential Address --}}
								<div class="input-field col s12 l6">
									<input id="residential_address" name="residential_address" type="text" value="{{ old('residential_address') }}" placeholder="Area, Town, State.">
									@if ($errors->has('residential_address'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('residential_address') }}</strong>
										</span>
									@endif
									<label for="residential_address">Current residential address</label>
								</div>
							</div>

							<div class="row">
								{{-- Permanent Address --}}
								<div class="input-field col s12 l6">
									<input id="permanent_address" name="permanent_address" type="text" value="{{ old('permanent_address') }}" placeholder="Area, Town, State.">
									@if ($errors->has('permanent_address'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('permanent_address') }}</strong>
										</span>
									@endif
									<label for="permanent_address">Permanent address</label>
								</div>
								{{-- Place of Birth --}}
								<div class="input-field col s12 l2">
									<input id="place_of_birth" name="place_of_birth" type="text" value="{{ old('place_of_birth') }}" placeholder="Area, Town or State.">
									@if ($errors->has('place_of_birth'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('place_of_birth') }}</strong>
										</span>
									@endif
									<label for="place_of_birth">Place of Birth</label>
								</div>
								{{-- Phone --}}
								<div class="input-field col s12 l2">
									<input id="phone_number" name="phone_number" type="number" value="{{ old('phone_number') }}" class="input_text" data-length="11">
									@if ($errors->has('phone_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('phone_number') }}</strong>
										</span>
									@endif
									<label for="phone_number">Phone no.</label>
								</div>
								{{-- Email --}}
								<div class="input-field col s12 l2">
									<input id="email" name="email" type="text" value="{{ old('email') }}">
									@if ($errors->has('email'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
									@endif
									<label for="email">Email address</label>
								</div>
							</div>
						</fieldset>
						<fieldset class="row">
							<legend>OFFICIAL DATA</legend>
							<div class="row">
								{{-- Service Number --}}
								<div class="input-field col s12 l3">
									<input id="service_number" name="service_number" type="number" value="{{ old('service_number') }}" class="input_text" data-length="5" required>
									@if ($errors->has('service_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('service_number') }}</strong>
										</span>
									@endif
									<label for="service_number">* Service No.</label>
								</div>
								{{-- Date of 1st Appt. --}}
								<div class="input-field col s12 l3">
									<input id="dofa" name="dofa" type="date" value="2019-01-01" required>
									@if ($errors->has('dofa'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('dofa') }}</strong>
										</span>
									@endif
									<label for="dofa">Date of 1st Appt.</label>
								</div>
								{{-- Date of Conf. --}}
								<div class="input-field col s12 l3">
									<input id="doc" name="doc" type="date" value="{{ old('doc') }}">
									@if ($errors->has('doc'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('doc') }}</strong>
										</span>
									@endif
									<label for="doc">Date of Conf.</label>
								</div>
								{{-- Date of Present Appt. --}}
								<div class="input-field col s12 l3">
									<input id="dopa" name="dopa" type="date" value="2019-01-01" required>
									@if ($errors->has('dopa'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('dopa') }}</strong>
										</span>
									@endif
									<label for="dopa">Date of Present Appt.</label>
								</div>
							</div>
							<div class="row">
								{{-- Cadre --}}
								<div class="col s12 l4">
									<label for="cadre">* Select Cadre</label>
									<select id="cadre" name="cadre" class="browser-default" required>
										<option disabled>Select Cadre</option>
										<option value="superintendent">Superintendent cadre</option>
										<option value="inspectorate" >Inspectorate cadre</option>
										<option value="assistant" selected>Assistant cadre</option>
									</select>
									@if ($errors->has('cadre'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('cadre') }}</strong>
										</span>
									@endif
								</div>
								{{-- GL --}}
								<div class="col s12 l3">
									<label for="gl">* Select GL</label>
									<select id="gl" name="gl" class="browser-default" required>
										<option value="" disabled selected>Select cadre first</option>
									</select>
									@if ($errors->has('gl'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('gl') }}</strong>
										</span>
									@endif
								</div>
								{{-- STEP --}}
								<div class="col s12 l2">
									<label for="step">* Select Step</label>
									<select id="step" name="step" class="browser-default" required>
										<option disabled>Select step</option>
										<option value="1">1</option>
										<option value="2" selected>2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
									</select>
									@if ($errors->has('step'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('step') }}</strong>
										</span>
									@endif
								</div>
								{{-- PAYPOINT --}}
								<div class="input-field col s12 l3">
									<input id="paypoint" name="paypoint" type="text" value="National Headquarters" id="autocomplete-input" class="autocomplete">
									@if ($errors->has('paypoint'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('paypoint') }}</strong>
										</span>
									@endif
									<label for="paypoint">Paypoint</label>
								</div>
							</div>
							<div class="row">
								{{-- SALARY STRUCTURE --}}
								<div class="col s12 l3">
									<label for="salary_structure">Salary structure</label>
									<select id="salary_structure" name="salary_structure" class="browser-default" required>
										<option disabled>Select a structure</option>
										<option value="consolidated">CONSOLIDATED</option>
										<option value="conpass" selected>CONPASS</option>
										<option value="conhess">CONHESS</option>
										<option value="conmess">CONMESS</option>
										<option value="conafss">CONAFSS</option>
										<option value="hapass">HAPASS</option>
										<option value="contiss ii">CONTISS II</option>
										<option value="conuass">CONUASS</option>
										<option value="conraiss">CONRAISS</option>
										<option value="conjuss">CONJUSS</option>
									</select>
									@if ($errors->has('salary_structure'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('salary_structure') }}</strong>
										</span>
									@endif
								</div>
								{{-- BANK --}}
								<div class="col s12 l3">
									<label for="bank">Select Bank</label>
									<select id="bank" name="bank" class="browser-default" >
										<option disabled selected>Select Bank</option>
										@foreach($banks as $bank)
											<option value="{{ strtolower($bank['name']) }}">{{  $bank['name'] }}</option>
										@endforeach
									</select>
									@if ($errors->has('bank'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('bank') }}</strong>
										</span>
									@endif
								</div>
								{{-- ACC NO. --}}
								<div class="input-field col s12 l3">
									<input id="account_number" name="account_number" type="number" value="{{ old('account_number') }}" class="input_text" data-length="11">
									@if ($errors->has('account_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('account_number') }}</strong>
										</span>
									@endif
									<label for="account_number">Account No.</label>
								</div>
								{{-- BVN NO --}}
								<div class="input-field col s12 l3">
									<input id="bvn" name="bvn" type="number" value="{{ old('bvn') }}" class="input_text" data-length="11">
									@if ($errors->has('bvn'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('bvn') }}</strong>
										</span>
									@endif
									<label for="bvn">BVN</label>
								</div>
							</div>
							<div class="row">
								{{-- IPPIS NO --}}
								<div class="input-field col s12 l3">
									<input id="ippis_number" name="ippis_number" type="number" value="{{ old('ippis_number') }}">
									@if ($errors->has('ippis_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('ippis_number') }}</strong>
										</span>
									@endif
									<label for="ippis_number">IPPIS No.</label>
								</div>
								{{-- NIN NO --}}
								<div class="input-field col s12 l3">
									<input id="nin_number" name="nin_number" type="number" value="{{ old('nin_number') }}">
									@if ($errors->has('nin_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('nin_number') }}</strong>
										</span>
									@endif
									<label for="nin_number">NIN No.</label>
								</div>
								{{-- NHIS NO --}}
								<div class="input-field col s12 l3">
									<input id="nhis_number" name="nhis_number" type="number" value="{{ old('nhis_number') }}">
									@if ($errors->has('nhis_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('nhis_number') }}</strong>
										</span>
									@endif
									<label for="nhis_number">NHIS No.</label>
								</div>
								{{-- NHF NO --}}
								<div class="input-field col s12 l3">
									<input id="nhf" name="nhf" type="text" value="{{ old('nhf') }}">
									@if ($errors->has('nhf'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('nhf') }}</strong>
										</span>
									@endif
									<label for="nhf">NHF No.</label>
								</div>
							</div>
							<div class="row">
								{{-- PFA--}}
								<div class="col s12 l3">
									<label for="pfa">Select PFA</label>
									<select id="pfa" name="pfa" class="browser-default">
										<option disabled selected>Select PFA</option>
										@foreach($pfas as $pfa)
											<option value="{{ strtolower($pfa['name']) }}">{{  $pfa['name'] }}</option>
										@endforeach
									</select>
									@if ($errors->has('pfa'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('pfa') }}</strong>
										</span>
									@endif
								</div>
								{{-- PEN NO--}}
								<div class="input-field col s12 l3">
									<input id="pen_number" name="pen_number" type="number" value="{{ old('pen_number') }}">
									@if ($errors->has('pen_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('pen_number') }}</strong>
										</span>
									@endif
									<label for="pen_number">PEN No.</label>
								</div>
								{{-- FORMATION --}}
								<div class="col s12 l3">
									<label for="command">* Present Formation</label>
									<select id="command" name="command" class="browser-default" required>
										<option disabled selected>Select State</option>
										@foreach($formations as $formation)
											<option value="{{ $formation->id }}">{{ $formation->formation }}</option>
										@endforeach
									</select>
									@if ($errors->has('command'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('command') }}</strong>
										</span>
									@endif
								</div>
								<div class="input-field col s12 l3 right">
									<button class="submit btn waves-effect waves-light right" type="submit">
										<i class="material-icons right">send</i>ADD RECORD
									</button>
								</div>
							</div>
							<br />
							<div class="progress" style="display:none;">
								<div class="indeterminate"></div>
							</div>
						</fieldset>
					</form>
					<div class="fixed-action-btn">
						<a href="{{ route('import_data') }}" title="Import record from excel doc." class="btn-floating btn-large waves-effect waves-light green">
							<i class="fas fa-file-excel"></i>
						</a>
					</div>
				</div>
            </div>
        </div>
        <div class="footer z-depth-1">
            <p>&copy; Nigeria Security & Civil Defence Corps</p>
        </div>
    </div>
@endsection

@push('scripts')
	<script>
		// var new_personnel = new Vue({
		// 	el: '#new',
		// 	data: {
		// 		isDisabled: false
		// 	}
		// })

		function loadGl() {
			let cadreSelected = $('#cadre').value;
			if(cadreSelected == 'superintendent'){
				$('#gl').html('<option value="" disabled selected>Choose your option</option>');
				$(`<option value="18">18</option>`).appendTo('#gl');
				$(`<option value="17">17</option>`).appendTo('#gl');
				$(`<option value="16">16</option>`).appendTo('#gl');
				$(`<option value="15">15</option>`).appendTo('#gl');
				$(`<option value="14">14</option>`).appendTo('#gl');
				$(`<option value="13">13</option>`).appendTo('#gl');
				$(`<option value="12">12</option>`).appendTo('#gl');
				$(`<option value="11">11</option>`).appendTo('#gl');
				$(`<option value="10">10</option>`).appendTo('#gl');
				$(`<option value="9">9</option>`).appendTo('#gl');
				$(`<option value="8">8</option>`).appendTo('#gl');
			}else if(cadreSelected == 'inspectorate'){
				$('#gl').html('<option value="" disabled selected>Choose your option</option>');
				$(`<option value="7">7</option>`).appendTo('#gl');
				$(`<option value="6">6</option>`).appendTo('#gl');
			}else{
				$('#gl').html('<option value="" disabled selected>Choose your option</option>');
				$(`<option value="7">7</option>`).appendTo('#gl');
				$(`<option value="6">6</option>`).appendTo('#gl');
				$(`<option value="5">5</option>`).appendTo('#gl');
				$(`<option value="4">4</option>`).appendTo('#gl');
				$(`<option value="3">3</option>`).appendTo('#gl');
			}
		}

		$(document).ready(function(){

			loadGl()
			
			$('input.input_text').characterCounter();
			$('input.autocomplete').autocomplete({
				data: {
					'National Headquarters' : null,
					'Abia State Command' : null,
					'Adamawa State Command' : null,
					'Akwa-ibom State Command' : null,
					'Anambra State Command' : null,
					'Bauchi State Command' : null,
					'Bayelsa State Command' : null,
					'Benue State Command' : null,
					'Borno State Command' : null,
					'Cross-river State Command' : null,
					'Delta State Command' : null,
					'Ebonyi State Command' : null,
					'Edo State Command' : null,
					'Ekiti State Command' : null,
					'Enugu State Command' : null,
					'FCT Command' : null,
					'Gombe State Command' : null,
					'Imo State Command' : null,
					'Jigawa State Command' : null,
					'Kaduna State Command' : null,
					'Kano State Command' : null,
					'Katsina State Command' : null,
					'Kebbi State Command' : null,
					'Kogi State Command' : null,
					'Kwara State Command' : null,
					'Lagos State Command' : null,
					'Nasarawa State Command' : null,
					'Niger State Command' : null,
					'Ogun State Command' : null,
					'Ondo State Command' : null,
					'Osun State Command' : null,
					'Oyo State Command' : null,
					'Plateau State Command' : null,
					'Rivers State Command' : null,
					'Sokoto State Command' : null,
					'Taraba State Command' : null,
					'Yobe State Command' : null,
					'Zamfara State Command' : null,
					'Zone A HQ, Lagos' : null,
					'Zone B HQ, Kaduna' : null,
					'Zone C HQ, Bauchi' : null,
					'Zone D HQ, Minna' : null,
					'Zone E HQ, Oweri' : null,
					'Zone F HQ, Abeokuta' : null,
					'Zone G HQ, Benin' : null,
					'Zone H HQ, Makurdi' : null,
					'College of Security Management, Abeokuta' : null,
					'College of Peace, Conflic Resolution &Desaster Management, Katsina' : null,
					'Civil Defence Academy, Sauka' : null
				},
			});

			// LOAD GL AFTER SELECTING CADRE
			$('#cadre').change(function() {
				let cadreSelected = $(this).val();
				if(cadreSelected == 'superintendent'){
					$('#gl').html('<option value="" disabled selected>Choose your option</option>');
					$(`<option value="18">18</option>`).appendTo('#gl');
					$(`<option value="17">17</option>`).appendTo('#gl');
					$(`<option value="16">16</option>`).appendTo('#gl');
					$(`<option value="15">15</option>`).appendTo('#gl');
					$(`<option value="14">14</option>`).appendTo('#gl');
					$(`<option value="13">13</option>`).appendTo('#gl');
					$(`<option value="12">12</option>`).appendTo('#gl');
					$(`<option value="11">11</option>`).appendTo('#gl');
					$(`<option value="10">10</option>`).appendTo('#gl');
					$(`<option value="9">9</option>`).appendTo('#gl');
					$(`<option value="8">8</option>`).appendTo('#gl');
				}else if(cadreSelected == 'inspectorate'){
					$('#gl').html('<option value="" disabled selected>Choose your option</option>');
					$(`<option value="7">7</option>`).appendTo('#gl');
					$(`<option value="6">6</option>`).appendTo('#gl');
				}else{
					$('#gl').html('<option value="" disabled selected>Choose your option</option>');
					$(`<option value="7">7</option>`).appendTo('#gl');
					$(`<option value="6">6</option>`).appendTo('#gl');
					$(`<option value="5">5</option>`).appendTo('#gl');
					$(`<option value="4">4</option>`).appendTo('#gl');
					$(`<option value="3">3</option>`).appendTo('#gl');
				}
			});

			// LOAD LGAs AFTER SELECTING STATE OF ORIGIN
			$('#soo').change(function() {
				let stateSelected = this.value;
				// GET ALL LOCAL GOVERNMENT AREAS IN NIGERIA
				axios.get(`{{ route('get_lgas','') }}/${stateSelected}`, )
					.then(function(response) {
						// console.log(response.data);
						let lgaArray = response.data;
						$('#lgoo').html('<option value="" disabled selected>Choose your option</option>');
						lgaArray.map(function(lga) {
							$(`<option value="${lga.id}">${lga.lg_name}</option>`).appendTo('#lgoo');
						});
					})
					.catch(function(error) {
						// handle error
						console.log(error.data);
					})
					.finally(function() {
						// always executed
					});
			});


			$('#importData').submit(function () {
				$('.importProgress').css('display', 'block');
				$('.importBtn').html('Importing...');
			});

			// $('.submit').click(function(){
			// 	$('.progress').fadeIn();
			// });

		});
	</script>
@endpush