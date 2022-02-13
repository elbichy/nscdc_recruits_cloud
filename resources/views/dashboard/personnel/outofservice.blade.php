@extends('administration.layouts.app', ['title' => 'All Redeployments Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">PERSONNEL - OUT OF SERVICE</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="row topMenuWrap">
                        {{-- <a href="#" id="blueBtn" class="blueBtn btn btn-small"><i class="material-icons right">format_list_bulleted</i>eligibility list</a>
                        <a href="#" id="greenBtn" class="greenBtn btn btn-small green darken-2 white-text"><i class="material-icons right">format_list_bulleted</i>norminal roll</a> --}}
                    </div>
                    <table class="table centered table-bordered" id="users-table">
                        <thead>
                            <tr>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Svc No.</th>
                                <th>Sex</th>
                                <th>DOB</th>
                                <th>DOFA</th>
                                <th>DOPA</th>
                                <th>Last Rank</th>
                                <th>Last Formation</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Svc No.</th>
                                <th>Sex</th>
                                <th>DOB</th>
                                <th>DOFA</th>
                                <th>DOPA</th>
                                <th>Last Rank</th>
                                <th>Last Formation</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="footer z-depth-1">
            <p>&copy; Nigeria Security & Civil Defence Corps</p>
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
            // $('#users-table').wrapAll(`<div style="; overflow-x: scroll;"></div>`);
            $('#users-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                "lengthMenu": [[50, 100, 150, 250, 300, -1], [50, 100, 150, 250, 300, "All"]],
                processing: true,
                serverSide: true,
                ajax:  `{!! route('get_outofservice') !!}`,
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {
                        "data": "id",
                        "title": "SN",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }, "orderable": false, "searchable": false
                    },
                    { data: 'name', name: 'name' },
                    { data: 'service_number', name: 'service_number'},
                    { data: 'sex', name: 'sex'},
                    { data: 'dob', name: 'dob'},
                    { data: 'dofa', name: 'dofa'},
                    { data: 'dopa', name: 'dopa'},
                    { data: 'rank_short', name: 'rank_short'},
                    { data: 'current_formation', name: 'current_formation'},
                    { data: 'status', name: 'status'}
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).attr('placeholder', 'Search');
                        $(input).appendTo($(column.footer()).empty())
                        .on('keyup', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });
                }
            });
            $('.dataTables_length > label > select').addClass('browser-default');
        });
    </script>

@endpush