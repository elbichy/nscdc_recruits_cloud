@extends('administration.layouts.app', ['title' => 'Show Redeployment Record'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">{{ strtoupper($redeployment->fullname) }}'s REDEPLOYMENT RECORD</h6>
                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
					<div class="redeploymentTable">
						<table class="striped">
							<tbody>
								<tr>
									<td>Type:</td>
									<td>{{ucwords($redeployment->type)}} Redeployment </td>
								</tr>
								<tr>
									<td>Fullname:</td>
									<td>{{ucwords($redeployment->fullname)}}</td>
								</tr>
								<tr>
									<td>Service No:</td>
									<td>{{ucwords($redeployment->service_number)}}</td>
								</tr>
								<tr>
									<td>Rank:</td>
									<td>{{ucwords($redeployment->rank)}}</td>
								</tr>
								<tr>
									<td>Redeployment From:</td>
									<td>{{ucwords($redeployment->from)}}</td>
								</tr>
								<tr>
									<td>Redeployment To</td>
									<td>{{ucwords($redeployment->to.' '.$redeployment->designation)}}</td>
								</tr>
								<tr>
									<td>Signatory</td>
									<td>{{strtoupper($redeployment->signatory)}}</td>
								</tr>
								<tr>
									<td>Date Produced</td>
									<td>{{ucwords($redeployment->created_at)}}</td>
								</tr>
							</tbody>
						</table>
						<div class="rightPane">
							<div class="top">
								<img src="data:image/png;base64,{{ $redeployment->barcode }}" alt="barcode"/>
							</div>
							<div class="bottom">
                                @if(auth()->user()->service_number == 66818 || auth()->user()->service_number == 6974)
                                <a href="/administration/dashboard/redeployment/generate_letter/signed/{{ $redeployment->id }}" style="margin-bottom: 8px;" class="btn orange darken-4 btn-small darken-2 white-text">GENERATE SIGNED LETTER <i class="fas fa-file-word fa-lg right"></i></a>
                                @endif

                                <a href="/administration/dashboard/redeployment/generate_letter/{{ $redeployment->id }}" style="margin-bottom: 8px;" class="btn green btn-small darken-2 white-text">GENERATE LETTER <i class="fas fa-file-word fa-lg right"></i></a>
                                
                                <a href="/administration/dashboard/redeployment/edit/{{ $redeployment->id }}" class="btn btn-small darken-2 white-text"><i class="material-icons right">edit</i> EDIT RECORD</a>
                                
                        		<button class="delete btn waves-effect waves-light right" type="button" onclick="deleteRecord(event)"><i class="material-icons right">close</i>DELETE RECORD</button>
							</div>
							<form action="{{ route('redeployment_delete', $redeployment->id) }}" method="post" id="delete_form">
								@method('DELETE')
								@csrf
							</form>
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
    <script src="{{ asset('js/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatable/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/datatable/buttons.print.min.js') }}"></script>
    <script>
        $(function() {
            $('#users-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                "lengthMenu": [[4, 10, 25, 50, 100, -1], [4, 10, 25, 50, 100, "All"]],
                processing: true,
                serverSide: true,
                ajax:  `{!! route('redeployment_get_today') !!}`,
                columns: [
                    {
                        "data": "id",
                        "title": "SN",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }, "orderable": false, "searchable": false
                    },
                    { data: 'fullname', name: 'fullname' },
                    { data: 'service_number', name: 'service_number'},
                    { data: 'rank', name: 'rank'},
                    { data: 'from', name: 'from'},
                    { data: 'to', name: 'to'},
                    { data: 'view', name: 'view', "orderable": false, "searchable": false}
                ]
            });
            $('.dataTables_length > label > select').addClass('browser-default');
        });
    </script>

@endpush