<table class="table">
    <tr>
        <th style="border: 0; width: 120px">Nama Produk</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{{ $model->nama }}</td>
    </tr>
    <tr>
        <th style="border: 0; width: 120px">Jenis Produk</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{!! $model->jenis_produk !!}</td>
    </tr>
    <tr>
        <th style="border: 0; width: 120px">Produk Outlet</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{!! $model->produk_outlet !!}</td>
    </tr>
    <tr>
        <th style="border: 0; width: 120px">Harga</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{{ $model->harga }}</td>
    </tr>
    <tr>
        <th style="border: 0; width: 120px">Dibuat Oleh</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{{ $model->created_by }}</td>
    </tr>
    <tr>
        <th style="border: 0; width: 120px">Diperbarui Oleh</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{{ $model->updated_by }}</td>
    </tr>
</table>