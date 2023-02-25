@extends('layouts.app')
@section('content')
<div class="portlet">
    @include('layouts.message')
    <div class="portlet-header">
        <p class="portlet-title">Produk</p>
        <div class="ml-auto">
            <button type="button" class="btn btn-primary btn-icon" onclick="tambahProduk()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-consoned table-bordered" id="table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Jenis Produk</th>
                    <th>Outlet</th>
                    <th>Harga/kg</th>
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
                {data: 'nama', name: 'produks.nama'},
                {data: 'jenis_produk', name:'produkJenis.jenis.jenis'},
                {data: 'outlet_produk', name:'produkOutlet.outlet.nama'},
                {data: 'harga', name: 'produks.harga'},
                {data: '_', searchable: false, orderable: false, class: 'text-right text-nowrap'}
            ]
        });
    });

    function tambahProduk(){
        axios.get('{{ route('master.produk.create') }}').then((response) => {
            bootbox.dialog({
                title: 'Tambah Produk',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function show(id){
        var url = '{{ route("master.produk.show", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Lihat produk',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function store(){
        axios.post('{{ route('master.produk.store') }}', $('#formCreate').serialize()).then((response) => {
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
        var url = '{{ route("master.produk.edit", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Edit Produk',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function update(id){
        var url = '{{ route("master.produk.update", ":id") }}';
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
            var url = '{{ route("master.produk.destroy", ":id") }}';
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