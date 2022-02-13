@extends('administration.layouts.app', ['title' => 'Edit Redeployments Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">GENERATE ELIGIBILITY LIST</h6>

                {{-- SALES TABLE --}}
                <div class="sectionFormWrap z-depth-1" style="padding:24px;">
                    <p class="formMsg blue lighten-5 left-align">
                        Click on the desired group to generate eligibility list for the next promotion examination
                    </p>
					<div class="eligibilityWrap">
						<div class="lastPromotion">
							<form action="{{ route('set_last_promotion') }}" name="set_last_promotion" method="POST" class="set_last_promotion">
								@csrf
								<span>
									{{-- Date of last promotion for 3-6 --}}
									<div class="input-field col s12 l3">
										<input id="dolp36" name="dolp36" type="text" class="dolp_datepicker" value="{{ $dolp36 }}" required>
										@if ($errors->has('dolp36'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('dolp36') }}</strong>
											</span>
										@endif
										<label for="dolp36">Date of last promotion for GL 3-6</label>
									</div>
								</span>
								<span>
									{{-- Date of last promotion for 7-12 --}}
									<div class="input-field col s12 l3">
										<input id="dolp712" name="dolp712" type="text" class="dolp_datepicker" value="{{$dolp712 }}" required>
										@if ($errors->has('dolp712'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('dolp712') }}</strong>
											</span>
										@endif
										<label for="dolp712">Date of last promotion for GL 7-12</label>
									</div>
								</span>
								<span>
									{{-- Date of last promotion for 13-16 --}}
									<div class="input-field col s12 l3">
										<input id="dolp1316" name="dolp1316" type="text" class="dolp_datepicker" value="{{ $dolp1316 }}" required>
										@if ($errors->has('dolp1316'))
											<span class="helper-text red-text">
												<strong>{{ $errors->first('dolp1316') }}</strong>
											</span>
										@endif
										<label for="dolp1316">Date of last promotion for GL 13-16</label>
									</div>
								</span>
							</form>
						</div>
						<div class="grouping">
							<a href="{{ route('generate_junior') }}" class="card generate_junior">
								<p>Grade Level</p>
								<h3>3-6</h3>
							</a>
							<a href="{{ route('generate_senior') }}" class="card generate_senior">
								<p>Grade Level</p>
								<h3>7-12</h3>
							</a>
							<a href="{{ route('generate_gojet') }}" class="card generate_gojet">
								<p>Grade Level</p>
								<h3>13-16</h3>
							</a>
						</div>
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
	@if(Session::has('jnr.eligibility'))
		<script>
			$(document).ready(function(){
				location.href = `/storage/eligibility/Junior_Eligibility_list.xlsx`;
			});
		</script>
	@endif
	@if(Session::has('snr.eligibility'))
		<script>
			$(document).ready(function(){
				location.href = `/storage/eligibility/Senior_Eligibility_list.xlsx`;
			});
		</script>
	@endif
	@if(Session::has('gjt.eligibility'))
		<script>
			$(document).ready(function(){
				location.href = `/storage/eligibility/Gojet_Officers_Eligibility_list.xlsx`;
			});
		</script>
	@endif
	<script>
		$(document).ready(function(){
			$('.dolp_datepicker').datepicker({
				format: 'yyyy-mm-dd',
				yearRange: [2004, (new Date).getFullYear()],
				onClose: function() {
					$('.set_last_promotion').submit();
				}
			});
			
			$('.generate_junior').click(function (e) { 
				$(this).html(`
				<div class="preloader-wrapper big active">
					<div class="spinner-layer spinner-blue">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
					<div class="spinner-layer spinner-red">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
					<div class="spinner-layer spinner-yellow">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
					<div class="spinner-layer spinner-green">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
				</div>
				<p>Generating list...</p>
				`);
			});
			$('.generate_senior').click(function (e) { 
				$(this).html(`
				<div class="preloader-wrapper big active">
					<div class="spinner-layer spinner-blue">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
					<div class="spinner-layer spinner-red">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
					<div class="spinner-layer spinner-yellow">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
					<div class="spinner-layer spinner-green">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
				</div>
				<p>Generating list...</p>
				`);
			});
			$('.generate_gojet').click(function (e) { 
				$(this).html(`
				<div class="preloader-wrapper big active">
					<div class="spinner-layer spinner-blue">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>

					<div class="spinner-layer spinner-red">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>

					<div class="spinner-layer spinner-yellow">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>

					<div class="spinner-layer spinner-green">
						<div class="circle-clipper left">
						<div class="circle"></div>
						</div><div class="gap-patch">
						<div class="circle"></div>
						</div><div class="circle-clipper right">
						<div class="circle"></div>
						</div>
					</div>
				</div>
				<p>Generating list...</p>
				`);
			});
		});
	</script>
@endpush