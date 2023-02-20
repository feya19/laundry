<div class="form-group">
    <label for="nama">Nama <span class="text-danger">*</span></label>
    {!! Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) !!}
</div>
<div class="form-group">
    <label for="jenis_produk">Jenis Produk <span class="text-danger">*</span></label>
    {!! Form::select('jenis_produk[]', $jenis_produk, null, ['class' => 'form-control', 'id' => 'jenis_produk', 'data-input-type' => 'select2', 'multiple']) !!}
</div>
<div class="form-group">
    <label for="produk_outlet">Produk Outlet <span class="text-danger">*</span></label>
    {!! Form::select('produk_outlet[]', $outlet, null, ['class' => 'form-control', 'id' => 'outlet', 'data-input-type' => 'select2', 'multiple']) !!}
</div>
<div class="form-group">
    <label for="harga">Harga/kg <span class="text-danger">*</span></label>
    {!! Form::text('harga', $model->harga ?? 0, ['class' => 'form-control text-right', 'id' => 'harga', 'data-input-type' => 'number-format']) !!}
</div>