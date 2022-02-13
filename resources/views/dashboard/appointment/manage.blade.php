@extends('layouts.app', ['title' => 'Appointment Category'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">CATEGORY</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="collection">
                        <a href="{{ route('manage_appointment_year', 2019) }}" class="collection-item">2019 Junior Staff Appointment</a>
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
        $(function() {
            $('.collapsible').collapsible();
            $('#users-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                "lengthMenu": [[50, 100, 150, 250, 300, -1], [50, 100, 150, 250, 300, "All"]],
                processing: true,
                serverSide: true,
                // ajax:  `{!! route('personnel_get_all') !!}`,
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
                    { data: 'current_formation', name: 'current_formation'}
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