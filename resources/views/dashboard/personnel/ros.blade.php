<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ config('app.name', 'NSCDC Admin Directorate Database') }}@isset($title) - {{ $title }}@endisset
    </title>
    <link rel="shortcut icon" href="{{ asset('storage/fav.png') }}">
    <style>
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body{
			width: 100%;
			height: 100%;
			background-color: #fff;
			padding: 40px 0 20px 0;
			font-size: 14px;
		}
		.sectionProfileWrap{
			width: 100%;
			margin: 0 auto;
			background-color: #fff;
		}
		.sectionProfileWrap .topSection{
			width: 100%;
			/* border: 1px solid #333; */
			padding: 20px;
			display: flex;
			position: relative;
		}
		.sectionProfileWrap .topSection .heading{
			width: 100%;
			text-align: center;
			display: block;
		}
		.sectionProfileWrap .topSection .heading p{
			font-size: 16px;
		}
		.sectionProfileWrap .topSection .heading .logo{
			width: 100px;
			margin: 0 auto;
		}
		.sectionProfileWrap .topSection .passport{
			height: 100px;
			position: absolute;
			top: 60px;
			right: 30px;
		}
		.sectionProfileWrap .infoWrap table{
			width: 90%;
			margin: 0 auto;
		}
		.sectionProfileWrap .infoWrap table tr{
			width: 100%;
		}
		.sectionProfileWrap .infoWrap table tr td{
			width: 50%;
			vertical-align: top;
		}
		.sectionProfileWrap .infoWrap table tr h4{
			margin: 0 0 6px 0!important;
			font-size: 18px;
		}
		.sectionProfileWrap .infoWrap table tr h4:last-child{
			margin: 10px auto 0 auto;
		}
		.sectionProfileWrap .fieldset{
			margin: 0 auto 20px auto;
			width: 90%;
			border:none;
			/* padding: 12px; */
		}
		.sectionProfileWrap .fieldset .legend{
			text-align: center;
			margin: 0 auto;
			display: block;
			width: 100%;
			text-align: center;
			font-size: 18px;
			padding-bottom: 6px;
			margin: 0 0 10px 0;
			border-bottom: 1px solid #333;
		}
		.sectionProfileWrap .fieldset table{
			/* border-collapse: collapse; */
			width: 100%;
			margin-top: 10px;
		}
		.sectionProfileWrap .fieldset table th, td {
			/* border: 1px solid #dddddd; */
			text-align: left;
			padding: 8px;
		}
		.sectionProfileWrap .fieldset table tr:nth-child(even) {
			background-color: #dddddd;
		}

    </style>

</head>
	<body>
		<div class="sectionProfileWrap">
			<div class="topSection">
				<div class="heading">
					<div class="logo">
						<img src="{{ asset('storage/nscdclargelogo.png') }}" alt="Profile Pic" width="100%">
					</div>
					<h2>NIGERIA SECURITY & CIVIL DEFENCE CORPS</h2>
					<h3>Directorate of Administration</h3>
					<p>Personnel Record of Service</p> 
					<p>{{ Carbon\Carbon::today()->format('l F, Y') }}</p> 
				</div>
				<div class="passport">
					@if ($personnel->passport == NULL)
						<img src="{{ asset('storage/avaterMale.jpg') }}" alt="Profile Pic" height="100%">
					@else
						<img src="{{ asset('storage/documents/'.$personnel->service_number.'/passport/'.$personnel->passport) }}" alt="Profile Pic" height="100%">
					@endif
				</div>
			</div>

			{{-- BASIC INFORMATION --}}
			<div class="infoWrap">
				<table>
					<tr>
						<td>
							<h4>Personal Information</h4>
							<p><b>Fullname:</b> {{ $personnel->name }}</p>
							<p><b>Date of Birth:</b> {{ $personnel->dob }}</p>
							<p><b>Gender:</b> {{ $personnel->sex != null ? $personnel->sex : 'N/A' }}</p>
							<p><b>Blood Group:</b> {{ $personnel->blood_group != null ? strtoupper($personnel->blood_group) : 'N/A' }}</p>
							<p><b>Marital status:</b> {{ $personnel->marital_status != null ? ucfirst($personnel->marital_status) : 'N/A' }}</p>
							
						</td>
						<td>
							<h4>Contact Information</h4>
							<p><b>Geopolitical Reion:</b> {{ $region !== NULL ? ucwords($region) : 'N/A' }}</p>
							<p><b>State of origin:</b> {{ $state !== NULL ? ucwords($state->state_name) : 'N/A' }}</p>
							<p><b>LGA:</b> {{ $lga !== NULL ? ucwords($lga->lg_name) : 'N/A' }}</p>
							<p><b>Residential address:</b> {{ $personnel->residential_address !== null ? ucwords($personnel->residential_address) : 'N/A' }}</p>
							<p><b>Phone:</b> {{ $personnel->phone_number != null ? $personnel->phone_number : 'N/A' }}</p>
							<p><b>Email:</b> {{ $personnel->email != null ? $personnel->email : 'N/A' }}</p>
							
						</td>
					</tr>
					<tr>
						<th colspan="2">
							<h4>Official Information</h4>
						</th>
					</tr>
					<tr>
						<td>
							<p><b>Service no:</b> {{ $personnel->service_number }}</p>
							<p><b>Current Rank:</b> {{ $personnel->rank_full != null ? $personnel->rank_full : 'N/A' }}</p>
							<p><b>Grade level:</b> {{ $personnel->gl != null ? $personnel->gl : 'N/A' }}</p>
							<p><b>Step:</b> {{ $personnel->step != null ? $personnel->step : 'N/A'  }}</p>
							<p><b>Date of 1st Appt:</b> {{ $personnel->dofa != null ? $personnel->dofa : 'N/A'  }}</p>
							<p><b>Date of Confirmation:</b> {{ $personnel->doc != null ? $personnel->doc : 'N/A'  }}</p>
							<p><b>Date of Present Appt:</b> {{ $personnel->dopa != null ? $personnel->dopa : 'N/A'  }}</p>
							<p><b>Paypoint:</b> {{ $personnel->paypoint != null ? $personnel->paypoint : 'N/A'  }}</p>
							<p><b>Specialization:</b> {{ $personnel->specialization != null ? $personnel->specialization : 'N/A' }}</p>
							<p><b>Date of Retirement:</b> {{ DateTime::createFromFormat('Y-m-d H:i:s', $ttr) ? date('d/m/Y', strtotime($ttr)) : 'N/A' }}</p>
						</td>
						<td>
							<p><b>Salary structure:</b> {{ $personnel->salary_structure != null ? $personnel->salary_structure : 'N/A' }}</p>
							<p><b>Bank:</b> {{ $personnel->bank != null ? $personnel->bank : 'N/A' }}</p>
							<p><b>Account No:</b> {{ $personnel->account_number != null ? $personnel->account_number : 'N/A' }}</p>
							<p><b>BVN:</b> {{ $personnel->bvn != null ? $personnel->bvn : 'N/A' }}</p>
							<p><b>IPPS No:</b> {{ $personnel->ippis_number != null ? $personnel->ippis_number : 'N/A' }}</p>
							<p><b>NIN No:</b> {{ $personnel->nin_number != null ? $personnel->nin_number : 'N/A' }}</p>
							<p><b>NHIS No:</b> {{ $personnel->nhis_number != null ? $personnel->nhis_number : 'N/A' }}</p>
							<p><b>NHF No:</b> {{ $personnel->nhf != null ? $personnel->nhf : 'N/A' }}</p>
							<p><b>PFA:</b> {{ $personnel->pfa != null ? $personnel->pfa : 'N/A' }}</p>
							<p><b>PEN no:</b> {{ $personnel->pen_number != null ? $personnel->pen_number : 'N/A' }}</p>
						</td>
					</tr>
				</table>
			</div>

			{{-- PERSONNEL NOK --}}
			<div class="fieldset">
				<div class="legend"><h4>Next of Kin Record</h4></div>
				<table class="striped responsive-table centered highlight">
					<thead>
						<tr>
							<th>Name</th>
							<th>Relationship</th>
							<th>Phone</th>
						</tr>
					</thead>
					<tbody>
						@if($personnel->noks->count() > 0)
							@foreach($personnel->noks as $nok)
								<tr>
									<td>{{ ucwords($nok->name) }}</td>
									<td>{{ ucwords($nok->relationship) }}</td>
									<td>{{ $nok->phone }}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="3">No data submitted</td>
							</tr>
						@endif
					</tbody>
					
				</table>
			</div>

			{{-- PERSONNEL QUALIFICATION --}}
			<div class="fieldset">
				<div class="legend"><h4>Qualification Record</h4></div>
				<table class="striped responsive-table centered highlight">
					<thead>
						<tr>
							<th>Qualification</th>
							<th>School/Institution</th>
							<th>Year Obtained</th>
						</tr>
					</thead>
					<tbody>
						@if($personnel->qualifications->count() > 0)
							@foreach($personnel->qualifications as $qualification)
								<tr>
									<td>{{ $qualification->qualification }}</td>
									<td>{{ $qualification->institution }}</td>
									<td>{{ $qualification->year_obtained }}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="4">No data submitted</td>
							</tr>
						@endif
					</tbody>
					
				</table>
			</div>

			{{-- PERSONNEL DEPLOYMENT --}}
			<div class="fieldset">
				<div class="legend"><h4>Deployment Record</h4></div>
				<table class="striped responsive-table centered highlight">
					<thead>
						<tr>
							<th>Command</th>
							<th>Dir/Dept/Unit/Div/Beat</th>
							<th>Position</th>
							<th>From</th>
							<th>To</th>
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
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="6">No data submitted</td>
							</tr>
						@endif
					</tbody>
					
				</table>
			</div>

			{{-- PERSONNEL PROGRESSION --}}
			<div class="fieldset">
				<div class="legend"><h4>Progression Record</h4></div>
				<table class="striped responsive-table centered highlight">
					<thead>
						<tr>
							<th>Type</th>
							<th>Cadre</th>
							<th>Grade level</th>
							<th>Rank (Full title)</th>
							<th>Rank (Acronym)</th>
							<th>Effective date</th>
						</tr>
					</thead>
					<tbody>
						@if($personnel->progressions->count() > 0)
							@foreach($personnel->progressions as $progression)
								<tr>
									<td>{{ ucfirst($progression->type) }}</td>
									<td>{{ ucfirst($progression->cadre) }}</td>
									<td>{{ $progression->gl }}</td>
									<td>{{ $progression->rank_full }}</td>
									<td>{{ $progression->rank_short }}</td>
									<td>{{ $progression->effective_date }}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="7">No data submitted</td>
							</tr>
						@endif
					</tbody>
					
				</table>
			</div>

		</div>
	</body>
</html>
