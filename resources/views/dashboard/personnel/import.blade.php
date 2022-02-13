@extends('layouts.app', ['title' => 'Add New Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
			<div class="sectionWrap">
				{{-- SECTION HEADING --}}
				<h6 class="center sectionHeading">IMPORT RECORDS</h6>
				<div class="sectionFormWrap z-depth-1" style="padding:24px;">
					<div class="importRecords">
						<div class="progress importProgress" style="margin:0; display: none;">
							<div class="indeterminate"></div>
						</div>
						<div class="card green darken-1"  style="margin-top:0;">
							<div class="card-content">
								<h5 class="card-title white-text">Import personnel records from excel</h5>
								<form style="margin-top: 15px;padding: 10px;" action="{{ route('store_imported_users') }}" method="post" enctype="multipart/form-data" id="importData" class="row">
									@csrf
									<input type="file" name="import_file" class="left"/>
									<button class="importBtn btn waves-effect waves-light green darken-2 right">Import File</button>
								</form>
							</div>
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
			$('.timepicker').timepicker({
				defaultTime: 'now'
			});

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
				$('.submit').prop('disabled', true).html('ADDING RECORD...');
				$('.progress').fadeIn();
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