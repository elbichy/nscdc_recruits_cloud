@extends('layouts.app', ['title' => 'Trashed Redeployments Records'])

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
                <h6 class="center sectionHeading">PERSONNEL REDEPLOYMENT - TRASHED RECORDS</h6>

                {{-- SALES TABLE --}}
                <div class="sectionTableWrap z-depth-1">
                    <div class="row topMenuWrap">
                        <button id="restoreTrashed" class="restoreTrashed btn btn-small green darken-2 white-text"><i class="material-icons right">restore</i> restore from trash</button>
                        <button id="deleteCloud" class="deleteCloud btn btn-small orange darken-2 white-text"><i class="material-icons right">cloud_off</i> delete from cloud</button>
                        <button id="deletePermanently" class="deletePermanently btn btn-small red darken-2 white-text"><i class="material-icons right">delete</i> delete permanently</button>
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
                                <th>Created</th>
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
                ajax:  `{!! route('redeployment_get_trash') !!}`,
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
                    { data: 'rank', name: 'rank'},
                    { data: 'from', name: 'from'},
                    { data: 'to', name: 'to'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'view', name: 'view', "orderable": false, "searchable": false}
                ]
            });
            $('.dataTables_length > label > select').addClass('browser-default');
        });

        // RESTORE SELECTED TRASHED REDEPLOYMENTS
        $(document).on('click', '#restoreTrashed', function() {
            let id = [];
            let count = 0;
            if (confirm('Are you sure you want to restore the selected records?')) {
                $('.personnelCheckbox:checked').each(function() {
                    id.push($(this).val())
                });
                if (id.length > 0) {
                    $('.modal > .modal-content > h4').html(`Restoring records from trash...`);
                    $('.modal > .modal-content > p').html(`0 out of ${id.length} records restored`);
                    $('.modal').modal('open');
                    
                    $.each( id, function( key, value ){
                        window.setTimeout(function(){
                            axios.post(`{!! route('redeployment_trash_restore_bulk') !!}`, { personnel: value })
                            .then(function(response) {
                                if (response.data.status) {
                                    count++;
                                    
                                        $('.modal > .modal-content > p').html(`${count} out of ${id.length} records restored`);
                                        let percentage = count/id.length*100;
                                        $('.determinate').animate({
                                            width: `${percentage+percentage}%`
                                        });
                                        $('.percentage').text(`${percentage}%`);
                                
                                }else{
                                    $('.modal').modal('close');
                                    $('.restoreTrashed').prop('disabled', false).html(`<i class="material-icons right">restore</i> restore from trash`);
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
                            message: `${id.length} records restored successfully!`,
                            autohideDelay: 5000
                        });
                        $('.restoreTrashed').prop('disabled', false).html(`<i class="material-icons right">restore</i> restore from trash`);
                        $('#users-table th input:checked'). prop("checked", false);
                        $('#users-table').DataTable().ajax.reload();
                    });
                } else {
                alert('You must select at least one personnel!');
                }
            }
        });


        // DELETE FROM CLOUD
        $(document).on('click', '#deleteCloud', function() {
            let id = [];
            let count = 0;
            if (confirm('Are you sure you want to delete all selected records from the cloud?')) {
                $('.deleteCloud').prop('disabled', true).html('DELETING FROM CLOUD...');
                $('.personnelCheckbox:checked').each(function() {
                    id.push($(this).val())
                });
                if (id.length > 0) {
                    $('.modal > .modal-content > h4').html(`Deleting from remote database...`);
                    $('.modal > .modal-content > p').html(`0 out of ${id.length} records deleted`);
                    $('.modal').modal('open');
                    
                    $.each( id, function( key, value ){
                        window.setTimeout(function(){
                            axios.post(`{!! route('redeployment_trash_cloud_bulk') !!}`, { personnel: value })
                            .then(function(response) {
                                if (response.data.status == 'true') {
                                    count++;
                                    
                                        $('.modal > .modal-content > p').html(`${count} out of ${id.length} records deleted`);
                                        let percentage = count/id.length*100;
                                        $('.determinate').animate({
                                            width: `${percentage+percentage}%`
                                        });
                                        $('.percentage').text(`${percentage}%`);
                                
                                }else if(response.data.status == 'false'){
                                    $('.modal').modal('close');
                                    $('.deleteCloud').prop('disabled', false).html(`<i class="material-icons right">cloud_off</i> delete from cloud`);
                                    $('#users-table th input:checked'). prop("checked", false);
                                    $('#users-table').DataTable().ajax.reload();
                                    $.wnoty({
                                        type: 'error',
                                        message: response.data.message,
                                        autohideDelay: 5000
                                    });
                                }else if(response.data.status == 'connection_error'){
                                    $('.modal').modal('close');
                                    $('.deleteCloud').prop('disabled', false).html(`<i class="material-icons right">cloud_off</i> delete from cloud`);
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
                            message: `${id.length} records deleted successfully!`,
                            autohideDelay: 5000
                        });
                        $('.deleteCloud').prop('disabled', false).html(`<i class="material-icons right">cloud_off</i> delete from cloud`);
                        $('#users-table th input:checked'). prop("checked", false);
                        $('#users-table').DataTable().ajax.reload();
                    });
                } else {
                alert('You must select at least one personnel!');
                }
            }
        });


        // PERMANENTLY DELETE BULK RECORDS
        $(document).on('click', '#deletePermanently', function() {
            let id = [];
            let count = 0;
            if (confirm('Are you sure you want to permanently delete the selected records?')) {
                $('.personnelCheckbox:checked').each(function() {
                    id.push($(this).val())
                });
                if (id.length > 0) {
                    $('.modal > .modal-content > h4').html(`Deleting permanently from database...`);
                    $('.modal > .modal-content > p').html(`0 out of ${id.length} records deleted`);
                    $('.modal').modal('open');
                    
                    $.each( id, function( key, value ){
                        window.setTimeout(function(){
                            axios.post(`{!! route('redeployment_trash_permanently_bulk') !!}`, { personnel: value })
                            .then(function(response) {
                                if (response.data.status) {
                                    count++;
                                    
                                        $('.modal > .modal-content > p').html(`${count} out of ${id.length} records deleted`);
                                        let percentage = count/id.length*100;
                                        $('.determinate').animate({
                                            width: `${percentage+percentage}%`
                                        });
                                        $('.percentage').text(`${percentage}%`);
                                
                                }
                                else{
                                    $('.modal').modal('close');
                                    $('.deleteCloud').prop('disabled', false).html(`<i class="material-icons right">cloud_off</i> delete from cloud`);
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
                            message: `${id.length} records deleted successfully!`,
                            autohideDelay: 5000
                        });
                        $('.deleteCloud').prop('disabled', false).html(`<i class="material-icons right">cloud_off</i> delete from cloud`);
                        $('#users-table th input:checked'). prop("checked", false);
                        $('#users-table').DataTable().ajax.reload();
                    });
                } else {
                alert('You must select at least one personnel!');
                }
            }
        });

    </script>

@endpush