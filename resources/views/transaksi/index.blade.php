@extends('layouts.app')
@section('content')
<div class="portlet">
    @include('layouts.message')
    <div class="portlet-header">
        <p class="portlet-title">Transaksi</p>
        <div class="ml-auto">
            <button type="button" class="btn btn-primary btn-icon" onclick="tambahTransaksi()"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="portlet-body">
        <table class="table table-consoned table-bordered" id="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No Invoice</th>
                    <th>Status</th>
                    <th>Batas Waktu</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
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
                {data: 'status', searchable: false, orderable: false},
                {data: 'deadline', name:'transaksi.deadline'},
                {data: 'total', name: 'transaksi.total'},
                {data: 'payment', searchable: false, orderable: false},
                {data: '_', searchable: false, orderable: false, class: 'text-right text-nowrap'}
            ]
        });
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
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function destroy(id){
        confirmDialog('Apakah anda yakin menghapus data ini?', 'proses tidak dapat dibatalkan', function() {
            var url = '{{ route("transaksi.destroy", ":id") }}';
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