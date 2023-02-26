@extends('layouts.app')
@section('content')
<div class="portlet">
    @include('layouts.message')
    <div class="portlet-header">
        <div class="col-md-6">
            <p class="portlet-title">Transaksi</p>
        </div>
        <div class="col-md-6 mt-2 mt-md-0">
            <div class="form-row align-items-center justify-content-md-end">
                <label for="status" class="col-md-1 col-3 mb-0 mr-3">Status</label>
                {!! Form::select('status', [''=>'Pilih']+$status, null, ['class' => 'form-control col-md-3 col-6 mr-3', 'id' => 'status']) !!}
                <button type="button" class="btn btn-primary btn-icon" onclick="tambahTransaksi()"><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-consoned table-bordered" id="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No Invoice</th>
                    <th width="1">Status</th>
                    <th>Batas Waktu</th>
                    <th>Total</th>
                    <th width="1">Lunas</th>
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
                {data: 'created_at', name: 'transaksi.created_at'},
                {data: 'no_invoice', name:'transaksi.no_invoice'},
                {data: 'status', searchable: false, orderable: false, class: 'text-center'},
                {data: 'deadline', name:'transaksi.deadline'},
                {data: 'total', name: 'transaksi.total', class: 'text-right'},
                {data: 'lunas', searchable: false, orderable: false, class: 'text-center'},
                {data: '_', searchable: false, orderable: false, class: 'text-center text-nowrap'}
            ],
            order: [[0, 'DESC']]
        });

        $('#status').change(function(){
            dataTable.ajax.url('?status='+$('#status :selected').val()).load();
        })
    });

    function tambahTransaksi(){
        window.location.href='{{ route('transaksi.create') }}';
    }

    function show(id){
        var url = '{{ route("transaksi.show", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Lihat Transaksi',
                message: response.data,
                size: 'large'
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function editStatus(id){
        var url = '{{ route("transaksi.editStatus", ":id") }}';
        url = url.replace(':id', id);
        axios.get(url).then((response) => {
            bootbox.dialog({
                title: 'Edit Status Transaksi',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function updateStatus(id){
        if($('#status :selected').val() == 'taken'){
            confirmDialog('Apakah anda yakin mengirim data ini?','proses tidak dapat dibatalkan', () => {
               submitStatus(id);
            });
        }else{
            submitStatus(id);
        }
    }

    function submitStatus(id){
        var url = '{{ route("transaksi.updateStatus", ":id") }}';
        url = url.replace(':id', id);
        axios.post(url, $('#formStatus').serialize()).then((response) => {
            toastr.success('Success', response.data.message);
            dataTable.ajax.reload().draw();
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
            var url = '{{ route("transaksi.destroy", ":id") }}';
            url = url.replace(':id', id);
            axios.delete(url).then((response) => {
                toastr.success('Success', response.data.message);
                dataTable.ajax.reload().draw();
            }).catch((error) => {
                toastr.error('Failed', error.response.data.message);
            });
        });
    }
</script>
@endpush