@extends('administration.layouts.app', ['title' => 'All Redeployments Records'])

@section('content')
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">PERSONNEL - JUNIOR ELIGIBILITY (GL 3-6)</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="row topMenuWrap">
                        <a href="{{ route('export_junior') }}" id="jnrExportBtn" class="jnrExportBtn btn btn-small green darken-2 white-text"><i class="material-icons right">format_list_bulleted</i>Export list</a>
                    </div>
                    <table class="table centered table-bordered" id="users-table">
                        <thead>
                            <tr>
                                <th>Svc No.</th>
                                <th>Fullname</th>
                                <th>GL</th>
                                <th>SOO</th>
                                <th>LGA</th>
                                <th>Qual.</th>
                                <th>DOB</th>
                                <th>DOFA</th>
                                <th>DOPA</th>
                                <th>Phone No.</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personnel as $user)
                                @if($user->dopa <= \Carbon\Carbon::parse('1/1/2017') && $user->gl < 7)
                                <tr>
                                    <td>{{ $user->service_number }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->gl }}</td>
                                    <td>NULL</td>
                                    <td>NULL</td>
                                    <td>NULL</td>
                                    <td>{{ $user->dob }}</td>
                                    <td>{{ $user->dofa }}</td>
                                    <td>{{ $user->dopa }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>Eligible</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
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
    </script>

@endpush