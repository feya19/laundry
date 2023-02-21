@extends('layouts.app')
@section('content')
<div class="portlet">
    <div class="portlet-header">
        <p class="portlet-title">Outlet</p>
        <div class="ml-auto">
            <button type="button" class="btn btn-primary btn-icon" onclick="tambahOutlet()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-consoned table-bordered" id="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
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
                {data: 'nama', name: 'outlets.nama'},
                {data: 'alamat', name: 'outlets.alamat'},
                {data: 'telepon', name: 'outlets.telepon'},
                {data: '_', searchable: false, orderable: false, class: 'text-right text-nowrap'}
            ]
        });
    });

    function tambahOutlet(){
        axios.get('{{ route('master.outlet.create') }}').then((response) => {
            bootbox.dialog({
                title: 'Tambah Outlet',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function show(id){
        var url = '{{ route("master.outlet.show", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Lihat Outlet',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function store(){
        axios.post('{{ route('master.outlet.store') }}', $('#formCreate').serialize()).then((response) => {
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
        var url = '{{ route("master.outlet.edit", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Edit Outlet',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function update(id){
        var url = '{{ route("master.outlet.update", ":id") }}';
        url = url.replace(':id', id);
        axios.patch(url, $('#formEdit').serialize()).then((response) => {
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
            var url = '{{ route("master.outlet.destroy", ":id") }}';
            url = url.replace(':id', id);
            axios.delete(url).then((response) => {
                toastr.success('Success', response.data.message);
                dataTable.ajax.reload();
            }).catch((error) => {
                toastr.error('Failed', error.response.data.message);
            });
        });
    }
</script>
@endpush