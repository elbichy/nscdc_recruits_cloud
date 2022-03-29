@extends('layouts.app', ['title' => 'Add New Records'])

@section('content')
	<!-- Modal Structure for cloud upload -->
    <div id="modal" class="modal newRedeploymentModal">
		<div class="modal-content">
			<h5 class="center" style="margin-bottom: 15px;">AUTO FILL OPTIONS</h5>
			<p class="center">Select the personnel you'd like to redeploy.</p>
			<div class="row card" style="margin: 10px 0; padding: 9px 8px 8px 0;">
				<div class="checkboxes col s12 l9">
				</div>
				<button class="btn green col s12 l3" onclick="populateForm()">SELECT</button>
			</div>
		</div>
    </div>
	<!-- Modal Structure for cloud upload -->
    <div id="modal" class="modal existingRedeploymentModal">
		<div class="modal-content">
			<h5 class="center" style="margin-bottom: 15px;">PREVIOUS DEPLOYMENT(S)</h5>
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>From</th>
						<th>To</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
    </div>
	<div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">NEW REDEPLOYMENT</h6>

                {{-- SALES TABLE --}}
                <div class="sectionFormWrap z-depth-1" style="padding:24px;">
                    <p class="formMsg blue lighten-5 left-align">
                        Fill the form below with the personnel information and submit.
                    </p>
					<form action="{{ route('redeployment_store') }}" method="POST" name="create_form" id="create_form">
						@csrf
						<div class="formWrap">
							<fieldset id="form" class="card">
								<div class="row" style="margin-bottom: 0px;">
									{{-- Type --}}
									<div class="col s12 l2">
										<label for="type">Select Type</label>
										<select id="type" name="type" class=" browser-default" required>
											<option disabled>Select Type</option>
											<option value="external" selected>External</option>
											<option value="internal">Internal</option>
										</select>
									</div>
									{{-- Service No --}}
									<div class="input-field col s12 l2">
										<input id="service_number" name="service_number" type="number" value="{{old('service_number')}}" onblur="checkExist(event)" required>
										@if ($errors->has('service_number'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('service_number') }}</strong>
											</span>
										@endif
										<label for="service_number">File/Svc No.</label>
									</div>
									{{-- Fullname --}}
									<div class="input-field col s12 l4">
										<input id="fullname" name="fullname" type="text" value="{{old('fullname')}}" placeholder="Full name" required>
										@if ($errors->has('fullname'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('fullname') }}</strong>
											</span>
										@endif
										<label for="fullname">Fullname</label>
									</div>
									{{-- Rank --}}
									<div class="col s12 l4">
										<label for="rank">Select Rank</label>
										<select id="rank" name="rank" class="browser-default" required>
											<option value="">Select Rank</option>
											<option value="CC">Commandant of Corps</option>
											<option value="DCC">Deputy Commandant of Corps</option>
											<option value="ACC">Assistant Commandant of Corps</option>
											<option value="CSC">Chief Superintendent of Corps</option>
											<option value="SC">Superintendent of Corps</option>
											<option value="DSC">Deputy Superintendent of Corps</option>
											<option value="ASC I">Assistant Superintendent of Corps I</option>
											<option value="ASC II">Assistant Superintendent of Corps II</option>
											<option value="CIC">Chief Inspector of Corps</option>
											<option value="DCIC">Deputy Chief Inspector of Corps</option>
											<option value="ACIC">Assistant Chief Inspector of Corps</option>
											<option value="PIC I">Principal Inspector of Corps I</option>
											<option value="PIC II">Principal Inspector of Corps II</option>
											<option value="SIC">Senior Inspector of Corps</option>
											<option value="IC I">Inspector of Corps I</option>
											<option value="IC II">Inspector of Corps II</option>
											<option value="IC">Inspector of Corps</option>
											<option value="AIC">Assistant Inspector of Corps</option>
											<option value="CCA">Chief Corps Assistant</option>
											<option value="SCA">Senior Corps Assistant</option>
											<option value="CA I">Corps Assistant I</option>
											<option value="CA II">Corps Assistant II</option>
											<option value="CA III">Corps Assistant III</option>
										</select>
										@if ($errors->has('rank'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('rank') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="row" style="margin-bottom: 0px;">
									{{-- From --}}
									<div class="input-field col s12 l3">
										<input name="from" type="text" id="from" class="autocomplete_formation" value="{{old('from')}}" placeholder="From (formation)" required>
										@if ($errors->has('from'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('from') }}</strong>
											</span>
										@endif
										<label for="from">From (Formation/Dir/Dept/Unit)</label>
									</div>
									{{-- To Command --}}
									<div class="input-field col s12 l3">
										<input name="to" type="text" id="to" class="autocomplete_formation" value="{{old('to')}}" placeholder="To (formation)" required>
										@if ($errors->has('to'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('to') }}</strong>
											</span>
										@endif
										<label for="to">To (Formation/Dir/Dept/Unit)</label>
									</div>
									{{-- Designation --}}
									<div class="input-field col s12 l3">
										<input name="designation" id="designation" class="autocomplete_designation" type="text" value="{{old('designation')}}"  placeholder="Designation">
										@if ($errors->has('designation'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('designation') }}</strong>
											</span>
										@endif
										<label for="designation">Designation</label>
									</div>
									{{-- Reason --}}
									<div class="input-field col s12 l3">
										<input name="reason" id="reason" type="text" value="{{old('reason')}}" placeholder="Reason">
										@if ($errors->has('reason'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('reason') }}</strong>
											</span>
										@endif
										<label for="reason">Ground/Reason</label>
									</div>
									{{-- In-Charge --}}
									<div class="col s12 l3">
										<label for="incharge">Select In-Charge</label>
										<select id="incharge" name="incharge" class=" browser-default" required>
											<option disabled>Select Signatory</option>
											<option value="Commandant General">Commandant General</option>
											<option value="Ag. Commandant General">Ag. Commandant General</option>
										</select>
										@if ($errors->has('incharge'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('incharge') }}</strong>
											</span>
										@endif
									</div>
									{{-- signatory --}}
									<div class="col s12 l3">
										<label for="signatory">Select Signatory</label>
										<select id="signatory" name="signatory" class=" browser-default" required>
											<option disabled>Select Signatory</option>
											<option value="dcg" selected>DCG</option>
											<option value="acg">ACG</option>
											<option value="cc">CC</option>
										</select>
										@if ($errors->has('signatory'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('signatory') }}</strong>
											</span>
										@endif
									</div>
									{{-- Financial Implication --}}
									<div class="col s12 l3">
										<label for="financial_implication">Attracts Financial Implication?</label>
										<select id="financial_implication" name="financial_implication" class=" browser-default" required>
											<option disabled>Select Option</option>
											<option value="0" selected>NO</option>
											<option value="1">YES</option>
										</select>
										@if ($errors->has('financial_implication'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('financial_implication') }}</strong>
											</span>
										@endif
									</div>
									{{-- Date --}}
									<div class="input-field col s12 l3">
										<input id="date" name="date" type="text" class="datepicker" value="{{old('date')}}" required>
										@if ($errors->has('date'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('date') }}</strong>
											</span>
										@endif
										<label for="date">Date</label>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="input-field col s12 l4">
							<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>ADD RECORD</button>
						</div>
						{{-- Add button --}}
						{{-- <div class="input-field col s12 l1">
							<a id="addMultiple" data-count="1" class="btn-floating btn-small waves-effect waves-light green left"><i class="material-icons">add</i></a>
						</div> --}}
					</form>
                </div>
            </div>
        </div>
        <div class="footer z-depth-1">
            <p>&copy; NSCDC ICT & Cybersecurity Department</p>
        </div>
    </div>
@endsection

@push('scripts')
	<script>
		$(document).ready(function(){

			var commands = {
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
				'Zone I HQ, Damaturu' : null,
				'Zone J HQ, Osun' : null,
				'Zone K HQ, Awka' : null,
				'Zone L HQ, Portharcourt' : null,
				'Zone M HQ, Sokoto' : null,
				'Zone N HQ, Kano' : null,
				'Zone O HQ, Abuja' : null,
				'College of Security Management, Abeokuta' : null,
				'College of Peace, Conflict Resolution and Disaster Management, Katsina' : null,
				'Civil Defence Academy, Sauka' : null,
				'Commandant General\'s office' : null,
				'Directorate of Administratiion' : null,
				'Directorate of Operations' : null,
				'Directorate of Critical National Asset and Infrastructure' : null,
				'Directorate of Intelligence and Investigation' : null,
				'Directorate of Technical Services' : null,
				'Directorate of Disaster and Crisis Management' : null,
				'Department of Private Guard Security' : null,
				'Department of Policy Research and Statistics' : null
			};

            $('.newRedeploymentModal').modal();
			$('.existingRedeploymentModal').modal();

			$('.datepicker').datepicker({
				defaultDate: new Date(),
				format: 'yyyy-mm-dd',
            	setDefaultDate: true
			});

			$('.timepicker').timepicker({
				defaultTime: 'now'
			});

			$('#create_form').submit(function (e) { 
				$('.submit').prop('disabled', true).html('ADDING RECORD...');
			});

			$('input.autocomplete_formation').autocomplete({
				data: commands
			});
			
			$('input.autocomplete_dept').autocomplete({
				data: departments
			});
		});

		function checkExist(e){
			let value = e.currentTarget.value;
			if (value > 0) {
				axios.get(`/administration/dashboard/redeployment/check/${value}`)
					.then(function(response) {
						if(response.data.status){
							$.each(response.data.personnel, function name(key, value) {
								$('.checkboxes').append(`
									<label>
										<input class="with-gap" name="prompt_result" type="radio" data-details_array="${[value.name, value.current_formation, value.rank_full]}" value="${key}"/>
										<span>${value.name}</span>
									</label>
								`);
							});
							$('.newRedeploymentModal').modal('open');

							$.each(response.data.records, function (key, deployment){
								$('.existingRedeploymentModal > .modal-content > table > tbody').append(`
									<tr>
										<td>${deployment.fullname}</td>
										<td>${deployment.from}</td>
										<td>${deployment.to}</td>
										<td>${deployment.created_at}</td>
									</tr>
								`);
							});
							let result = confirm(`${response.data.count} redeployment records exist for this user, would you like to view them?`);
							if(result){
								$('.existingRedeploymentModal').modal('open')
							}
							
						}else{
							$.wnoty({
								type: 'error',
								message: `${response.data.message}.`,
								autohideDelay: 5000
							});

							$.each(response.data.personnel, function name(key, value) {
								$('.checkboxes').append(`
									<label>
										<input class="with-gap" name="prompt_result" type="radio" data-details_array="${[value.name, value.current_formation, value.rank_full]}" value="${key}"/>
										<span>${value.name}</span>
									</label>
								`);
							});
							$('.newRedeploymentModal').modal('open');
						}
					});
                } else {
                    alert('You must type a valid service/file number!');
                }
		}

		function populateForm(e) {
			let selectedData = $("input[name='prompt_result']:checked")[0].dataset.details_array;
			let finalArray = selectedData.split(',');
			console.log(finalArray);
			$('#fullname').val(finalArray[0]);
			$('#from').val(finalArray[1]);
			$(`#rank option[value="${finalArray[2]}"]`).prop("selected", "selected");
			$('.newRedeploymentModal').modal('close');
			$('.checkboxes').html('');
		}
	</script>
@endpush