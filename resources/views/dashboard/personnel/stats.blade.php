@extends('administration.layouts.app')

@section('content')
	<div class="my-content-wrapper">
		<div class="content-container white">
			<div class="sectionWrap">
				{{-- STATISTICS --}}
				<div id="card-stats">
					<div class="row mt-1" style="margin: 0;">
						<div class="col s12 m6 l3">
							<div class="card gradient-45deg-green-teal gradient-shadow min-height-100 white-text">
								<div class="padding-4">
									<div class="col s4 m4">
										<i class="fas fa-users fa-2x background-round mt-5"></i>
									</div>
									<div class="col s8 m8 right-align">
										<h5 class="mb-0">{{ $total_personnel }}</h5>
										<p class="no-margin">Total Personnel</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12 m6 l3">
							<div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text">
								<div class="padding-4">
									<div class="col s4 m4">
										<i class="fas fa-siren-on fa-2x background-round mt-5"></i>
									</div>
									<div class="col s8 m8 right-align">
										<h5 class="mb-0">{{ $total_formations }}</h5>
										<p class="no-margin">Total Formations</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12 m6 l3">
							<div class="card gradient-45deg-amber-amber gradient-shadow min-height-100 white-text">
								<div class="padding-4">
									<div class="col s4 m4">
										<i class="material-icons small background-round mt-5">arrow_downward</i>
									</div>
									<div class="col s8 m8 right-align">
										<h5 class="mb-0">{{ $internal }}</h5>
										<p class="no-margin">Internal Posting</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12 m6 l3">
							<div class="card gradient-45deg-red-pink gradient-shadow min-height-100 white-text">
								<div class="padding-4">
									<div class="col s4 m4">
										<i class="material-icons small background-round mt-5">arrow_upward</i>
									</div>
									<div class="col s8 m8 right-align">
										<h5 class="mb-0">{{ $external }}</h5>
										<p class="no-margin">External Posting</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="width: 100%; margin-bottom: 0px;">
					<div class="col s12 l6">
						<div class="card" style="width: 100%; height: 430px; padding:6px">
							<h5 class="center" style="font-size:14px; font-weight:bold; margin:0;">Distribution of personnel by Gender</h5>
							<div style="width: 100%;">
								{!! $genderChart->container() !!}
							</div>
						</div>
					</div>
					<div class="col s12 l6">
						<div class="card" style="width: 100%; height: 430px; padding:6px">
							<h5 class="center" style="font-size:14px; font-weight:bold; margin:0;">Distribution of personnel by Marital Status</h5>
							<div style="width: 100%;">
								{!! $maritalStatusChart->container() !!}
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="width: 100%; margin-bottom: 0px;">
					<div class="col s12">
						<div class="card" style="width: 100%; height: 430px; padding:6px">
							<h5 class="center" style="font-size:14px; font-weight:bold; margin:0;">Distribution of personnel by Grade Level</h5>
							<div style="width: 100%;">
								{!! $rankChart->container() !!}
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="width: 100%; margin-bottom: 0px;">
					<div class="col s12">
						<div class="card" style="width: 100%; height: 430px; padding:6px">
							<h5 class="center" style="font-size:14px; font-weight:bold; margin:0;">Distribution of personnel by Formation</h5>
							<div style="width: 100%;">
								{!! $formationChart->container() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer z-depth-1">
			<p>&copy; NSCDC ICT & Cybersecurity Department</p>
		</div>
	</div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/datatable/dataTables.checkboxes.min.js') }}"></script>
	<script src="{{ asset('js/Chart.min.js') }}"></script>
	{!! $genderChart->script() !!}
	{!! $maritalStatusChart->script() !!}
	{!! $rankChart->script() !!}
	{!! $formationChart->script() !!}
    <script>
        $(function() {
            
        });
    </script>

@endpush