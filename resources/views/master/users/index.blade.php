@extends('layouts.app')
@section('content')
<div class="portlet">
    <div class="portlet-header">
        <p class="portlet-title">Users</p>
        <div class="ml-auto">
            <button type="button" class="btn btn-primary btn-icon" onclick="tambahUser()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-consoned table-bordered" id="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th width="1"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </div>
    </table>
</div>
@endsection
@push('script')
<script>
    var dataTatble;
    $(function(){
        dataTable = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '',
            columns: [
                {data: 'username', name: 'users.username'},
                {data: 'name', name: 'users.name'},
                {data: 'role', name: 'users.role'},
                {data: '_', searchable: false, orderable: false, class: 'text-right text-nowrap'}
            ]
        });
    });

    function tambahUser(){
        axios.get('{{ route('master.users.create') }}').then((response) => {
            bootbox.dialog({
                title: 'Tambah User',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function show(id){
        var url = '{{ route("master.users.show", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Lihat User',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function store(){
        $('.error_message').remove();
        axios.post('{{ route('master.users.store') }}', new FormData(document.getElementById('formCreate'))).then((response) => {
            toastr.success('Success', response.data.message);
            dataTable.ajax.reload();
            bootbox.hideAll();
        }).catch((error) => {
            switch (error.response.status) {
                case 422:
                    var response = JSON.parse(error.request.responseText);
                    $('#formCreate').prepend(validation(response))
                    $('#formCreate').unblock();
                    break;
                default:
                    toastr.error('Failed', error.response.data.message);
                    break;
            }
        });
    }

    function edit(id){
        var url = '{{ route("master.users.edit", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Edit user',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function update(id){
        var url = '{{ route("master.users.update", ":id") }}';
        url = url.replace(':id', id);
        $('.error_message').remove();
        axios.patch(url, new FormData(document.getElementById('formEdit'))).then((response) => {
            toastr.success('Success', response.data.message);
            dataTable.ajax.reload();
            bootbox.hideAll();
        }).catch((error) => {
            switch (error.response.status) {
                case 422:
                    var response = JSON.parse(error.request.responseText);
                    $('#formEdit').prepend(validation(response))
                    $('#formEdit').unblock();
                    break;
                default:
                    toastr.error('Failed', error.response.data.message);
                    break;
            }
        });
    }

    function destroy(id){
        confirmDialog('Apakah anda yakin menghapus data ini?', 'proses tidak dapat dibatalkan', function() {
            var url = '{{ route("master.users.destroy", ":id") }}';
            url = url.replace(':id', id);
            axios.delete(url).then((response) => {
                toastr.success('Success', response.data.message);
                dataTable.ajax.reload();
            }).catch((error) => {
                toastr.error('Failed', error.response.data.message);
            });
        });
    }

    function browse_file() {
        $('#FotoProfil').click();
    }

    function fileFoto(){
        var reader = new FileReader();
        reader.onload = function (e) {
            if(e.total > 2048000){
                alertDialog('Ukuran file tidak boleh melebihi 2 MB');
                return false;
            }
        }
        $('#FileNama').val(document.getElementById('FotoProfil').files[0].name);
    }
</script>
@endpush