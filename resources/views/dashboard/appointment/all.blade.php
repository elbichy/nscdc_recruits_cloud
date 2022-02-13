@extends('layouts.app', ['title' => 'Appointment Records'])

@section('content')
    <!-- Modal Structure for excel upload -->
    <div id="appointment_modal" class="modal importModal">
        <div class="modal-content">
            <h4>Upload Redeployment</h4>
            <p>Select an excel file containing redeployment table and submit</p>
            @if ($errors->import->count())
                <div class="errors row  red darken-1" style="padding: 4px; margin:4px 0px;" >
                    <p class="col s12 white-text">There is an error from excel on column labeled "{{$errors->import->first()}}". ensure there are no trailing white-space or upper-cases then try to import again.</p>
                </div>
            @endif
            <form style="margin-top: 15px; margin-bottom: 0px; padding: 10px;" action="{{ route('store_imported_appointment') }}" method="post" enctype="multipart/form-data" id="importData" class="row">
                @csrf
                <input type="file" name="import_file" class="left"/>
                <button class="importBtn btn waves-effect waves-light green darken-2 right">Import File</button>
            </form>
        </div>
        {{-- <div class="modal-footer">
            <a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
        </div> --}}
    </div>
    <!-- Modal Structure to edit promotion -->
    <div id="modal" class="modal appointmentEditModal" style="height: 454px; overflow:hidden!important;">
        <form action="{{ route('appointment_update') }}" method="POST" name="edit_form" id="appointment_edit_form">
            <div class="modal-content">
                @csrf
                <div class="formWrap">
                    <div id="form">
                        <div class="row">
                            {{-- ID --}}
                            <input id="id" name="id" type="hidden" value="{{old('id')}}">
                            {{-- TSA No --}}
                            <div class="input-field col s12 l2">
                                <input id="tsa" name="tsa" type="number" value="{{old('tsa')}}" placeholder="e.g 12" required readonly>
                                @if ($errors->has('tsa'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('tsa') }}</strong>
                                    </span>
                                @endif
                                <label for="tsa">TSA No.</label>
                            </div>
                            {{-- Num --}}
                            <div class="input-field col s12 l2">
                                <input id="num" name="num" type="number" value="{{old('num')}}" placeholder="e.g 1" required readonly>
                                @if ($errors->has('num'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('num') }}</strong>
                                    </span>
                                @endif
                                <label for="num">S/No.</label>
                            </div>
                            {{-- Appliation code --}}
                            <div class="input-field col s12 l4">
                                <input id="application_code" name="application_code" type="text" value="{{old('application_code')}}" placeholder="e.g NSCDC-2019-2728334" required>
                                @if ($errors->has('application_code'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('application_code') }}</strong>
                                    </span>
                                @endif
                                <label for="application_code">Appliation code</label>
                            </div>
                            {{-- Fullname --}}
                            <div class="input-field col s12 l4">
                                <input id="name" name="name" type="text" value="{{old('name')}}" placeholder="e.g Jane Doe" required>
                                @if ($errors->has('name'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <label for="name">Fullname</label>
                            </div>
                        </div>
                        <div class="row">
                            {{-- Email --}}
                            <div class="input-field col s12 l3">
                                <input id="email" name="email" type="text" value="{{old('email')}}" placeholder="e.g janedoe@gmail.com">
                                @if ($errors->has('email'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <label for="email">Email address</label>
                            </div>
                            {{-- Mobile No. --}}
                            <div class="input-field col s12 l3">
                                <input id="mobile_number" name="mobile_number" type="text" value="{{old('mobile_number')}}" placeholder=" e.g 08024564345">
                                @if ($errors->has('mobile_number'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('mobile_number') }}</strong>
                                    </span>
                                @endif
                                <label for="mobile_number">Mobile No.</label>
                            </div>
                            {{-- Gender --}}
                            <div class="col s12 l3">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender" class=" browser-default">
                                    <option disabled selected>Choose formation</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @if ($errors->has('gender'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                            {{-- Date --}}
                            <div class="input-field col s12 l3">
                                <input id="date" name="date" type="text" class="date_datepicker" value="{{old('date')}}" placeholder="yy-mm-dd">
                                @if ($errors->has('date'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif
                                <label for="date">Date</label>
                            </div>
                        </div>
                        <div class="row">
                            {{-- Position (Rank) --}}
                            <div class="col s12 l3">
                                <label for="position">Position (Rank)</label>
                                <select id="position" name="position" class=" browser-default" required>
                                    <option disabled>Choose rank</option>
                                    @foreach($ranks as $rank)
                                        @if(strpos($rank, 'General'))
                                        @continue
                                        @endif
                                        @if(strpos($rank, 'Commandant'))
                                            @continue
                                        @endif
                                        @if(strpos($rank, 'Superintendent'))
                                            @continue
                                        @endif
                                        @if(strpos($rank, 'Chief Inspector'))
                                            @continue
                                        @endif
                                        @if(strpos($rank, 'Principal'))
                                            @continue
                                        @endif
                                        @if(strpos($rank, 'Senior Inspector'))
                                            @continue
                                        @endif
                                            <option value="{{ $rank->full_title }}">{{ $rank->full_title }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('position'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('position') }}</strong>
                                    </span>
                                @endif
                            </div>
                            {{-- State of Origin --}}
                            <div class="input-field col s12 l3">
                                <input name="state" type="text" id="state" class="state" value="{{old('state')}}" placeholder="e.g Kano" required>
                                @if ($errors->has('state'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                                <label for="state">State of origin</label>
                            </div>
                            {{-- LGA --}}
                            <div class="input-field col s12 l3">
                                <input name="lga" type="text" id="lga" class="lga" value="{{old('lga')}}" placeholder="e.g Bichi">
                                @if ($errors->has('lga'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('lga') }}</strong>
                                    </span>
                                @endif
                                <label for="lga">LGA</label>
                            </div>
                            {{-- Day --}}
                            <div class="input-field col s12 l3">
                                <input name="day" type="text" id="day" class="day" value="{{old('day')}}" placeholder="e.g D1" required>
                                @if ($errors->has('day'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('day') }}</strong>
                                    </span>
                                @endif
                                <label for="day">LGA</label>
                            </div>
                            
                        </div>
                        <div class="row">
                            {{-- Time --}}
                            <div class="input-field col s12 l2">
                                <input id="time" name="time" type="text" class="timepicker" value="{{old('time')}}" placeholder="12:00 PM" >
                                @if ($errors->has('time'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('time') }}</strong>
                                    </span>
                                @endif
                                <label for="time">Time</label>
                            </div>
                            {{-- Amount --}}
                            <div class="input-field col s12 l3">
                                <input id="amount" name="amount" type="text" value="{{old('amount')}}" placeholder="e.g 682375.00" required>
                                @if ($errors->has('amount'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                                <label for="amount">Amount</label>
                            </div>
                            {{-- ID Number --}}
                            <div class="input-field col s12 l3">
                                <input id="id_number" name="id_number" type="text" value="{{old('id_number')}}" placeholder="e.g D1-5-3657678" required>
                                @if ($errors->has('id_number'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('id_number') }}</strong>
                                    </span>
                                @endif
                                <label for="id_number">ID Number</label>
                            </div>

                            <div class="col s12 l4 submitBtn right" style="display: flex; align-items: flex-end; justify-content: flex-end; height: 66px;">
                                <button class="submit btn waves-effect waves-light right" type="submit"><i class="material-icons right">send</i>UPDATE</button>
                                <button class="delete btn waves-effect waves-light right" type="submit" style="margin-left: 6px;" title="DELETE RECORD" onclick="deleteRecord(event)"><i class="material-icons right" style="margin-left: 0px;">delete</i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form action="{{ route('appointment_delete') }}" method="post" id="delete_form">
            @method('DELETE')
            @csrf
            <input type="hidden" name="delete_id" class="delete_id">
        </form>
    </div>
 
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">PERSONNEL APPOINTMENT - {{ $year }} LIST</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="topMenuWrap" style="display: flex; justify-content:space-between; margin-bottom: 20px;">
                        <div class="left">
                            <button id="upload_excel_modal" class="greenBtn btn btn-small green darken-2 left">
                                <i class="fas fa-file-excel right"></i></i> IMPORT FROM EXCELL
                            </button>
                        </div>
                        
                        <button id="enlistBtn" class="enlistBtn btn btn-small"><i class="fas fa-file-word right"></i> GENERATE APPOINTMENT LETTER</button>
                    </div>
                    <table class="table centered table-bordered striped highlight" id="users-table">
                        <thead>
                            <tr>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Gender</th>
                                <th>Day</th>
                                <th>SOO</th>
                                {{-- <th>Email</th> --}}
                                <th>Phone</th>
                                <th>Rank</th>
                                <th>ID NO.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Gender</th>
                                <th>Day</th>
                                <th>SOO</th>
                                {{-- <th>Email</th> --}}
                                <th>Phone</th>
                                <th>Rank</th>
                                <th>ID NO.</th>
                                <th></th>
                                {{-- <th></th> --}}
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
        // EDIT PROMOTION
        function editAppointment(e){
            e.preventDefault();
            
            let id = e.currentTarget.dataset.pro_id;
            axios.get(`{!! route('appointment_edit', '') !!}/${id}`)
            .then(function (response) {
               if(response.status == 200){
                    $('#id').val(id);
                    $('#tsa').val(response.data.tsa);
                    $('#num').val(response.data.num);
                    $('#application_code').val(response.data.application_code);
                    $('#name').val(response.data.name);
                    $('#mobile_number').val(response.data.mobile_number);
                    $('#email').val(response.data.email);
                    $(`#gender option[value="${response.data.gender}"]`).prop("selected", "selected");
                    $('#state').val(response.data.state);
                    $('#lga').val(response.data.lga);
                    $('#day').val(response.data.day);
                    $('#amount').val(response.data.amount);
                    $('#id_number').val(response.data.id_number);
                    $('#update-dopa').val(response.data.date);
                    $(`#position option[value="${response.data.position}"]`).prop("selected", "selected");
                    $('#date').val(response.data.date);
                    $('#time').val(response.data.time);
                    $('.delete_id').val(response.data.id);
                    $('.appointmentEditModal').modal('open');
               }
            });
        }

        // DELETE PROMOTION
        function deleteRecord(e){
            e.preventDefault();
            if (confirm('Are you sure you want to delete this record?')) {
                $('.delete').prop('disabled', true);
                $('#delete_form').submit();
            }
        }

        $(function() {

            $('.dob_datepicker').datepicker({
				format: 'yyyy-mm-dd',
                container: 'body',
				yearRange: [1930, 1997]
			});
			$('.date_datepicker').datepicker({
				format: 'yyyy-mm-dd',
                container: 'body'
			});
            $('.timepicker').timepicker({
                container: 'body',
                twelveHour: true,
            });
            $('.importModal').modal();
            $('.appointmentEditModal').modal();

            $(document).on('click', '#addCandidate', function() {
                // $(this).prop('disabled', true).html('Adding record...');
                $('.promotionModal').modal('open');
            });
            $(document).on('click', '#upload_excel_modal', function(){
                // alert('Yeah!');
                $('#appointment_modal').modal('open');
            });

            // GENERATE BULK LETTERS
            $(document).on('click', '#enlistBtn', function() {
                let id = [];
                if (confirm('Are you sure you want to generate letters for the selected personnel(s)?')) {
                    $('.personnelCheckbox:checked').each(function() {
                        id.push($(this).val())
                    });
                    if (id.length > 0) {
                        $('.enlistBtn').prop('disabled', true).html('PROCESSING...');
                        axios.post(`{!! route('generate_bulk_appointment_letter') !!}`, { candidates: id }, {responseType: 'blob'})
                            .then(function(response) {
                                if(response.status == 200){
                                    $('.enlistBtn').prop('disabled', false).html(`<i class="material-icons right">format_list_bulleted</i> GENERATE PROMOTION LETTERS`);
                                    const url = window.URL.createObjectURL(new Blob([response.data]));
                                    const link = document.createElement('a');
                                    link.href = url;
                                    link.setAttribute('download', 'appointment_letter.docx');
                                    document.body.appendChild(link);
                                    link.click();
                                    $('#users-table th input:checked'). prop("checked", false);
                                    $('#users-table').DataTable().ajax.reload();
                                }
                            });
                    } else {
                        alert('You must select at least one personnel!');
                    }
                }
            });

            $(document).on('change', '.selectAll', function() {
                if (this.checked) {
                    $('.personnelCheckbox').attr('checked', true);
                } else {
                    $('.personnelCheckbox').attr('checked', false);
                }
            });

            $('#users-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                "lengthMenu": [[10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, -1], [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, "All"]],
                processing: true,
                serverSide: true,
                order: [[ 4, "asc" ]],
                ajax:  `{!! route('appointment_get_list', $year) !!}`,
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
                    { data: 'gender', name: 'gender'},
                    { data: 'day', name: 'day' },
                    { data: 'state', name: 'state' },
                    // { data: 'email', name: 'email' },
                    { data: 'mobile_number', name: 'mobile_number' },
                    { data: 'position', name: 'position'},
                    { data: 'id_number', name: 'id_number'},
                    { data: 'view', name: 'view', "orderable": false, "searchable": false}
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).attr('placeholder', 'Search');
                        $(input).appendTo($(column.footer()).empty()).on('keyup', function () {
                            var searchTerm = $(this).val();
                            regex = '\\b' + searchTerm.toLowerCase() + '\\b';
                            column.search(regex, true, false, true).draw();
                        });
                    });
                }
            });
            $('.dataTables_length > label > select').addClass('browser-default');
        });

    </script>

    @if ($errors->any())
    <script>
        $(function() {
            $('.appointmentEditModal').modal('open');
        });
    </script>
    @endif
    @if ($errors->import->count())
    <script>
        $(function() {
            $('.importModal').modal('open');
        });
    </script>
    @endif

@endpush