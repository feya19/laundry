@extends('layouts.app')
@section('content')
<div class="portlet">
    @include('layouts.message')
    <div class="portlet-header d-block">
        <div class="row">
            <div class="col-md-6">
                <p class="portlet-title">Pelanggan</p>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
                <div class="form-row align-items-center justify-content-md-end">
                    <label for="jenis_kelamin" class="col-md-1 col-3 mb-0 mr-2">Filter</label>
                    {!! Form::select('jenis_kelamin', $jenis_kelamin, null, ['class' => 'form-control col-md-3 col-6 mr-3', 'id' => 'jenis_kelamin']) !!}
                    <button type="button" class="col-auto mr-2 ml-auto ml-md-0 btn btn-primary btn-icon" onclick="tambahPelanggan()"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-consoned table-bordered" id="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th width="100">Telepon</th>
                    <th>Jenis Kelamin</th>
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
                {data: 'nama', name: 'pelanggan.nama'},
                {data: 'alamat', name: 'pelanggan.alamat'},
                {data: 'telepon', name: 'pelanggan.telepon'},
                {data: 'jenis_kelamin', searchable: false, orderable: false},
                {data: '_', searchable: false, orderable: false, class: 'text-right text-nowrap'}
            ]
        });
        
        $('#jenis_kelamin').change(function(){
            dataTable.ajax.url('?jenis_kelamin='+$('#jenis_kelamin :selected').val()).load();
        })
    });

    function tambahPelanggan(){
        axios.get('{{ route('master.pelanggan.create') }}').then((response) => {
            bootbox.dialog({
                title: 'Tambah Pelanggan',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function show(id){
        var url = '{{ route("master.pelanggan.show", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Lihat Pelanggan',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function store(){
        axios.post('{{ route('master.pelanggan.store') }}', $('#formCreate').serialize()).then((response) => {
            toastr.success('Success', response.data.message);
            dataTable.ajax.reload();
            bootbox.hideAll();
        }).catch((error) => {
            switch (error.response.status) {
                case 422:
                    var response = JSON.parse(error.request.responseText);
                    validation(response);
                    break;
                default:
                    toastr.error('Failed', error.response.data.message);
                    break;
            }
        });
    }

    function edit(id){
        var url = '{{ route("master.pelanggan.edit", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Edit Pelanggan',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function update(id){
        var url = '{{ route("master.pelanggan.update", ":id") }}';
        url = url.replace(':id', id);
        axios.patch(url, $('#formEdit').serialize()).then((response) => {
            toastr.success('Success', response.data.message);
            dataTable.ajax.reload();
            bootbox.hideAll();
        }).catch((error) => {
            switch (error.response.status) {
                case 422:
                    var response = JSON.parse(error.request.responseText);
                    validation(response);
                    break;
                default:
                    toastr.error('Failed', error.response.data.message);
                    break;
            }
        });
    }

    function destroy(id){
        confirmDialog('Apakah anda yakin menghapus data ini?', 'proses tidak dapat dibatalkan', function() {
            var url = '{{ route("master.pelanggan.destroy", ":id") }}';
            url = url.replace(':id', id);
            axios.delete(url).then((response) => {
                toastr.success('Success', response.data.message);
                dataTable.ajax.reload();
            }).catch((error) => {
                toastr.error('Failed', error.response.data.message);
            });
        });
    }

    function checkNomor(){
        let hp = $('#telepon').val();
        let first = hp.substr(0,1);
        if(!hp){
            $('#telepon').addClass('is-invalid');
            $('#groupTelepon .error_message').remove();
            $('#telepon').parent().append(`
                <span class="invalid-feedback error_message nomor" role="alert">
                    <strong>Telepon Harus Diisi</strong>
                </span>`
            );
        }else if(inArray(first, ['0', '-', ']' , '`'])){
            $('#telepon').addClass('is-invalid');
            $('#groupTelepon .error_message').remove();
            $('#telepon').parent().append(`
                <span class="invalid-feedback error_message nomor" role="alert">
                    <strong>Telepont Nomor Salah</strong>
                </span>`
            );
        }else if(hp.length > 15){
            $('#telepon').addClass('is-invalid');
            $('#groupTelepon .error_message').remove();
            $('#telepon').parent().append(`
                <span class="invalid-feedback error_message nomor" role="alert">
                    <strong>Telepon maksimal berisi 15 karakter</strong>
                </span>`
            );
        }else if(hp.length < 10){
            $('#telepon').addClass('is-invalid');
            $('#groupTelepon .error_message').remove();
            $('#telepon').parent().append(`
                <span class="invalid-feedback error_message nomor" role="alert">
                    <strong>Telepon minimal berisi 10 karakter</strong>
                </span>`
            );
        }else{
            $('#telepon').removeClass('is-invalid');
            $('#groupTelepon .error_message').remove();
        }
    }
</script>
@endpush