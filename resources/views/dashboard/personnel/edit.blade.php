@extends('layouts.app', ['title' => 'Edit Personnel Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SECTION HEADING --}}
                <h6 class="center sectionHeading">EDIT PERSONNEL RECORDS</h6>
                {{-- SECTION TABLE --}}
                <div class="sectionFormWrap z-depth-1" style="padding:24px;">
					
					<p class="formMsg grey lighten-3 left-align">
						Fill the form below with the personnel information and proceed.
					</p>
					<form action="{{ route('personnel_update', $personnel->id) }}" method="POST" enctype="multipart/form-data" name="create_form" id="create_form">
						@method('PUT')
						@csrf
						<fieldset class="row">
							<legend>PERSONAL DATA</legend>
							<div class="row">
								{{-- Fullname --}}
								<div class="input-field col s12 l6">
									<input id="name" name="name" type="text" value="{{ $personnel->name }}" required>
									@if ($errors->has('name'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('name') }}</strong>
										</span>
									@endif
									<label for="name">* Fullname</label>
								</div>
								{{-- Date of Birth --}}
								<div class="input-field col s12 l3">
									<input id="dob" name="dob" type="date" value="{{ $personnel->dob }}" required>
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
										<option value="male" {{ $personnel->gender == 'male' ? 'selected' : '' }}>Male</option>
										<option value="female" {{ $personnel->gender == 'female' ? 'selected' : '' }}>Female</option>
										<option value="other" {{ $personnel->gender == 'other' ? 'selected' : '' }}>Other</option>
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
									<label for="marital_status">Select Status</label>
									<select id="marital_status" name="marital_status" class=" browser-default">
										<option disabled>Select Type</option>
										<option value="single" {{ $personnel->marital_status == 'single' ? 'selected' : '' }}>Single</option>
										<option value="married" {{ $personnel->marital_status == 'married' ? 'selected' : '' }}>Married</option>
										<option value="separated" {{ $personnel->marital_status == 'separated' ? 'selected' : '' }}>Separated</option>
										<option value="widowed" {{ $personnel->marital_status == 'widowed' ? 'selected' : '' }}>Widowed</option>
										<option value="divorced" {{ $personnel->marital_status == 'divorced' ? 'selected' : '' }}>Divorced</option>
									</select>
									@if ($errors->has('marital_status'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('marital_status') }}</strong>
										</span>
									@endif
								</div>
								{{-- Date of Marriage --}}
								<div class="input-field col s12 l4">
									<input id="date_of_marriage" name="date_of_marriage" type="date" value="{{ $personnel->date_of_marriage }}">
									@if ($errors->has('name'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('date_of_marriage') }}</strong>
										</span>
									@endif
									<label for="date_of_marriage">Date of marriage</label>
								</div>
								{{-- Name of spouse --}}
								<div class="input-field col s12 l4">
									<input id="name_of_spouse" name="name_of_spouse" type="text" value="{{ $personnel->name_of_spouse }}">
									@if ($errors->has('name'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('name_of_spouse') }}</strong>
										</span>
									@endif
									<label for="name_of_spouse">Name of spouse</label>
								</div>
							</div>
							<div class="row">
								{{-- Religion --}}
								<div class="col s12 l4">
									<label for="religion">Select religion</label>
									<select id="religion" name="religion" class=" browser-default">
										<option disabled>Select religion</option>
										<option value="christianity" {{ $personnel->religion == 'christianity' ? 'selected' : '' }}>Christianity</option>
										<option value="islam" {{ $personnel->religion == 'islam' ? 'selected' : '' }}>Islam</option>
										<option value="other" {{ $personnel->religion == 'other' ? 'selected' : '' }}>Other</option>
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
										<option disabled>Select Type</option>
										<option value="o+" {{ $personnel->blood_group == 'o+' ? 'selected' : '' }}>O+</option>
										<option value="o-"  {{ $personnel->blood_group == 'o-' ? 'selected' : '' }}>O-</option>
										<option value="a+"  {{ $personnel->blood_group == 'a+' ? 'selected' : '' }}>A+</option>
										<option value="a-"  {{ $personnel->blood_group == 'a-' ? 'selected' : '' }}>A-</option>
										<option value="b+"  {{ $personnel->blood_group == 'b+' ? 'selected' : '' }}>B+</option>
										<option value="b-"  {{ $personnel->blood_group == 'b-' ? 'selected' : '' }}>B-</option>
										<option value="ab+"  {{ $personnel->blood_group == 'ab+' ? 'selected' : '' }}>AB+</option>
										<option value="ab-"  {{ $personnel->blood_group == 'ab-' ? 'selected' : '' }}>AB-</option>
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
										<option value="aa" {{ $personnel->genotype == 'aa' ? 'selected' : '' }}>AA</option>
										<option value="as-" {{ $personnel->genotype == 'as-' ? 'selected' : '' }}>AS</option>
										<option value="ac" {{ $personnel->genotype == 'ac' ? 'selected' : '' }}>AC</option>
										<option value="ss" {{ $personnel->genotype == 'ss' ? 'selected' : '' }}>SS</option>
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
									<input id="height" name="height" type="text" value="{{ $personnel->height }}">
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
									<input id="weight" name="weight" type="text" value="{{ $personnel->weight }}">
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
										<option value="1" {{ $personnel->soo == 1 ? 'selected' : '' }}>Abia</option>
										<option value="2" {{ $personnel->soo == 2 ? 'selected' : '' }}>Adamawa</option>
										<option value="3" {{ $personnel->soo == 3 ? 'selected' : '' }}>Akwa-ibom</option>
										<option value="4" {{ $personnel->soo == 4 ? 'selected' : '' }}>Anambra</option>
										<option value="5" {{ $personnel->soo == 5 ? 'selected' : '' }}>Bauchi</option>
										<option value="6" {{ $personnel->soo == 6 ? 'selected' : '' }}>Bayelsa</option>
										<option value="7" {{ $personnel->soo == 7 ? 'selected' : '' }}>Benue</option>
										<option value="8" {{ $personnel->soo == 8 ? 'selected' : '' }}>Borno</option>
										<option value="9" {{ $personnel->soo == 9 ? 'selected' : '' }}>Cross-river</option>
										<option value="10" {{ $personnel->soo == 10 ? 'selected' : '' }}>Delta</option>
										<option value="11" {{ $personnel->soo == 11 ? 'selected' : '' }}>Ebonyi</option>
										<option value="12" {{ $personnel->soo == 12 ? 'selected' : '' }}>Edo</option>
										<option value="13" {{ $personnel->soo == 13 ? 'selected' : '' }}>Ekiti</option>
										<option value="14" {{ $personnel->soo == 14 ? 'selected' : '' }}>Enugu</option>
										<option value="15" {{ $personnel->soo == 15 ? 'selected' : '' }}>Fct</option>
										<option value="16" {{ $personnel->soo == 16 ? 'selected' : '' }}>Gombe</option>
										<option value="17" {{ $personnel->soo == 17 ? 'selected' : '' }}>Imo</option>
										<option value="18" {{ $personnel->soo == 18 ? 'selected' : '' }}>Jigawa</option>
										<option value="19" {{ $personnel->soo == 19 ? 'selected' : '' }}>Kaduna</option>
										<option value="20" {{ $personnel->soo == 20 ? 'selected' : '' }}>Kano</option>
										<option value="21" {{ $personnel->soo == 21 ? 'selected' : '' }}>Katsina</option>
										<option value="22" {{ $personnel->soo == 22 ? 'selected' : '' }}>Kebbi</option>
										<option value="23" {{ $personnel->soo == 23 ? 'selected' : '' }}>Kogi</option>
										<option value="24" {{ $personnel->soo == 24 ? 'selected' : '' }}>Kwara</option>
										<option value="25" {{ $personnel->soo == 25 ? 'selected' : '' }}>Lagos</option>
										<option value="26" {{ $personnel->soo == 26 ? 'selected' : '' }}>Nasarawa</option>
										<option value="27" {{ $personnel->soo == 27 ? 'selected' : '' }}>Niger</option>
										<option value="28" {{ $personnel->soo == 28 ? 'selected' : '' }}>Ogun</option>
										<option value="29" {{ $personnel->soo == 29 ? 'selected' : '' }}>Ondo</option>
										<option value="30" {{ $personnel->soo == 30 ? 'selected' : '' }}>Osun</option>
										<option value="31" {{ $personnel->soo == 31 ? 'selected' : '' }}>Oyo</option>
										<option value="32" {{ $personnel->soo == 32 ? 'selected' : '' }}>Plateau</option>
										<option value="33" {{ $personnel->soo == 33 ? 'selected' : '' }}>Rivers</option>
										<option value="34" {{ $personnel->soo == 34 ? 'selected' : '' }}>Sokoto</option>
										<option value="35" {{ $personnel->soo == 35 ? 'selected' : '' }}>Taraba</option>
										<option value="36" {{ $personnel->soo == 36 ? 'selected' : '' }}>Yobe</option>
										<option value="37" {{ $personnel->soo == 37 ? 'selected' : '' }}>Zamfara</option>
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
										<option disabled selected>Select State</option>
										<option value="{{ $personnel->lgoo }}" selected>{{ $lga }}</option>
									</select>
									@if ($errors->has('lgoo'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('lgoo') }}</strong>
										</span>
									@endif
								</div>
								{{-- Residential Address --}}
								<div class="input-field col s12 l6">
									<input id="residential_address" name="residential_address" type="text" value="{{ $personnel->residential_address }}" placeholder="Area, Town, State.">
									@if ($errors->has('residential_address'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('residential_address') }}</strong>
										</span>
									@endif
									<label for="residential_address">Present residential address</label>
								</div>
							</div>
							<div class="row">
								{{-- Permanent Address --}}
								<div class="input-field col s12 l6">
									<input id="permanent_address" name="permanent_address" type="text" value="{{ $personnel->permanent_address }}" placeholder="Area, Town, State.">
									@if ($errors->has('permanent_address'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('permanent_address') }}</strong>
										</span>
									@endif
									<label for="permanent_address">Permanent address</label>
								</div>
								{{-- Place of Birth --}}
								<div class="input-field col s12 l2">
									<input id="place_of_birth" name="place_of_birth" type="text" value="{{ $personnel->place_of_birth }}" placeholder="Area, Town or State.">
									@if ($errors->has('place_of_birth'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('place_of_birth') }}</strong>
										</span>
									@endif
									<label for="place_of_birth">Place of Birth</label>
								</div>
								{{-- Phone --}}
								<div class="input-field col s12 l2">
									<input id="phone_number" name="phone_number" type="number" value="{{ $personnel->phone_number }}" class="input_text" data-length="11">
									@if ($errors->has('phone_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('phone_number') }}</strong>
										</span>
									@endif
									<label for="phone_number">Phone no.</label>
								</div>
								{{-- Email --}}
								<div class="input-field col s12 l2">
									<input id="email" name="email" type="text" value="{{ $personnel->email }}">
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
								{{-- Cadre --}}
								<div class="col s12 l4">
									<label for="cadre">* Select Cadre</label>
									<select id="cadre" name="cadre" class="browser-default" required>
										<option disabled selected>Select Cadre</option>
										<option value="superintendent" {{ $personnel->cadre == 'superintendent' ? 'selected' : '' }}>Superintendent cadre</option>
										<option value="inspectorate" {{ $personnel->cadre == 'inspectorate' ? 'selected' : '' }}>Inspectorate cadre</option>
										<option value="assistant" {{ $personnel->cadre == 'assistant' ? 'selected' : '' }}>Assistant cadre</option>
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
										<option disabled selected>Select grade level</option>
										<option value="18" {{ $personnel->gl == 18 ? 'selected' : '' }}>18</option>
										<option value="17" {{ $personnel->gl == 17 ? 'selected' : '' }}>17</option>
										<option value="16" {{ $personnel->gl == 16 ? 'selected' : '' }}>16</option>
										<option value="15" {{ $personnel->gl == 15 ? 'selected' : '' }}>15</option>
										<option value="14" {{ $personnel->gl == 14 ? 'selected' : '' }}>14</option>
										<option value="13" {{ $personnel->gl == 13 ? 'selected' : '' }}>13</option>
										<option value="12" {{ $personnel->gl == 12 ? 'selected' : '' }}>12</option>
										<option value="11" {{ $personnel->gl == 11 ? 'selected' : '' }}>11</option>
										<option value="10" {{ $personnel->gl == 10 ? 'selected' : '' }}>10</option>
										<option value="9" {{ $personnel->gl == 9 ? 'selected' : '' }}>9</option>
										<option value="8" {{ $personnel->gl == 8 ? 'selected' : '' }}>8</option>
										<option value="7" {{ $personnel->gl == 7 ? 'selected' : '' }}>7</option>
										<option value="6" {{ $personnel->gl == 6 ? 'selected' : '' }}>6</option>
										<option value="5" {{ $personnel->gl == 5 ? 'selected' : '' }}>5</option>
										<option value="4" {{ $personnel->gl == 4 ? 'selected' : '' }}>4</option>
										<option value="3" {{ $personnel->gl == 3 ? 'selected' : '' }}>3</option>
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
										<option disabled selected>Select step</option>
										<option value="1" {{ $personnel->step == 1 ? 'selected' : '' }}>1</option>
										<option value="2" {{ $personnel->step == 2 ? 'selected' : '' }}>2</option>
										<option value="3" {{ $personnel->step == 3 ? 'selected' : '' }}>3</option>
										<option value="4" {{ $personnel->step == 4 ? 'selected' : '' }}>4</option>
										<option value="5" {{ $personnel->step == 5 ? 'selected' : '' }}>5</option>
										<option value="6" {{ $personnel->step == 6 ? 'selected' : '' }}>6</option>
										<option value="7" {{ $personnel->step == 7 ? 'selected' : '' }}>7</option>
										<option value="8" {{ $personnel->step == 8 ? 'selected' : '' }}>8</option>
										<option value="9" {{ $personnel->step == 9 ? 'selected' : '' }}>9</option>
										<option value="10" {{ $personnel->step == 10 ? 'selected' : '' }}>10</option>
									</select>
									@if ($errors->has('step'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('step') }}</strong>
										</span>
									@endif
								</div>
								{{-- Service Number --}}
								<div class="input-field col s12 l3">
									<input id="service_number" name="service_number" type="number" value="{{ $personnel->service_number }}" readonly disabled>
									@if ($errors->has('service_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('service_number') }}</strong>
										</span>
									@endif
									<label for="service_number">Svc No.</label>
								</div>
							</div>
							<div class="row">
								{{-- Date of 1st Appt. --}}
								<div class="input-field col s12 l3">
									<input id="dofa" name="dofa" type="date" value="{{ $personnel->dofa }}" required>
									@if ($errors->has('dofa'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('dofa') }}</strong>
										</span>
									@endif
									<label for="dofa">* Date of 1st Appt.</label>
								</div>
								{{-- Date of Conf. --}}
								<div class="input-field col s12 l3">
									<input id="doc" name="doc" type="date" value="{{ $personnel->doc != null ? $personnel->doc : 'dd/mm/yyyy' }}" >
									@if ($errors->has('doc'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('doc') }}</strong>
										</span>
									@endif
									<label for="doc">Date of Confirmation.</label>
								</div>
								{{-- Date of Present Appt. --}}
								<div class="input-field col s12 l3">
									<input id="dopa" name="dopa" type="date" value="{{ $personnel->dopa }}" required>
									@if ($errors->has('dopa'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('dopa') }}</strong>
										</span>
									@endif
									<label for="dopa">* Date of Present Appt.</label>
								</div>
								{{-- PAYPOINT --}}
								<div class="input-field col s12 l3">
									<input id="paypoint" name="paypoint" type="text" value="{{ $personnel->paypoint }}" id="autocomplete-input" class="autocomplete">
									@if ($errors->has('paypoint'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('paypoint') }}</strong>
										</span>
									@endif
									<label for="paypoint">Paypoint</label>
								</div>
							</div>
							<div class="row">
								{{-- BANK --}}
								<div class="col s12 l3">
									<label for="bank">Select Bank</label>
									<select id="bank" name="bank" class="browser-default">
										<option selected>Select Bank</option>
										@foreach($banks as $bank)
											<option value="{{ $bank['name'] }}" {{ $personnel->bank == $bank['name'] ? 'selected' : '' }}>{{  $bank['name'] }}</option>
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
									<input id="account_number" name="account_number" type="number" value="{{ $personnel->account_number }}" class="input_text" data-length="10">
									@if ($errors->has('account_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('account_number') }}</strong>
										</span>
									@endif
									<label for="account_number">Account Number</label>
								</div>
								{{-- BVN NO --}}
								<div class="input-field col s12 l3">
									<input id="bvn" name="bvn" type="number" value="{{ $personnel->bvn }}" class="input_text" data-length="11">
									@if ($errors->has('bvn'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('bvn') }}</strong>
										</span>
									@endif
									<label for="bvn">BVN</label>
								</div>
								{{-- IPPIS NO --}}
								<div class="input-field col s12 l3">
									<input id="ippis_number" name="ippis_number" type="number" value="{{ $personnel->ippis_number }}">
									@if ($errors->has('ippis_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('ippis_number') }}</strong>
										</span>
									@endif
									<label for="ippis_number">IPPIS No.</label>
								</div>
							</div>
							<div class="row">
								{{-- SALARY STRUCTURE --}}
								<div class="col s12 l3">
									<label for="salary_structure">Salary structure</label>
									<select id="salary_structure" name="salary_structure" class="browser-default">
										<option disabled selected>Select a structure</option>
										<option value="consolidated" {{ $personnel->salary_structure == 'consolidated' ? 'selected' : '' }}>CONSOLIDATED</option>
										<option value="conpass" {{ $personnel->salary_structure == 'conpass' ? 'selected' : '' }}>CONPASS</option>
										<option value="conhess" {{ $personnel->salary_structure == 'conhess' ? 'selected' : '' }}>CONHESS</option>
										<option value="conmess" {{ $personnel->salary_structure == 'conmess' ? 'selected' : '' }}>CONMESS</option>
										<option value="conafss" {{ $personnel->salary_structure == 'conafss' ? 'selected' : '' }}>CONAFSS</option>
										<option value="hapass" {{ $personnel->salary_structure == 'hapass' ? 'selected' : '' }}>HAPASS</option>
										<option value="contiss ii" {{ $personnel->salary_structure == 'contiss ii' ? 'selected' : '' }}>CONTISS II</option>
										<option value="conuass" {{ $personnel->salary_structure == 'conuass' ? 'selected' : '' }}>CONUASS</option>
										<option value="conraiss" {{ $personnel->salary_structure == 'conraiss' ? 'selected' : '' }}>CONRAISS</option>
										<option value="conjuss" {{ $personnel->salary_structure == 'conjuss' ? 'selected' : '' }}>CONJUSS</option>
									</select>
									@if ($errors->has('salary_structure'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('salary_structure') }}</strong>
										</span>
									@endif
								</div>
								{{-- NIN NO --}}
								<div class="input-field col s12 l3">
									<input id="nin_number" name="nin_number" type="number" value="{{ $personnel->nin_number }}">
									@if ($errors->has('nin_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('nin_number') }}</strong>
										</span>
									@endif
									<label for="nin_number">NIN No.</label>
								</div>
								{{-- NHIS NO --}}
								<div class="input-field col s12 l3">
									<input id="nhis_number" name="nhis_number" type="number" value="{{ $personnel->nhis_number }}">
									@if ($errors->has('nhis_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('nhis_number') }}</strong>
										</span>
									@endif
									<label for="nhis_number">NHIS No.</label>
								</div>
								{{-- NHF NO --}}
								<div class="input-field col s12 l3">
									<input id="nhf" name="nhf" type="text" value="{{ $personnel->nhf }}">
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
								<div class="col s12 l6">
									<label for="pfa">Select PFA</label>
									<select id="pfa" name="pfa" class="browser-default" required>
										<option selected>Select PFA</option>
										@foreach($pfas as $pfa)
											<option value="{{ $pfa['name'] }}" {{ $personnel->pfa == $pfa['name'] ? 'selected' : '' }}>{{  $pfa['name'] }}</option>
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
									<input id="pen_number" name="pen_number" type="number" value="{{ $personnel->pen_number }}">
									@if ($errors->has('pen_number'))
										<span class="helper-text red-text">
											<strong>{{ $errors->first('pen_number') }}</strong>
										</span>
									@endif
									<label for="pen_number">PEN No.</label>
								</div>
								<div class="input-field col s12 l3 right">
									<button class="submit btn waves-effect waves-light right" type="submit">
										<i class="material-icons right">send</i>UPDATE RECORD
									</button>
								</div>
							</div>
							{{-- SPECIALIZATION--}}
							{{-- <div class="input-field col s12 l3">
								<input id="specialization" name="specialization" type="text" value="{{ $personnel->specialization }}">
								@if ($errors->has('specialization'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('specialization') }}</strong>
									</span>
								@endif
								<label for="specialization">Specialization</label>
							</div> --}}
							{{-- Command
							<div class="col s12 l3">
								<label for="command">* Present Formation</label>
								<select id="command" name="command" class="browser-default" required>
									<option disabled selected>Select State</option>
									@foreach($formations as $formation)
										<option value="{{ $formation->id }}" {{ $formation->formation == $personnel->current_formation ? 'selected' : ''}}>{{ $formation->formation }}</option>
									@endforeach
								</select>
								@if ($errors->has('command'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('command') }}</strong>
									</span>
								@endif
							</div> --}}
							
							<br />
							<div class="progress" style="display:none;">
								<div class="indeterminate"></div>
							</div>
						</fieldset>
					</form>
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
		$(document).ready(function(){
			$('.dob_datepicker').datepicker({
				format: 'yyyy-mm-dd',
				yearRange: [1930, 1997]
			});
			$('.dofa_datepicker').datepicker({
				format: 'yyyy-mm-dd',
				yearRange: [2004, 2015]
			});
			$('.dopa_datepicker').datepicker({
				format: 'yyyy-mm-dd',
				yearRange: [2010, 2018]
			});
			$('.doc_datepicker').datepicker({
				format: 'yyyy-mm-dd',
				yearRange: [2005, 2018]
			});
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

			$('.tabs').tabs();

			$('.contact-data').click(function(){
				$('.tabs').tabs('select', 'contact-data');
			});
			$('.official-data').click(function(){
				$('.tabs').tabs('select', 'official-data');
			});
			$('.docs-upload').click(function(){
				$('.tabs').tabs('select', 'docs-upload');
			});

			$('#create_form').submit(function (e) { 
				$('.submit').prop('disabled', true).html('UPDATING RECORD <i class="fas fa-circle-notch fa-spin"></i>');
			});

			// LOAD LGAs AFTER SELECTING STATE OF ORIGIN
			$('#soo').change(function() {
				let stateSelected = $(this).val();
				// GET ALL LOCAL GOVERNMENT AREAS IN NIGERIA
				axios.get(`/api/get-lgoo/${stateSelected}`)
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

			$('.submit').click(function(){
				$('.progress').fadeIn();
			});

		});
	</script>
@endpush