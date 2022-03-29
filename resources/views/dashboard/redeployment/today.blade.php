@extends('layouts.app', ['title' => 'Today\'s Redeployments Records'])

@section('content')
    <!-- Modal Structure for cloud upload -->
    <div id="modal1" class="modal">
        <div class="modal-content">
        <h4></h4>
        <p></p>
        <div class="progress">
            <div class="determinate" style="width: 0%"></div>
        </div>
        <div class="percentage" style="width: 100%; text-align: center; font-size: 16px;">
            0%
        </div>
        </div>
        <div class="modal-footer">
            <a href="#!" id="modal-close" class="modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>
    <div class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SALES HEADING --}}
                <h6 class="center sectionHeading">PERSONNEL REDEPLOYMENT - TODAY</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="row topMenuWrap">
                        <button id="genBulkBtn" class="genBulkBtn btn  btn-small purple darken-2"><i class="fas fa-file-word right"></i> generate letters</button>
                        <button id="enlistBtn" class="enlistBtn btn btn-small"><i class="material-icons right">format_list_bulleted</i> generate signal</button>
                        <button id="moveToTrash" class="moveToTrash btn btn-small red darken-2 white-text"><i class="material-icons right">delete</i> trash</button>
                    </div>
                    <table class="table centered table-bordered" id="users-table">
                        <thead>
                            <tr>
                                <th><input type='checkbox' class='browser-default selectAll'></th>
                                <th>SN</th>
                                <th>Fullname</th>
                                <th>Service No</th>
                                <th>Rank</th>
                                <th>From (Formation)</th>
                                <th>To (Formation)</th>
                                <th style="width: 90px;"></th>
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
    <script type="text/javascript" src="{{ asset('js/datatable/dataTables.checkboxes.min.js') }}"></script>

    <script>

        $(document).ready(function(){
            $('.modal').modal();
        });


        $(document).on('change', '.selectAll', function() {
            if (this.checked) {
                $('.personnelCheckbox').attr('checked', true);
            } else {
                $('.personnelCheckbox').attr('checked', false);
            }
        });


        $(function() {
            $('#users-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                "lengthMenu": [[10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, -1], [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100, "All"]],
                processing: true,
                serverSide: true,
                ajax:  `{!! route('redeployment_get_today') !!}`,
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {
                        "data": "id",
                        "title": "SN",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }, "orderable": false, "searchable": false
                    },
                    { data: 'fullname', name: 'fullname' },
                    { data: 'service_number', name: 'service_number'},
                    { data: 'rank_acronym', name: 'rank'},
                    { data: 'from', name: 'from'},
                    { data: 'to', name: 'to'},
                    { data: 'view', name: 'view', "orderable": false, "searchable": false}
                ]
            });
            $('.dataTables_length > label > select').addClass('browser-default');
        });

        
        // GENERATE BULK REDEPLOYMENT LETTERS
        $(document).on('click', '#genBulkBtn', function() {
            let id = [];
            if (confirm('Are you sure you want to generate letters for the selected deployment(s)?')) {
                $('.personnelCheckbox:checked').each(function() {
                    id.push($(this).val())
                });
                if (id.length > 0) {
                    $('.genBulkBtn').prop('disabled', true).html('PROCESSING...');
                    axios.post(`{!! route('generate_bulk_redeployment_letter') !!}`, { redeployment_id: id }, {responseType: 'blob'})
                        .then(function(response) {
                            if(response.status == 200){
                                $('.genBulkBtn').prop('disabled', false).html(`<i class="fas fa-file-word right"></i> GENERATE LETTERS`);
                                const url = window.URL.createObjectURL(new Blob([response.data]));
                                const link = document.createElement('a');
                                link.href = url;
                                link.setAttribute('download', 'BULK REDEPLOYMENT LETTERS.docx');
                                document.body.appendChild(link);
                                link.click();
                                $('#users-table th input:checked'). prop("checked", false);
                                $('#users-table').DataTable().ajax.reload();
                            }
                        });
                } else {
                    alert('You must select at least one deployment record!');
                }
            }
        });


        // GENERATE LIST OF REDEPLOYMENTS
        $(document).on('click', '#enlistBtn', function() {
            let id = [];
            if (confirm('Are you sure you want to shortlist the selected personnel(s)?')) {
                $('.personnelCheckbox:checked').each(function() {
                    id.push($(this).val())
                });
                if (id.length > 0) {
                    $('.enlistBtn').prop('disabled', true).html('PROCESSING...');
                    axios.post(`{!! route('generate_signal') !!}`, { personnel: id }, {responseType: 'blob'})
                        .then(function(response) {
                            if(response.status == 200){
                                $('.enlistBtn').prop('disabled', false).html(`<i class="material-icons right">format_list_bulleted</i> generate signal`);
                                const url = window.URL.createObjectURL(new Blob([response.data]));
                                const link = document.createElement('a');
                                link.href = url;
                                link.setAttribute('download', 'redeployment_signal.docx');
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


        // DELETE BULK RECORDS
        $(document).on('click', '#moveToTrash', function() {
            let id = [];
            if (confirm('Are you sure you want to move the selected records to trash?')) {
                $('.personnelCheckbox:checked').each(function() {
                    id.push($(this).val())
                });
                if (id.length > 0) {
                    $('.moveToTrash').prop('disabled', true).html('MOVING TO TRASH...');
                    axios.post(`{!! route('redeployment_delete_bulk') !!}`, { personnel: id })
                        .then(function(response) {
                            if(response.data.status){
                                $.wnoty({
                                    type: 'success',
                                    message: `${response.data.message}.`,
                                    autohideDelay: 5000
                                });
                                $('.moveToTrash').prop('disabled', false).html(`<i class="material-icons right">delete</i> trash`);
                                $('#users-table th input:checked'). prop("checked", false);
                                $('#users-table').DataTable().ajax.reload();
                            }else{
                                $.wnoty({
                                    type: 'error',
                                    message: `${response.data.message}.`,
                                    autohideDelay: 5000
                                });
                                $('.moveToTrash').prop('disabled', false).html(`<i class="material-icons right">delete</i> trash`);
                                $('#users-table th input:checked'). prop("checked", false);
                                $('#users-table').DataTable().ajax.reload();
                            }
                        });
                } else {
                    alert('You must select at least one personnel!');
                }
            }
        });


        // CLOUD UPLOAD
        $(document).on('click', '#cloudUpload', function() {
            let id = [];
            let count = 0;
            if (confirm('Are you sure you want to sync all selected records?')) {
                $('.cloudUpload').prop('disabled', true).html('UPLOADING...');
                $('.personnelCheckbox:checked').each(function() {
                    id.push($(this).val())
                });
                if (id.length > 0) {
                    $('.modal > .modal-content > h4').html(`Synchronizing to remote database...`);
                    $('.modal > .modal-content > p').html(`0 out of ${id.length} records synchronized`);
                    $('.modal').modal('open');
                    
                    $.each( id, function( key, value ){
                        window.setTimeout(function(){
                            axios.post(`{!! route('bulk_sync') !!}`, { personnel: value })
                            .then(function(response) {
                                if (response.data.status) {
                                    count++;
                                    
                                        $('.modal > .modal-content > p').html(`${count} out of ${id.length} records synchronized`);
                                        let percentage = count/id.length*100;
                                        $('.determinate').animate({
                                            width: `${percentage+percentage}%`
                                        });
                                        $('.percentage').text(`${percentage}%`);
                                
                                }else{
                                    $('.modal').modal('close');
                                    $('.cloudUpload').prop('disabled', false).html(`<i class="material-icons right">cloud_upload</i> push to cloud`);
                                    $('#users-table th input:checked'). prop("checked", false);
                                    $('#users-table').DataTable().ajax.reload();
                                    $.wnoty({
                                        type: 'error',
                                        message: `Could not open connection to database server.  Please check your configuration.`,
                                        autohideDelay: 5000
                                    });
                                }
                            });
                        }, 5000);
                    });
                    $(document).on('click', '#modal-close', function(){
                        $.wnoty({
                            type: 'success',
                            message: `${id.length} records synchronized successfully!`,
                            autohideDelay: 5000
                        });
                        $('.cloudUpload').prop('disabled', false).html(`<i class="material-icons right">cloud_upload</i> push to cloud`);
                        $('#users-table th input:checked'). prop("checked", false);
                        $('#users-table').DataTable().ajax.reload();
                    });
                } else {
                alert('You must select at least one personnel!');
                }
            }
        });


        // CLOUD DOWNLOAD
        $(document).on('click', '#cloudDownload', function() {
            let count = 0;
            $(this).prop('disabled', true).html('DOWNLOADING...');
            $('.modal > .modal-content > h4').html(`Synchronizing from remote database`);
            $('.modal > .modal-content > p').html(`Fetching new or updated records, please wait...`);
            $('.modal > .modal-content > .progress').html(`<div class="indeterminate"></div>`);
            $('.modal > .modal-content > .percentage').html(`0 Records found, searching...`);
            $('.modal').modal('open');
            axios.get(`{!! route('cloud_download') !!}`)
            .then(function(response) {
                if (response.data.status) {
                    let count = response.data.count;
                    $('.modal > .modal-content > p').html(`Done!`);
                    $('.modal > .modal-content > .progress').html(`<div class="determinate" style="width: 100%"></div>`);
                    $('.modal > .modal-content > .percentage').html(`${response.data.count} records synchronized successfully!`);
                }else{
                    $('.modal').modal('close');
                    $('.modal > .modal-content > h4').html(``);
                    $('.modal > .modal-content > p').html(``);
                    $('.modal > .modal-content > .progress').html(`<div class="determinate" style="width: 0%"></div>`);
                    $('.modal > .modal-content > .percentage').html(`0%`);

                    $('.cloudDownload').prop('disabled', false).html(`<i class="material-icons right">cloud_download</i> pull from cloud`);
                    $('#users-table th input:checked'). prop("checked", false);
                    $('#users-table').DataTable().ajax.reload();
                    $.wnoty({
                        type: 'error',
                        message: `Something went wrong.`,
                        autohideDelay: 5000
                    });
                }
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });
                    
            $(document).on('click', '#modal-close', function(){
                $('.cloudDownload').prop('disabled', false).html(`<i class="material-icons right">cloud_download</i> Download to cloud`);
                $('#users-table th input:checked'). prop("checked", false);
                $('#users-table').DataTable().ajax.reload();
            });
        });


    </script>

@endpush