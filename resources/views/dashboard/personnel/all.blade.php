@extends('layouts.app', ['title' => 'All Redeployments Records'])

@section('content')
    <div class="my-content-wrapper">
        <!-- Modal Structure -->
        {{-- <div id="modal1" class="modal">
            <div class="modal-content">
                <h4>Synchronize records to cloud server</h4>
                <p class="row"></p>
                <span class="row"></span>
                <div class="progress">
                    <div class="determinate" style="width: 0%"></div>
                </div>
                <div class="row count" style="display: flex; justify-content: center"></div>
            </div>
            <div class="modal-footer">
                <a id="sync" class="waves-effect waves-light btn-small"><i class="material-icons left">cloud_upload</i>PUSH</a>
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
        </div> --}}
        <div class="content-container">
            <div class="sectionWrap">
                {{-- HEADING --}}
                <h6 class="center sectionHeading">PERSONNEL - All RECORDS</h6>

                {{-- TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="row topMenuWrap">
                        {{-- <a id="sync_cloud" class="waves-effect waves-light btn-small green darken-3" style="display: block"><i class="material-icons left">excel</i>GENERAL NR</a> --}}
                        @if(auth()->user()->hasRole('super admin'))
                        <a href="#" id="greenBtn" class="greenBtn btn btn-small green darken-2 white-text"><i class="fas fa-file-excel right"></i>GENERAL NR</a>
                        @endif
                    </div>
                    <table class="table centered table-bordered" id="users-table">
                        <thead>
                            <tr>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Svc No.</th>
                                <th>Sex</th>
                                <th>Marital Status</th>
                                <th>SOO</th>
                                <th>DOB</th>
                                {{-- <th>DOFA</th> --}}
                                {{-- <th>DOPA</th> --}}
                                <th>Rank</th>
                                <th>Formation</th>
                                <th>Updated</th>
                                <th>Passport</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Svc No.</th>
                                <th>Sex</th>
                                <th>Marital Status</th>
                                <th>SOO</th>
                                <th>DOB</th>
                                {{-- <th>DOFA</th> --}}
                                {{-- <th>DOPA</th> --}}
                                <th>Rank</th>
                                <th>Formation</th>
                                <th>Updated</th>
                                <th>Passport</th>
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
            
            $('.modal').modal();

            // $('#users-table').wrapAll(`<div style="; overflow-x: scroll;"></div>`);
            $('#users-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                "lengthMenu": [[50, 100, 150, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000, -1], [50, 100, 150, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000, "All"]],
                processing: true,
                serverSide: true,
                ajax:  `{!! route('personnel_get_all') !!}`,
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
                    { data: 'soo', name: 'soo'},
                    { data: 'marital_status', name: 'marital_status'},
                    // { data: 'dofa', name: 'dofa'},
                    // { data: 'dopa', name: 'dopa'},
                    { data: 'rank_short', name: 'rank_short'},
                    { data: 'current_formation', name: 'current_formation'},
                    { data: 'updated_at', name: 'updated_at'},
                    { data: 'passport', name: 'passport', "orderable": false, "searchable": false},
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