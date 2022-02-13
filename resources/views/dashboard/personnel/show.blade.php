@extends('layouts.app', ['title' => 'Staff Profile' ])

@section('content')

	<!-- DELETE PERSONNEL MODAL -->
	<div id="modal" class="modal deletePersonnelModal">
		<form action="{{ route('personnel_delete') }}" method="POST" name="create_form" id="delete_personnel_form">
			<div class="modal-content" style="padding: 24px 9px 0px;">
					@csrf
					<div class="formWrap">
						<fieldset id="form" class="row" style="margin-bottom: 0px; padding: 0px; border: none;">
							{{-- DELETE PERSONNEL --}}
							<input type="hidden" name="user" value="{{ $personnel->id }}">
							<div class="col s12 l12">
								<label for="reason">Select reason</label>
								<select id="reason" name="reason" class="browser-default" required>
									<option disabled selected>Select a reason</option>
									<option value="retirement">Retirement</option>
									<option value="resignation">Resignation</option>
									<option value="dismissal">Dismissal</option>
									<option value="deceased">Deceased</option>
								</select>
								@if ($errors->has('reason'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('reason') }}</strong>
									</span>
								@endif
							</div>
						</fieldset>
					</div>
			</div>
			<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
				<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
				<button class="submit_pass btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>DELETE PERSONNEL</button>
			</div>
		</form>
	</div>

	<!-- CHANGE PERSONNEL MODAL -->
	<div id="modal" class="modal changePassModal">
		<form action="{{ route('personnel_change_password', $personnel->id) }}" method="POST" name="create_form" id="change_pass_form">
			<div class="modal-content" style="padding: 24px 9px 0px;">
					@csrf
					<div class="formWrap">
						<fieldset id="form" class="row" style="margin-bottom: 0px; padding: 0px; border: none;">
							{{-- OLD PASSWORD --}}
							<div class="input-field col s12 l4">
								<input id="old_pass" name="old_pass" type="password" value="{{ old('old_pass') }}" class="fillable" placeholder="Old password">
								@if ($errors->has('old_pass'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('old_pass') }}</strong>
									</span>
								@endif
								<label for="old_pass">Old Password</label>
							</div>
							{{-- NEW PASSWORD --}}
							<div class="input-field col s12 l4">
								<input id="password" name="password" type="password" value="{{ old('password') }}" class="fillable" placeholder="New password" required>
								@if ($errors->has('password'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
								<label for="password">New Password</label>
							</div>
							{{-- CONFIRM NEW PASSWORD --}}
							<div class="input-field col s12 l4">
								<input id="password_confirmation" name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" class="fillable" placeholder="Confirm new password" required>
								@if ($errors->has('password_confirmation'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('password_confirmation') }}</strong>
									</span>
								@endif
								<label for="password_confirmation">Confirm Password</label>
							</div>
						</fieldset>
					</div>
			</div>
			<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
				<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
				<button class="submit_pass btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE PASSWORD</button>
			</div>
		</form>
	</div>

	<!-- CHANGE PERSONNEL PRIVILAGE MODAL -->
	<div id="modal" class="modal personnelPrivilageModal">
		<form action="{{ route('user_assign_privilage', $personnel->id) }}" method="POST" name="create_form" id="change_privilage_form">
			<div class="modal-content" style="padding: 24px 20px 0px;">
				<h4>Elevate Personnel</h4>
				@csrf
				<fieldset class="row">
					<legend>Available Roles</legend>
					<div class="col s12">
						@foreach ($roles as $role)
							@if (count($role->users))
								<p>
									<label>
										<input type="checkbox" name="roles[]" class="filled-in" value="{{ $role->name }}" checked="checked" />
										<span>{{ ucwords($role->name) }}</span>
									</label>
								</p>
							@else
								<p>
									<label>
										<input type="checkbox" name="roles[]" value="{{ $role->name }}" class="filled-in" />
										<span>{{ ucwords($role->name) }}</span>
									</label>
								</p>
							@endif
						
						@endforeach
						
					</div>
				</fieldset>
				
				<fieldset class="row">
					<legend>Available Permissions</legend>
					<div class="col s12">
						@foreach ($permissions as $permission)
							@if (count($permission->users))
								<p>
									<label>
										<input type="checkbox" name="permissions[]" class="filled-in" value="{{ $permission->name }}" checked="checked" />
										<span>{{ ucwords($permission->name) }}</span>
									</label>
								</p>
							@else
								<p>
									<label>
										<input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="filled-in" />
										<span>{{ ucwords($permission->name) }}</span>
									</label>
								</p>
							@endif
						
						@endforeach
					</div>
				</fieldset>
			</div>
			<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
				<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
				<button class="submit_privilage btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE PRIVILAGE</button>
			</div>
		</form>
	</div>

    <div class="my-content-wrapper">
        <div class="content-container white">
            <div class="sectionWrap z-depth-0">
                <div class="sectionProfileWrap z-depth-0" style="margin-top:18px; padding:0;">
					<h5>{{ $personnel->name }}'s Records</h5> 
					
					{{-- PROFILE INFO --}}
					<div class="profile">
						<div class="row infoWrap">
							{{-- BASIC INFORMATION --}}
							<div class="row">
								<div class="col s12 l6">
									<div class="detailWrap">
										<h6>Service name</h6>
										<p>{{ strtoupper($personnel->name) }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Date of Birth</h6>
										<p>{{ $personnel->dob }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Gender</h6>
										<p>{{ $personnel->sex != null ? strtoupper($personnel->sex) : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l2">
									<div class="detailWrap">
										<h6>Blood group</h6>
										<p>{{ $personnel->blood_group != null ? strtoupper($personnel->blood_group) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Genotype</h6>
										<p>{{ $personnel->genotype != null ? strtoupper($personnel->genotype) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l2">
									<div class="detailWrap">
										<h6>Height</h6>
										<p>{{ $personnel->height != null ? strtoupper($personnel->height) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l2">
									<div class="detailWrap">
										<h6>Weight</h6>
										<p>{{ $personnel->weight != null ? strtoupper($personnel->weight) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Marital status</h6>
										<p>{{ $personnel->marital_status != null ? strtoupper($personnel->marital_status) : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Name of Spouse</h6>
										<p>{{ $personnel->name_of_spouse != null ? strtoupper($personnel->name_of_spouse) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Date of Marriage</h6>
										<p>{{ $personnel->date_of_marriage != null ? $personnel->date_of_marriage : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l6">
									<div class="detailWrap">
										<h6>Residential address</h6>
										<p>{{ $personnel->residential_address != null ? strtoupper($personnel->residential_address) : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Place of Birth</h6>
										<p>{{ $personnel->place_of_birth != NULL ? strtoupper($personnel->place_of_birth) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l9">
									<div class="detailWrap">
										<h6>Permanent address</h6>
										<p>{{ $personnel->permanent_address != null ? strtoupper($personnel->permanent_address) : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>State of origin</h6>
										<p>{{ $state != NULL ? strtoupper($state->state_name) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>LGA</h6>
										<p>{{ $lga != NULL ? strtoupper($lga->lg_name) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l2">
									<div class="detailWrap">
										<h6>Phone</h6>
										<p>{{ $personnel->phone_number != null ? $personnel->phone_number : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l4">
									<div class="detailWrap">
										<h6>Email</h6>
										<p>{{ $personnel->email != null ? strtolower($personnel->email) : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Service no.</h6>
										<p>{{ $personnel->service_number }}</p>
									</div>
								</div>
								<div class="col s12 l6">
									<div class="detailWrap">
										<h6>Current Rank</h6>
										<p>{{ $personnel->rank_full != null ? $personnel->rank_full : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Grade level</h6>
										<p>{{ $personnel->gl != null ? $personnel->gl : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Step</h6>
										<p>{{ $personnel->step != null ? $personnel->step : 'N/A'  }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Date of 1st Appt.</h6>
										<p>{{ $personnel->dofa != null ? $personnel->dofa : 'N/A'  }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Date of Confirmation</h6>
										<p>{{ $personnel->doc != null ? $personnel->doc : 'N/A'  }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Date of Present Appt.</h6>
										<p>{{ $personnel->dopa != null ? $personnel->dopa : 'N/A'  }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Paypoint</h6>
										<p>{{ $personnel->paypoint != null ? strtoupper($personnel->paypoint) : 'N/A'  }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Salary structure</h6>
										<p>{{ $personnel->salary_structure != null ? strtoupper($personnel->salary_structure) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Bank</h6>
										<p>{{ $personnel->bank != null ? strtoupper($personnel->bank) : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Account No.</h6>
										<p>{{ $personnel->account_number != null ? $personnel->account_number : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>BVN</h6>
										<p>{{ $personnel->bvn != null ? $personnel->bvn : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>IPPS No.</h6>
										<p>{{ $personnel->ippis_number != null ? $personnel->ippis_number : 'N/A' }}</p>
									</div>
								</div>	
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>NIN No.</h6>
										<p>{{ $personnel->nin_number != null ? $personnel->nin_number : 'N/A' }}</p>
									</div>
								</div>	
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>NHIS No.</h6>
										<p>{{ $personnel->nhis_number != null ? $personnel->nhis_number : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">	
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>NHF No.</h6>
										<p>{{ $personnel->nhf != null ? $personnel->nhf : 'N/A' }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>PFA</h6>
										<p>{{ $personnel->pfa != null ? strtoupper($personnel->pfa) : 'N/A' }}</p>
									</div>
								</div>	
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>PEN no.</h6>
										<p>{{ $personnel->pen_number != null ? $personnel->pen_number : 'N/A' }}</p>
									</div>
								</div>	
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Specialization</h6>
										<p>{{ $personnel->specialization != null ? $personnel->specialization : 'N/A' }}</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="fieldset" id="countdowntimer">
									<legend><p>
										Count down till retirement
									</p></legend>
									<span id="label_timer"></span>
								</div>
							</div>
						</div>
						<div class="sideColumn">
							<div class="profilePic">
								@if ($personnel->passport == NULL)
									<img src="{{ asset('storage/avaterMale.jpg') }}" alt="Profile Pic" width="100%">
								@else
									<img src="{{ asset('storage/documents/'.$personnel->service_number.'/passport/'.$personnel->passport) }}" alt="Profile Pic" width="100%">
								@endif
							</div>
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<form action="{{ route('personnel_upload_file', $personnel->id) }}" method="POST" enctype="multipart/form-data" id="passport_upload">
								@csrf
									<div class="input-field col s12 l12" style="margin-bottom: 4px;">
										<input type="file" name="passport" id="passport" accept="image/*" style="width: 155px;">
										@if ($errors->has('passport'))
											<span class="helper-text red-text"style="width: 170px;">
												<strong>{{ $errors->first('passport') }}</strong>
											</span>
										@endif
									</div>
									<button class="upload_file waves-effect waves-light btn" type="submit"  style="margin-bottom: 14px;">UPLOAD PASSPORT</button>
							</form>
							@endif

					
						</div>
					</div>

					{{-- PERSONNEL NOK --}}
					<div class="fieldset">
						<legend><p>NEXT OF KIN RECORD</p></legend>
						<div class="table_form_wrapper">
							<!-- Modal Structure for cloud upload -->
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<div id="modal" class="modal nokModal">
								<form action="" method="POST" name="create_form" id="edit_nok_form">
									<div class="modal-content" style="padding: 24px 9px 0px;">
										@csrf
										<div class="formWrap">
											<fieldset id="form" class="row" style="margin-bottom: 0px; padding: 0px; border: none;">
												{{-- NAME --}}
												<div class="input-field col s12 l4">
													<input id="nok_name" name="nok_name" type="text" value="{{ old('nok_name') }}" class="fillable" placeholder="Name" required>
													@if ($errors->has('nok_name'))
														<span class="helper-text red-text">
															<strong>{{ $errors->first('nok_name') }}</strong>
														</span>
													@endif
													<label for="nok_name">Name</label>
												</div>
												{{-- RELATIONSHIP --}}
												<div class="col s12 l4">
													<label for="relationship">Select relationship</label>
													<select id="relationship" name="relationship" class="browser-default" required>
														<option disabled selected>Select relationship</option>
														<option value="father">Father</option>
														<option value="mother">Mother</option>
														<option value="uncle">Uncle</option>
														<option value="aunt">Aunt</option>
														<option value="brother">Brother</option>
														<option value="sister">Sister</option>
														<option value="cousin">Cousin</option>
														<option value="spouse">Spouse</option>
														<option value="son">Son</option>
														<option value="daughter">Daughter</option>
														<option value="nephew">Nephew</option>
														<option value="niece">Niece</option>
													</select>
													@if ($errors->has('relationship'))
														<span class="helper-text red-text">
															<strong>{{ $errors->first('relationship') }}</strong>
														</span>
													@endif
												</div>
												{{-- PHONE --}}
												<div class="input-field col s12 l4">
													<input id="nok_phone" name="nok_phone" type="number" value="{{ old('nok_phone') }}" class="fillable" placeholder="Phone" required>
													@if ($errors->has('nok_phone'))
														<span class="helper-text red-text">
															<strong>{{ $errors->first('nok_phone') }}</strong>
														</span>
													@endif
													<label for="nok_phone">Phone number</label>
												</div>
												{{-- ADDRESS --}}
												<div class="input-field col s12">
													<input id="nok_address" name="nok_address" type="text" value="{{ old('nok_address') }}" placeholder="Address" required>
													@if ($errors->has('nok_address'))
														<span class="helper-text red-text">
															<strong>{{ $errors->first('nok_address') }}</strong>
														</span>
													@endif
													<label class="active" for="nok_address">Address</label>
												</div>
											</fieldset>
										</div>
									</div>
									<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
										<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
										<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE RECORD</button>
									</div>
								</form>
							</div>
							@endif
							<table class="striped responsive-table highlight">
								<thead>
									<tr>
										<th>Name</th>
										<th>Relationship</th>
										<th>Address</th>
										<th>Phone</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@if($personnel->noks->count() > 0)
										@foreach($personnel->noks as $nok)
											<tr>
												<td>{{ strtoupper($nok->name) }}</td>
												<td>{{ strtoupper($nok->relationship) }}</td>
												<td>{{ strtoupper($nok->address) }}</td>
												<td>{{ $nok->phone }}</td>
												@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
												<td>
													<button data-nok_id="{{ $nok->id }}" data-nok_name="{{ $nok->name }}" data-nok_relationship="{{ $nok->relationship }}" data-nok_phone="{{ $nok->phone }}" data-nok_address="{{ $nok->address }}" class="edit_nok" class="red-text" title="Edit record" style="background: transparent; border: none; cursor: pointer; margin-right: 10px;">
														<i class="blue-text fas fa-edit"></i>
													</button>
													<a href="#" class="delete_nok" class="red-text" title="Delete record">
														<i class="red-text fas fa-trash-alt"></i>
													</a>
													{{-- DELETE NOK FORM --}}
													<form action="{{ route('personnel_delete_nok', $nok->id) }}" method="post" id="delete_nok">
														@method('delete')
														@csrf
													</form>
												</td>
												@endif
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="5">No data submitted</td>
										</tr>
									@endif
								</tbody>
								
							</table>
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<form action="{{ route('personnel_store_nok', $personnel->id) }}" method="POST" class="card add_record_form" id="nok_form">
								@csrf
								<div class="row">
									{{-- NAME --}}
									<div class="input-field col s12 l6">
										<input id="nok_name" name="nok_name" type="text" value="{{ old('nok_name') }}" class="fillable" required>
										@if ($errors->has('nok_name'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('nok_name') }}</strong>
											</span>
										@endif
										<label for="nok_name">Name</label>
									</div>
									{{-- RELATIONSHIP --}}
									<div class="col s12 l3">
										<label for="relationship">Select relationship</label>
										<select id="relationship" name="relationship" class="browser-default" required>
											<option disabled selected>Select relationship</option>
											<option value="father">Father</option>
											<option value="mother">Mother</option>
											<option value="uncle">Uncle</option>
											<option value="aunt">Aunt</option>
											<option value="brother">Brother</option>
											<option value="sister">Sister</option>
											<option value="cousin">Cousin</option>
											<option value="spouse">Spouse</option>
											<option value="son">Son</option>
											<option value="daughter">Daughter</option>
											<option value="nephew">Nephew</option>
											<option value="niece">Niece</option>
										</select>
										@if ($errors->has('relationship'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('relationship') }}</strong>
											</span>
										@endif
									</div>
									{{-- PHONE --}}
									<div class="input-field col s12 l3">
										<input id="nok_phone" name="nok_phone" type="number" value="{{ old('nok_phone') }}" class="fillable" required>
										@if ($errors->has('nok_phone'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('nok_phone') }}</strong>
											</span>
										@endif
										<label for="nok_phone">Phone number</label>
									</div>
									{{-- ADDRESS --}}
									<div class="input-field col s12 l9">
										<input id="address" name="address" type="text" value="{{ old('address') }}" class="fillable" required>
										@if ($errors->has('address'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('address') }}</strong>
											</span>
										@endif
										<label for="address">Address</label>
									</div>

									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="submit_nok btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>ADD RECORD
										</button>
									</div>
								</div>
							</form>
							@endif
						</div>
					</div>

					{{-- PERSONNEL PARTICULARS OF CHILDREN --}}
					<div class="fieldset">
						<legend><p>PARTICULARS OF CHILDREN</p></legend>
						<div class="table_form_wrapper">
							<!-- Modal Structure for cloud upload -->
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<div id="modal" class="modal childModal">
								<form action="" method="POST" name="create_form" id="edit_child_form">
									<div class="modal-content" style="padding: 24px 9px 0px;">
										@csrf
										<div class="formWrap">
											<fieldset id="form" class="row" style="margin-bottom: 0px; padding: 0px; border: none;">
												{{-- NAME --}}
												<div class="input-field col s12 l6">
													<input id="child_name" name="child_name" type="text" value="{{ old('child_name') }}" class="fillable" required>
													@if ($errors->has('nok_name'))
														<span class="helper-text red-text">
															<strong>{{ $errors->first('child_name') }}</strong>
														</span>
													@endif
													<label for="child_name">Name</label>
												</div>
												{{-- SEX --}}
												<div class="col s12 l3">
													<label for="child_sex">Select sex</label>
													<select id="child_sex" name="child_sex" class="browser-default" required>
														<option disabled selected>Select sex</option>
														<option value="male">Male</option>
														<option value="female">Female</option>
														<option value="other">Other</option>
													</select>
													@if ($errors->has('child_sex'))
														<span class="helper-text red-text">
															<strong>{{ $errors->first('child_sex') }}</strong>
														</span>
													@endif
												</div>
												{{-- DATE OF BIRTH --}}
												<div class="input-field col s12 l3">
													<input id="child_dob" name="child_dob" type="date" value="{{ old('child_dob') }}" class="fillable" required>
													@if ($errors->has('child_dob'))
														<span class="helper-text red-text">
															<strong>{{ $errors->first('child_dob') }}</strong>
														</span>
													@endif
													<label for="child_dob">Date of Birth</label>
												</div>
											</fieldset>
										</div>
									</div>
									<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
										<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
										<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE RECORD</button>
									</div>
								</form>
							</div>
							@endif
							<table class="striped responsive-table highlight">
								<thead>
									<tr>
										<th>Name</th>
										<th>Sex</th>
										<th>Date of Birth</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@if($personnel->children->count() > 0)
										@foreach($personnel->children as $child)
											<tr>
												<td>{{ strtoupper($child->name) }}</td>
												<td>{{ strtoupper($child->sex) }}</td>
												<td>{{ $child->dob }}</td>
												@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
												<td>
													<button data-child_id="{{ $child->id }}" data-child_name="{{ $child->name }}" data-child_sex="{{ $child->sex }}" data-child_dob="{{ $child->dob }}" class="edit_child" class="red-text" title="Edit record" style="background: transparent; border: none; cursor: pointer; margin-right: 10px;">
														<i class="blue-text fas fa-edit"></i>
													</button>
													<a href="#" class="delete_child" class="red-text" title="Delete record">
														<i class="red-text fas fa-trash-alt"></i>
													</a>
													{{-- DELETE NOK FORM --}}
													<form action="{{ route('personnel_delete_child', $child->id) }}" method="post" id="delete_child">
														@method('delete')
														@csrf
													</form>
												</td>
												@endif
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="4">No data submitted</td>
										</tr>
									@endif
								</tbody>
								
							</table>
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<form action="{{ route('personnel_store_child', $personnel->id) }}" method="POST" class="card add_record_form" id="child_form">
								@csrf
								<div class="row">
									{{-- NAME --}}
									<div class="input-field col s12 l6">
										<input id="child_name" name="child_name" type="text" value="{{ old('child_name') }}" class="fillable" required>
										@if ($errors->has('nok_name'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('child_name') }}</strong>
											</span>
										@endif
										<label for="child_name">Name</label>
									</div>
									{{-- SEX --}}
									<div class="col s12 l3">
										<label for="child_sex">Select sex</label>
										<select id="child_sex" name="child_sex" class="browser-default" required>
											<option disabled selected>Select sex</option>
											<option value="male">Male</option>
											<option value="female">Female</option>
											<option value="other">Other</option>
										</select>
										@if ($errors->has('child_sex'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('child_sex') }}</strong>
											</span>
										@endif
									</div>
									{{-- DATE OF BIRTH --}}
									<div class="input-field col s12 l3">
										<input id="child_dob" name="child_dob" type="date" value="{{ old('child_dob') }}" class="fillable" required>
										@if ($errors->has('child_dob'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('child_dob') }}</strong>
											</span>
										@endif
										<label for="child_dob">Date of Birth</label>
									</div>

									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="submit_child btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>ADD RECORD
										</button>
									</div>
								</div>
							</form>
							@endif
						</div>
					</div>

					{{-- PERSONNEL QUALIFICATION --}}
					<div class="fieldset">
						<legend><p>QUALIFICATION RECORD</p></legend>
						<div class="table_form_wrapper">
							<!-- Modal Structure for cloud upload -->
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<div id="modal" class="modal qualModal">
								<form action="" method="POST" name="create_form" id="edit_qual_form">
									<div class="modal-content" style="padding: 24px 9px 0px;">
											@csrf
											<div class="formWrap">
												<fieldset id="form" class="row" style="margin-bottom: 0px; padding: 0px; border: none;">
													{{-- QUALIFICATION --}}
													<div class="input-field col s12 l3">
														<input id="qualification" name="qualification" type="text" value="{{ old('qualification') }}" id="autocomplete-input" class="fillable autocomplete_qualifications" placeholder="Qualification" required>
														@if ($errors->has('qualification'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('qualification') }}</strong>
															</span>
														@endif
														<label for="qualification">Qualification</label>
													</div>
													{{-- COURSE --}}
													<div class="input-field col s12 l4">
														<input id="course" name="course" type="text" value="{{ old('course') }}" id="autocomplete-input" class="fillable autocomplete" placeholder="Inst." required>
														@if ($errors->has('course'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('course') }}</strong>
															</span>
														@endif
														<label for="course">Course/Discipline</label>
													</div>
													{{-- INSTITUTION --}}
													<div class="input-field col s12 l5">
														<input id="institution" name="institution" type="text" value="{{ old('institution') }}" id="autocomplete-input" class="fillable autocomplete" placeholder="Inst." required>
														@if ($errors->has('institution'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('institution') }}</strong>
															</span>
														@endif
														<label for="institution">School/Institution</label>
													</div>
													{{-- GRADE --}}
													<div class="input-field col s12 l4">
														<input id="grade" name="grade" type="text" value="{{ old('grade') }}" id="autocomplete-input" class="fillable autocomplete" placeholder="Inst." required>
														@if ($errors->has('grade'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('grade') }}</strong>
															</span>
														@endif
														<label for="grade">Grade</label>
													</div>
													{{-- YEAR COMMENCED --}}
													<div class="input-field col s12 l3">
														<input id="year_commenced" name="year_commenced" type="text" value="{{ old('year_commenced') }}" class="fillable" placeholder="Year Commenced" required>
														@if ($errors->has('year_commenced'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('year_commenced') }}</strong>
															</span>
														@endif
														<label for="year_commenced">Year Commenced</label>
													</div>
													
													{{-- YEAR OBTAINED --}}
													<div class="input-field col s12 l3">
														<input id="year_obtained" name="year_obtained" type="text" value="{{ old('year_obtained') }}" class="fillable" placeholder="Year Completed" required>
														@if ($errors->has('year_obtained'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('year_obtained') }}</strong>
															</span>
														@endif
														<label for="year_obtained">Year Completed</label>
													</div>
												</fieldset>
											</div>
									</div>
									<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
										<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
										<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE RECORD</button>
									</div>
								</form>
							</div>
							@endif
							<table class="striped responsive-table highlight">
								<thead>
									<tr>
										<th>Qualification</th>
										<th>Program</th>
										<th>Institution</th>
										<th>Grade</th>
										<th>From</th>
										<th>To</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@if($personnel->qualifications->count() > 0)
										@foreach($personnel->qualifications as $qualification)
											<tr>
												<td>{{ $qualification->qualification }}</td>
												<td>{{ $qualification->course }}</td>
												<td>{{ $qualification->institution }}</td>
												<td>{{ $qualification->grade }}</td>
												<td>{{ $qualification->year_commenced }}</td>
												<td>{{ $qualification->year_obtained }}</td>
												@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
												<td>
													<button data-qual_id="{{ $qualification->id }}" data-qual_qual="{{ $qualification->qualification }}" data-qual_inst="{{ $qualification->institution }}" data-qual_yr="{{ $qualification->year_obtained }}" class="edit_qualification" class="red-text" title="Edit record" style="background: transparent; border: none; cursor: pointer; margin-right: 10px;">
														<i class="blue-text fas fa-edit"></i>
													</button>
													<a href="" class="delete_qualification" class="red-text" title="Delete record">
														<i class="red-text fas  fa-trash-alt"></i>
													</a>
													{{-- DELETE QUALIFICATION FORM --}}
													<form action="{{ route('personnel_delete_qualification', $qualification->id) }}" method="post" id="delete_qualification">
														@method('delete')
														@csrf
													</form>
												</td>
												@endif
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="7">No data submitted</td>
										</tr>
									@endif
								</tbody>
								
							</table>
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<form action="{{ route('personnel_store_qualification', $personnel->id) }}" method="POST" class="card add_record_form" id="qualification_form">
								@csrf
								<div class="row">
									{{-- QUALIFICATION --}}
									<div class="input-field col s12 l3">
										<input name="qualification" type="text" value="{{ old('qualification') }}" id="autocomplete-input" class="fillable autocomplete_qualifications" placeholder="e.g B.Sc, NCE, SSCE, FSLC" required>
										@if ($errors->has('qualification'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('qualification') }}</strong>
											</span>
										@endif
										<label for="qualification">Qualification</label>
									</div>
									{{-- COURSE --}}
									<div class="input-field col s12 l4">
										<input id="course" name="course" type="text" value="{{ old('course') }}" id="autocomplete-input" class="fillable autocomplete" placeholder="e.g Public Administration">
										@if ($errors->has('course'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('course') }}</strong>
											</span>
										@endif
										<label for="course">Course/Discipline</label>
									</div>
									{{-- INSTITUTION --}}
									<div class="input-field col s12 l5">
										<input id="institution" name="institution" type="text" value="{{ old('institution') }}" id="autocomplete-input" class="fillable autocomplete" placeholder="Institution" required>
										@if ($errors->has('institution'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('institution') }}</strong>
											</span>
										@endif
										<label for="institution">School/Institution</label>
									</div>
									{{-- GRADE --}}
									<div class="input-field col s12 l3">
										<input id="grade" name="grade" type="text" value="{{ old('grade') }}" id="autocomplete-input" class="fillable autocomplete" placeholder="e.g Distinction, Credit, First Class, etc." >
										@if ($errors->has('grade'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('grade') }}</strong>
											</span>
										@endif
										<label for="grade">Grade</label>
									</div>
									{{-- YEAR COMMENCED --}}
									<div class="input-field col s12 l3">
										<input id="year_commenced" name="year_commenced" type="text" value="{{ old('year_commenced') }}" class="fillable" placeholder="Year Commenced" required>
										@if ($errors->has('year_commenced'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('year_commenced') }}</strong>
											</span>
										@endif
										<label for="year_commenced">Year Commenced</label>
									</div>
									
									{{-- YEAR OBTAINED --}}
									<div class="input-field col s12 l3">
										<input id="year_obtained" name="year_obtained" type="text" value="{{ old('year_obtained') }}" class="fillable" placeholder="Year Completed" required>
										@if ($errors->has('year_obtained'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('year_obtained') }}</strong>
											</span>
										@endif
										<label for="year_obtained">Year Completed</label>
									</div>

									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="submit_qualification btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>ADD RECORD
										</button>
									</div>
								</div>
							</form>
							@endif
						</div>
					</div>

					{{-- PERSONNEL DEPLOYMENT --}}
					<div class="fieldset">
						<legend><p>DEPLOYMENT RECORD</p></legend>
						<div class="table_form_wrapper">
							<!-- Modal Structure for cloud upload -->
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<div id="modal" class="modal deployModal">
								<form action="" method="POST" name="create_form" id="edit_deploy_form">
									<div class="modal-content" style="padding: 24px 9px 0px;">
											@csrf
											<div class="formWrap">
												<fieldset id="form" class="row" style="margin-bottom: 0px; padding: 0px; border: none;">
													{{-- COMMAND --}}
													<div class="col s12 l4">
														<label for="type">Select Formation</label>
														<select id="command" name="command" class="browser-default" required>
															<option disabled selected>Select Formation</option>
															@foreach($all_formations as $fmtn)
																@if($fmtn->type == 'state')
																	<option value="{{ $fmtn->id }}">{{ $fmtn->formation }} State Command</option>
																@elseif($fmtn->type == 'fct')
																	<option value="{{ $fmtn->id }}">{{ $fmtn->formation }}  Command</option>
																@elseif($fmtn->type == 'zone')
																	<option value="{{ $fmtn->id }}">{{ $fmtn->formation }} HQ</option>
																@else
																	<option value="{{ $fmtn->id }}">{{ $fmtn->formation }}</option>
																@endif
															@endforeach
														</select>
														@if ($errors->has('command'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('command') }}</strong>
															</span>
														@endif
													</div>
													{{-- DEPARTMENT --}}
													<div class="input-field col s12 l4">
														<input id="department" name="department" type="text" value="{{ old('department') }}" class="fillable" placeholder="Dept." required>
														@if ($errors->has('department'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('department') }}</strong>
															</span>
														@endif
														<label for="department">Department</label>
													</div>
													{{-- DESIGNATION --}}
													<div class="input-field col s12 l4">
														<input id="designation" name="designation" type="text" value="{{ old('designation') }}" class="fillable" placeholder="Designation" required>
														@if ($errors->has('designation'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('designation') }}</strong>
															</span>
														@endif
														<label for="designation">Designation</label>
													</div>
													{{-- FROM --}}
													<div class="input-field col s12 l3">
														<input id="from" name="from" type="date" class="fillable" value="{{ old('from') }}" placeholder="From" required>
														@if ($errors->has('from'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('from') }}</strong>
															</span>
														@endif
														<label for="from">From date</label>
													</div>
													{{-- TO --}}
													<div class="input-field col s12 l3">
														<input id="to" name="to" type="date" class="fillable" value="{{ old('to') }}" placeholder="To" required>
														@if ($errors->has('to'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('to') }}</strong>
															</span>
														@endif
														<label for="to">To date</label>
													</div>
												</fieldset>
											</div>
									</div>
									<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
										<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
										<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE RECORD</button>
									</div>
								</form>
							</div>
							@endif
							<table class="striped responsive-table highlight">
								<thead>
									<tr>
										<th>Command</th>
										<th>Designation</th>
										<th>Position</th>
										<th>From</th>
										<th>To</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@if($personnel->formations->count() > 0)
										@foreach($personnel->formations as $deployment)
											<tr>
												<td>{{ $deployment->pivot->command }}</td>
												<td>{{ $deployment->pivot->department }}</td>
												<td>{{ $deployment->pivot->designation }}</td>
												<td>{{ $deployment->pivot->from }}</td>
												<td>{{ $deployment->pivot->to }}</td>
												@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
												<td>
													<button data-deploy_id="{{ $deployment->pivot->id }}" data-deploy_cmnd="{{ $deployment->id }}" data-deploy_dept="{{ $deployment->pivot->department }}" data-deploy_desig="{{ $deployment->pivot->designation }}" data-deploy_from="{{ $deployment->pivot->from }}" data-deploy_to="{{ $deployment->pivot->to }}" class="edit_deployment" class="red-text" title="Edit record" style="background: transparent; border: none; cursor: pointer; margin-right: 10px;">
														<i class="blue-text fas fa-edit"></i>
													</button>
													<a href="#" class="delete_deployment" class="red-text" title="Delete record">
														<i class="red-text fas  fa-trash-alt"></i>
													</a>
													{{-- DELETE DEPLOYMENT FORM --}}
													<form action="{{ route('personnel_delete_deployment', $deployment->pivot->id) }}" method="post" id="delete_deployment">
														@method('delete')
														@csrf
													</form>
												</td>
												@endif
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="6">No data submitted</td>
										</tr>
									@endif
								</tbody>
								
							</table>
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<form action="{{ route('personnel_store_deployment', $personnel->id) }}" method="POST" class="card add_record_form" id="deployment_form">
								@csrf
								<div class="row">
									{{-- COMMAND --}}
									<div class="col s12 l4">
										<label for="type">Select Formation</label>
										<select id="command" name="command" class="browser-default" required>
											<option disabled selected>Select Formation</option>
											@foreach($all_formations as $fmtn)
												@if($fmtn->type == 'state')
													<option value="{{ $fmtn->id }}">{{ $fmtn->formation }} State Command</option>
												@elseif($fmtn->type == 'fct')
													<option value="{{ $fmtn->id }}">{{ $fmtn->formation }}  Command</option>
												@elseif($fmtn->type == 'zone')
													<option value="{{ $fmtn->id }}">{{ $fmtn->formation }} HQ</option>
												@else
													<option value="{{ $fmtn->id }}">{{ $fmtn->formation }}</option>
												@endif
											@endforeach
										</select>
										@if ($errors->has('command'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('command') }}</strong>
											</span>
										@endif
									</div>
									{{-- DEPARTMENT --}}
									<div class="input-field col s12 l4">
										<input id="department" name="department" type="text" value="{{ old('department') }}" class="fillable" required>
										@if ($errors->has('department'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('department') }}</strong>
											</span>
										@endif
										<label for="department">Department</label>
									</div>
									{{-- DESIGNATION --}}
									<div class="input-field col s12 l4">
										<input id="designation" name="designation" type="text" value="{{ old('designation') }}" class="fillable" required>
										@if ($errors->has('designation'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('designation') }}</strong>
											</span>
										@endif
										<label for="designation">Designation</label>
									</div>
									{{-- FROM --}}
									<div class="input-field col s12 l3">
										<input id="from" name="from" type="date" class="fillable" value="{{ old('from') }}" required>
										@if ($errors->has('from'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('from') }}</strong>
											</span>
										@endif
										<label for="from">From date</label>
									</div>
									{{-- TO --}}
									<div class="input-field col s12 l3">
										<input id="to" name="to" type="date" class="fillable" value="{{ old('to') }}" required>
										@if ($errors->has('to'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('to') }}</strong>
											</span>
										@endif
										<label for="to">To date</label>
									</div>

									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="submit_deployment btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>ADD RECORD
										</button>
									</div>
								</div>
							</form>
							@endif
						</div>
					</div>

					{{-- PERSONNEL PROGRESSION --}}
					<div class="fieldset">
						<legend><p>PROGRESSION RECORD</p></legend>
						<div class="table_form_wrapper">
							<!-- Modal Structure for cloud upload -->
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<div id="modal" class="modal progressModal">
								<form action="" method="POST" name="create_form" id="edit_progress_form">
									<div class="modal-content" style="padding: 24px 9px 0px;">
											@csrf
											<div class="formWrap">
												<fieldset id="form" class="row" style="margin-bottom: 0px; padding: 0px; border: none;">
													{{-- TYPE --}}
													<div class="col s12 l3">
														<label for="type">Select type</label>
														<select id="type" name="type" class="browser-default" required>
															<option disabled selected>Select type</option>
															<option value="Entry">Entry Rank</option>
															<option value="promotion">Promotion</option>
															<option value="special-promotion">Special Promotion</option>
															<option value="advancement">Advancement</option>
															<option value="upgrading-conversion">Upgrading/Conversion</option>
															<option value="lateral-conversion">Lateral/Conversion</option>
															<option value="Appointment">Appointmemt</option>
														</select>
														@if ($errors->has('type'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('type') }}</strong>
															</span>
														@endif
													</div>
													{{-- CADRE --}}
													<div class="col s12 l3">
														<label for="cadre">Select Cadre</label>
														<select id="cadre" name="cadre" class="browser-default" required>
															<option disabled selected>Select Cadre</option>
															<option value="superintendent">Superintendent cadre</option>
															<option value="inspectorate" >Inspectorate cadre</option>
															<option value="assistant" >Assistant cadre</option>
														</select>
														@if ($errors->has('cadre'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('cadre') }}</strong>
															</span>
														@endif
													</div>
													{{-- GL --}}
													<div class="col s12 l3">
														<label for="gl">Select new GL</label>
														<select id="gl" name="gl" class="browser-default" required>
															<option disabled selected>Select new grade level</option>
															<option value="18">18</option>
															<option value="17">17</option>
															<option value="16">16</option>
															<option value="15">15</option>
															<option value="14">14</option>
															<option value="13">13</option>
															<option value="12">12</option>
															<option value="11">11</option>
															<option value="10">10</option>
															<option value="9">9</option>
															<option value="8">8</option>
															<option value="7">7</option>
															<option value="6">6</option>
															<option value="5">5</option>
															<option value="4">4</option>
															<option value="3">3</option>
														</select>
														@if ($errors->has('gl'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('gl') }}</strong>
															</span>
														@endif
													</div>
													{{-- YEAR --}}
													<div class="input-field col s12 l3">
														<input id="effective_date" name="effective_date" type="date" class="fillable" value="{{ old('effective_date') }}" value="{{ old('effective_date') }}" placeholder="Effective date" required>
														@if ($errors->has('effective_date'))
															<span class="helper-text red-text">
																<strong>{{ $errors->first('effective_date') }}</strong>
															</span>
														@endif
														<label for="effective_date">Effective date</label>
													</div>
												</fieldset>
											</div>
									</div>
									<div class="modal-footer" style="padding: 0px 10px 0 0; width: 99%;">
										<a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
										<button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE RECORD</button>
									</div>
								</form>
							</div>
							@endif
							<table class="striped responsive-table highlight">
								<thead>
									<tr>
										<th>Type</th>
										<th>Cadre</th>
										<th>Grade level</th>
										<th>Rank (Full title)</th>
										<th>Rank (Acronym)</th>
										<th>Effective date</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@if($personnel->progressions->count() > 0)
										@foreach($personnel->progressions as $progression)
											<tr>
												<td>{{ strtoupper($progression->type) }}</td>
												<td>{{ strtoupper($progression->cadre) }}</td>
												<td>{{ $progression->gl }}</td>
												<td>{{ $progression->rank_full }}</td>
												<td>{{ $progression->rank_short }}</td>
												<td>{{ $progression->effective_date }}</td>
												@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
												<td>
													<button data-progress_id="{{ $progression->id }}" data-progress_type="{{ $progression->type }}" data-progress_cadre="{{ $progression->cadre }}" data-progress_gl="{{ $progression->gl }}" data-progress_effective_date="{{ $progression->effective_date }}" class="edit_progression" class="red-text" title="Edit record" style="background: transparent; border: none; cursor: pointer; margin-right: 10px;">
														<i class="blue-text fas fa-edit"></i>
													</button>
													<a href="#" class="delete_progression" class="red-text" title="Delete record">
														<i class="red-text fas  fa-trash-alt"></i>
													</a>
													{{-- DELETE DEPLOYMENT FORM --}}
													<form action="{{ route('personnel_delete_progression', $progression->id) }}" method="post" id="delete_progression">
														@method('delete')
														@csrf
													</form>
												</td>
												@endif
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="7">No data submitted</td>
										</tr>
									@endif
								</tbody>
								
							</table>
							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<form action="{{ route('personnel_store_progression', $personnel->id) }}" method="POST" class="card add_record_form" id="progression_form">
								@csrf
								<div class="row">
									{{-- TYPE --}}
									<div class="col s12 l3">
										<label for="type">Select type</label>
										<select id="type" name="type" class="browser-default" required>
											<option disabled selected>Select type</option>
											<option value="Entry">Entry Rank</option>
											<option value="promotion">Promotion</option>
											<option value="special-promotion">Special Promotion</option>
											<option value="advancement">Advancement</option>
											<option value="proper-placement">Proper Placement</option>
											<option value="upgrading-conversion">Upgrading/Conversion</option>
											<option value="lateral-conversion">Lateral/Conversion</option>
											<option value="Appointment">Appointment</option>
										</select>
										@if ($errors->has('type'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('type') }}</strong>
											</span>
										@endif
									</div>
									{{-- CADRE --}}
									<div class="col s12 l3">
										<label for="cadre">Select Cadre</label>
										<select id="cadre" name="cadre" class="browser-default" required>
											<option disabled selected>Select Cadre</option>
											<option value="superintendent">Superintendent cadre</option>
											<option value="inspectorate" >Inspectorate cadre</option>
											<option value="assistant" >Assistant cadre</option>
										</select>
										@if ($errors->has('cadre'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('cadre') }}</strong>
											</span>
										@endif
									</div>
									{{-- GL --}}
									<div class="col s12 l3">
										<label for="gl">Select new GL</label>
										<select id="gl" name="gl" class="browser-default" required>
											<option disabled selected>Select new grade level</option>
											<option value="18">18</option>
											<option value="17">17</option>
											<option value="16">16</option>
											<option value="15">15</option>
											<option value="14">14</option>
											<option value="13">13</option>
											<option value="12">12</option>
											<option value="11">11</option>
											<option value="10">10</option>
											<option value="9">9</option>
											<option value="8">8</option>
											<option value="7">7</option>
											<option value="6">6</option>
											<option value="5">5</option>
											<option value="4">4</option>
											<option value="3">3</option>
										</select>
										@if ($errors->has('gl'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('gl') }}</strong>
											</span>
										@endif
									</div>
									{{-- YEAR --}}
									<div class="input-field col s12 l3">
										<input id="effective_date" name="effective_date" type="date" class="fillable" value="{{ old('effective_date') }}" value="{{ old('effective_date') }}" required>
										@if ($errors->has('effective_date'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('effective_date') }}</strong>
											</span>
										@endif
										<label for="effective_date">Effective date</label>
									</div>

									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="submit_progression btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>ADD RECORD
										</button>
									</div>
								</div>
							</form>
							@endif
						</div>
					</div>

					{{-- PERSONNEL DOCUMENTS --}}
					<div class="fieldset">
						<legend><p>PERSONNEL CREDENTIALS</p></legend>
						<div class="docWrapper">
							@if(!$personnel->documents->isEmpty())
								@foreach($personnel->documents as $document)
									<ul>
										@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
										<a href="#" class="deleteDocument" id="delete"><i class="tiny material-icons">close</i></a>
										@endif
										{{-- DELETE DOCUMENT FORM --}}
										<form action="{{ route('deletePersonnelDocument', $document->id) }}" method="post" id="deletePersonnelDocument">
											@method('delete')
											@csrf
										</form>

										<li>
											{{-- LOCAL WAY --}}
											<a href="{{ asset('storage/documents/'.$personnel->service_number.'/'.$document->file) }}" data-lightbox="documents"  data-title="{{ strtoupper($document->title) }}">
												<img src="{{ asset('storage/documents/'.$personnel->service_number.'/'.$document->file) }}" width="80px">
											</a>
										</li>
										<li>{{ strtoupper($document->title) }}</li>
									</ul>
								@endforeach
							@else
								<tr>
									<td colspan="2" style="text-align:center;">No Documents Uploaded</td>
								</tr>
							@endif
						</div>
						@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
						<div class="table_form_wrapper">
							<form action="{{ route('personnel_upload_file', $personnel->id) }}" method="POST" class="card add_record_form" enctype="multipart/form-data" id="document_upload">
								@csrf
								<div class="row">
									<div class="col s12 l9 input-field">
										<input type="file" name="file[]" id="file" class="fillable" accept="image/*" style="border:none;" multiple>
									</div>
									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="document_upload btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>UPLOAD DOCUMENT
										</button>
									</div>
								</div>
							</form>
						</div>
						@endif
					</div>

					{{-- MENU COLLECTION --}}
					<div class="fixed-action-btn">
						<a class="btn-floating btn-large waves-effect waves-light blue darken-3">
							<i class="large material-icons">more_vert</i>
						</a>
						<ul>
							<li><a href="{{ route('personnel_ros', $personnel->id) }}" title="Print Record of Service" class="btn-floating green"><i style="font-size: 1.33333em;" class="fas fa-print fa-lg"></i></a></li>

							<li><a class="changePassword btn-floating purple" title="Change Password"><i style="font-size: 1.33333em;" class="fas fa-key fa-lg"></i></a></li>
							
							@role('super admin')
							<li><a class="personnelPrivilage btn-floating pink" title="Personnel Privilage">
								<i style="font-size: 1.33333em;" class="fas fa-user-cog fa-lg"></i>
							</a></li>
							@endrole

							@if(auth()->user()->can('edit personnel') || auth()->user()->hasRole('super admin'))
							<li><a href="{{ route('personnel_edit', $personnel->id) }}" title="Edit Records" class="btn-floating blue"><i style="font-size: 1.33333em;" class="fas fa-user-edit fa-lg"></i></a></li>
							@endif
							@if(auth()->user()->can('delete personnel') || auth()->user()->hasRole('super admin'))
							<li><a class="deletePersonnel btn-floating red" title="Delete Personnel"><i style="font-size: 1.33333em;" class="fas fa-user-minus fa-lg"></i></a></li>
							@endif
							
						</ul>
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
	@if ($errors->any())
    <script>
        $(function() {
            $('.changePassModal').modal('open');
        });
    </script>
	@endif
	
    <script>
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true,
			'fitImagesInViewport': true,
			'maxHeight': 800,
			'disableScrolling': false,
		});
        $(function() {

			M.updateTextFields()

			$("#label_timer").countdowntimer({
				dateAndTime : "{{ $ttr }}",
				labelsFormat : true,
				displayFormat : "YODHMS",
				borderColor : "#0e75a7",
				fontColor : "#FFFFFF",
				backgroundColor : "#164f6b",
				size : "xl",
			});

			$('.fixed-action-btn').floatingActionButton({
				direction: 'left'
			});
			
			$('.modal').modal({
				dismissible: true
			});

			

			// $('.dofa_datepicker').datepicker({
			// 	container: 'body',
			// 	format: 'yyyy-mm-dd',
			// 	yearRange: [2004, (new Date).getFullYear()]
			// });

			// COMMANDS
			$('input.autocomplete_commands').autocomplete({
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
					'Civil Defence Academy, Sauka' : null,
					'Education Liason Office Ibadan' : null
				},
			});
			
			// QUALIFICATIONS
			$('input.autocomplete_qualifications').autocomplete({
				data: {
					'FSLC' : null,
					'SSCE' : null,
					'WAEC' : null,
					'NECO' : null,
					'B.Sc' : null,
					'M.Sc' : null,
					'PGD' : null,
					'NCE' : null,
					'DIPLOMA' : null,
					'ND' : null,
					'HND' : null,
					'RN' : null,
					'RM' : null,
				},
			});

			// PASSPORT UPLOAD
			$('#passport_upload').submit(function(){
				$('.upload_file').html(`Uploading <i class="fas fa-circle-notch fa-spin"></i>`);
			});

			// DOCS UPLOAD
			$('#document_upload').submit(function(){
				$('.document_upload').html(`Uploading <i class="fas fa-circle-notch fa-spin"></i>`);
			});
			
			//DELETE PERSONNEL
			$(document).on('click', '.deletePersonnel', function(event) {
				event.preventDefault();
				$('.deletePersonnelModal').modal('open');
            });
			
			//EDIT PASSWORD 
			$(document).on('click', '.changePassword', function(event) {
				event.preventDefault();
				$('.changePassModal').modal('open');
            });
			$('#change_pass_form').submit(function(){
				$('.submit_pass').html(`Updating record...`);
			});

			
			//EDIT PRIVILAGE 
			$(document).on('click', '.personnelPrivilage', function(event) {
				event.preventDefault();
				$('.personnelPrivilageModal').modal('open');
            });
			$('#change_privilage_form').submit(function(){
				$('.submit_privilage').html(`Updating record...`);
			});



			//NEXT OF KIN UPDATE/DELETE 
			$(document).on('click', '.edit_nok', function(event) {
				event.preventDefault();
				
				let relationship = this.dataset.nok_relationship == '' ? 'promotion' : this.dataset.nok_relationship;
				$('#edit_nok_form').prop('action', `/dashboard/personnel/nok/${this.dataset.nok_id}/update`);
				$('#nok_name').prop('value', `${this.dataset.nok_name}`);
				$('#relationship option[value='+relationship+']').prop('selected', true);
				$('#nok_phone').prop('value', `${this.dataset.nok_phone}`);
				$('#nok_address').prop('value', `${this.dataset.nok_address}`);
				$('.nokModal').modal('open');
            });
			$('#nok_form').submit(function(){
				$('.submit_nok').html(`Adding record...`);
			});
			$('.delete_nok').click(function(event){
				event.preventDefault();
				if(confirm("Are you sure you want to delete this record?")){
					event.currentTarget.nextElementSibling.submit();
				}
			});

			//CHILD UPDATE/DELETE 
			$(document).on('click', '.edit_child', function(event) {
				event.preventDefault();
				
				let child_sex = this.dataset.child_sex == '' ? 'male' : this.dataset.child_sex;
				$('#edit_child_form').prop('action', `/dashboard/personnel/children/${this.dataset.child_id}/update`);
				$('#child_name').prop('value', `${this.dataset.child_name}`);
				$('#child_sex option[value='+child_sex+']').prop('selected', true);
				$('#child_dob').prop('value', `${this.dataset.child_dob}`);
				$('.childModal').modal('open');
            });
			$('#child_form').submit(function(){
				$('.submit_child').html(`Adding record...`);
			});
			$('.delete_child').click(function(event){
				event.preventDefault();
				if(confirm("Are you sure you want to delete this record?")){
					event.currentTarget.nextElementSibling.submit();
				}
			});


			// QUALIFICATION UPDATE/DELETE
			$(document).on('click', '.edit_qualification', function(event) {
				event.preventDefault();
                // $(this).prop('disabled', true).html('Adding record...');\
				// this.dataset.qual_id
				$('#edit_qual_form').prop('action', `/dashboard/personnel/qualification/${this.dataset.qual_id}/update`);
				$('#qualification').prop('value', `${this.dataset.qual_qual}`);
				$('#institution').prop('value', `${this.dataset.qual_inst}`);
				$('#year_obtained').prop('value', `${this.dataset.qual_yr}`);
                $('.qualModal').modal('open');
            });
			$('#qualification_form').submit(function(){
				$('.submit_qualification').html(`Adding record...`);
			});
			$('.delete_qualification').click(function(event){
				event.preventDefault();
				if(confirm("Are you sure you want to delete this record?")){
					event.currentTarget.nextElementSibling.submit();
				}
			});
			

			// DEPLOYMENT UPDATE/DELETE
			$(document).on('click', '.edit_deployment', function(event) {
				event.preventDefault();
                // $(this).prop('disabled', true).html('Adding record...');\
				// this.dataset.qual_id
				$('#edit_deploy_form').prop('action', `/dashboard/personnel/deployment/${this.dataset.deploy_id}/update`);

				let cmnd = this.dataset.deploy_cmnd == '' ? '1' : this.dataset.deploy_cmnd;
				// console.log(cmnd);
				$('.command').prop('value', `${this.dataset.deploy_cmnd}`);
				$('#command option[value='+cmnd+']').prop('selected', true);

				$('#department').prop('value', `${this.dataset.deploy_dept}`);
				$('#designation').prop('value', `${this.dataset.deploy_desig}`);
				$('#from').prop('value', `${this.dataset.deploy_from}`);
				$('#to').prop('value', `${this.dataset.deploy_to}`);
                $('.deployModal').modal('open');
            });
			$('#deployment_form').submit(function(){
				$('.submit_deployment').html(`Adding record...`);
			});
			$('.delete_deployment').click(function(event){
				event.preventDefault();
				if(confirm("Are you sure you want to delete this record?")){
					event.currentTarget.nextElementSibling.submit();
				}
			});
			

			// PROGRESSION UPDATE/DELETE
			$(document).on('click', '.edit_progression', function(event) {
				event.preventDefault();
                // $(this).prop('disabled', true).html('Adding record...');\
				// this.dataset.qual_id
				// console.log(this.dataset.progress_type);
				let type = this.dataset.progress_type == '' ? 'promotion' : this.dataset.progress_type;
				$('#edit_progress_form').prop('action', `/dashboard/personnel/progression/${this.dataset.progress_id}/update`);
				$('#type option[value='+type+']').prop('selected', true);
				$('#cadre option[value='+`${this.dataset.progress_cadre}`+']').prop('selected', true);
				$('#gl option[value='+`${this.dataset.progress_gl}`+']').prop('selected', true);
				$('#effective_date').prop('value', `${this.dataset.progress_effective_date}`);
                $('.progressModal').modal('open');
            });
			$('#progression_form').submit(function(){
				$('.submit_progression').html(`Adding record...`);
			});
			$('.delete_progression').click(function(event){
				event.preventDefault();
				if(confirm("Are you sure you want to delete this record?")){
					event.currentTarget.nextElementSibling.submit();
				}
			});



			$('.deleteDocument').click(function(event){
				event.preventDefault();
				if(confirm("Are you sure you want to delete document?")){
					event.currentTarget.nextElementSibling.submit();
				}
			});

			// LOAD LGAs AFTER SELECTING STATE OF ORIGIN
			$('#soo').change(function() {
				let stateSelected = $(this).val();
				// GET ALL LOCAL GOVERNMENT AREAS IN NIGERIA
				axios.get(`${base_url}/get-lgoo/${stateSelected}`)
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

			// $('.deletePersonnel').click(function(event){
			// 	event.preventDefault();
			// 	if(confirm("Are you sure you want to delete personnel?")){
			// 		$('#deletePersonnel').submit();
			// 	}
			// });
			
        });
    </script>
@endpush