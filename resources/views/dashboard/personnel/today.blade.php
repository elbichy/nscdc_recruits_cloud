@extends('administration.layouts.app', ['title' => 'Today\'s Redeployments Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">PERSONNEL REDEPLOYMENT - TODAY</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <table class="table centered table-bordered" id="users-table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Service No</th>
                                <th>Rank</th>
                                <th>From (Formation)</th>
                                <th>To (Formation)</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
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