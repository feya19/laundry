<style>
   .theme-light .select2-results__option[aria-selected=true] {
    color: #2196f3;
    background: #f5f5f5; }
  .theme-dark .select2-results__option[aria-selected=true] {
    color: #2196f3;
    background: #616161; }
</style>
<div class="portlet">
    @include('layouts.message')
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="no_invoice">No Invoice</label>
                    {!! Form::text('no_invoice', 'AUTO', ['class' => 'form-control', 'id' => 'no_invoice', 'readonly']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Pelanggan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        {!! Form::select('pelanggan_id', ['' => '']+$pelanggan, null, ['class' => 'form-control '.add_error($errors, 'pelanggan_id'), 'id' => 'pelanggan']) !!}
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="btnAddPelanggan" onclick="formAddPelanggan()"><i class="fa fa-plus"></i></button>
                        </div>
                        @error('pelanggan_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="batas_waktu">Batas Waktu <span class="text-danger">*</span></label>
                    {!! Form::text('batas_waktu', '', ['class' => 'form-control '.add_error($errors, 'batas_waktu'), 'id' => 'batas_waktu', 'data-input-type' => 'datetime']) !!}
                    @error('batas_waktu')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    {!! Form::select('status', $status, null, ['class' => 'form-control '.add_error($errors, 'status'), 'id' => 'status']) !!}
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portlet">
    <div class="portlet-header">
        <p class="portlet-title">Detail</p>
    </div>
    <div class="portlet-body">
        @if($errors->has('produk.*'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-icon"><i class="fa fa-ban"></i></div>
                <div class="alert-content">

                    @foreach ($errors->get('produk.*') as $key => $error)
                        <p class="mb-0"><strong>{{ ucwords($error[0]) }}</strong></p>
                    @endforeach
                </div>
                <button type="button" class="btn btn-text-light btn-icon alert-dismiss" data-dismiss="alert">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        @endif
        @php
            $produk = new \App\Models\Produk();
        @endphp
        <table class="table table-bordered" id="table-produk">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th class="text-right"  width="200">Harga</th>
                    <th class="text-center" width="100">Jumlah/Kg</th>
                    <th class="text-right"  width="200">Total</th>
                    <th width="1"></th>
                </tr>
            </thead>
            <tbody>
                <tr id="form-add-produk">
                    <td>
                        {!! Form::select('produk[0][produk_detail]', ['' => 'Pilih'], '', ['class' => 'form-control', 'id' => 'form-add-produk-nama_produk' ]) !!}
                        {!! Form::hidden('produk[0][produks_id]', '', ['class' => 'form-control', 'id' => 'form-add-produk-produks_id']) !!}
                        {!! Form::hidden('produk[0][nama]', '', ['class' => 'form-control', 'id' => 'form-add-produk-produk']) !!}
                    </td>
                    <td>
                        {!! Form::text('produk[0][harga]', 0, ['class' => 'form-control text-right', 'data-input-type' => 'number-format', 'id' => 'form-add-produk-harga', 'onchange' => 'countTotal()', 'readonly']) !!}
                    </td>
                    <td>
                        {!! Form::text('produk[0][jumlah]', 0, ['class' => 'form-control text-center', 'id' => 'form-add-produk-jumlah', 'onkeyup' => 'countTotal()', 'data-input-type' => 'number-format', 'data-thousand-separator' => 'false']) !!}
                    </td>
                    <td>
                        {!! Form::text('produk[0][total]',0,['class' => 'form-control text-right', 'data-input-type' => 'number-format', 'id' => 'form-add-produk-total', 'readonly']) !!}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-md" onclick="onAddProduk()"><i class="fa fa-plus"></i></button>
                    </td>
                </tr>
                @if(old('produk'))
                    @foreach(old('produk') as $key => $value)
                        @if(isset($value['produks_id']))
                            <tr data-row-id="{{ $value['produks_id'] }}">
                                <td>
                                    {!! Form::hidden('produk['.$value['produks_id'].'][produks_id]', $value['produks_id'], ['id' => 'produk-produks_id-'.$value['produks_id']]) !!}
                                    {!! Form::hidden('produk['.$value['produks_id'].'][nama]', $value['nama']) !!}
                                    {{$value['nama']}}
                                </td>
                                <td>
                                    {!! Form::text('produk['.$value['produks_id'].'][harga]',$value['harga'],['id' => 'produk-harga-'.$value['produks_id'], 'onchange' => 'countTotal('.$value['produks_id'].')','class' => 'form-control text-right', 'data-input-type' => 'number-format', 'readonly']) !!}
                                </td>
                                <td>
                                    {!! Form::text('produk['.$value['produks_id'].'][jumlah]',$value['jumlah'],['id' => 'produk-jumlah-'.$value['produks_id'], 'onkeyup' => 'countTotal('.$value['produks_id'].')', 'class' => 'form-control text-center', 'data-input-type' => 'number-format', 'data-thousand-separator' => 'false']) !!}
                                </td>
                                <td>
                                    {!! Form::text('produk['.$value['produks_id'].'][total]',$value['total'],['id' => 'produk-total-'.$value['produks_id'], 'class' => 'form-control text-right', 'data-input-type' => 'number-format', 'readonly']) !!}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-md" onclick="produk_remove({{ $value['produks_id'] }})"><i class="fa fa-minus"></i></button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @elseif(isset($model['produk']))
                    @php $grand_total = 0; @endphp
                    @foreach($model['produk'] as $key => $value)
                        @php $grand_total += $value['total']; @endphp
                        <tr data-row-id="{{ $key }}">
                            <td>
                                {!! Form::hidden('produk['.$value['produks_id'].'][produks_id]', $value['produks_id'], ['id' => 'produk-produks_id-'.$value['produks_id']]) !!}
                                {!! Form::hidden('produk['.$value['produks_id'].'][nama]', $value['nama_produk']) !!}
                                {{$value['nama_produk']}}
                            </td>
                            <td>
                                {!! Form::text('produk['.$value['produks_id'].'][harga]',$value['harga'],['id' => 'produk-harga-'.$value['produks_id'], 'class' => 'form-control text-right', 'data-input-type' => 'number-format', 'onchange' => 'countTotal('.$value['produks_id'].')','readonly']) !!}
                            </td>
                            <td>
                                {!! Form::text('produk['.$value['produks_id'].'][jumlah]',$value['jumlah'],['id' => 'produk-jumlah-'.$value['produks_id'], 'class' => 'form-control text-center', 'onkeyup' => 'countTotal('.$value['produks_id'].')', 'data-input-type' => 'number-format', 'data-thousand-separator' => 'false']) !!}
                            </td>
                            <td>
                                {!! Form::text('produk['.$value['produks_id'].'][total]',$value['total'],['id' => 'produk-total-'.$value['produks_id'], 'class' => 'form-control text-right', 'data-input-type' => 'number-format', 'readonly']) !!}
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-md" onclick="produk_remove({{ $key }})"><i class="fa fa-minus"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="form-group px-1">
            <label for="note">Catatan</label>
            {!! Form::text('note', NULL, ['id' => 'note', 'class' => 'form-control '.add_error($errors, 'note')]) !!}
            @error('note')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="portlet" id="pembayaranSection">
    <div class="portlet-header">
        <p class="portlet-title">Pembayaran</p>
    </div>
    <div class="portlet-body">
        <div class="form-group">
            <label for="subtotal">Subtotal</label>
            {!! Form::text('subtotal', 0, ['class' => 'form-control text-right '.add_error($errors, 'subtotal'), 'id' => 'subtotal', 'readonly', 'data-input-type' => 'number-format', 'onchange' => 'setTotal()']) !!}
            @error('subtotal')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="diskon">Diskon</label>
                    {!! Form::text('diskon', 0, ['class' => 'form-control text-right '.add_error($errors, 'diskon'), 'id' => 'diskon', 'data-input-type' => 'number-format']) !!}
                    @error('diskon')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="potongan">Potongan</label>
                    {!! Form::text('potongan', 0, ['class' => 'form-control text-right '.add_error($errors, 'potongan'), 'id' => 'potongan', 'data-input-type' => 'number-format']) !!}
                    @error('potongan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="biaya_tambahan">Biaya Tambahan</label>
                    {!! Form::text('biaya_tambahan', 0, ['class' => 'form-control text-right '.add_error($errors, 'biaya_tambahan'), 'id' => 'biaya_tambahan', 'data-input-type' => 'number-format']) !!}
                    @error('biaya_tambahan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="total">Total</label>
                    {!! Form::text('total', 0, ['class' => 'form-control text-right '.add_error($errors, 'total'), 'id' => 'total', 'data-input-type' => 'number-format', 'readonly']) !!}
                    @error('total')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Pembayaran</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="total">Total Biaya</label>
                    {!! Form::text('total_biaya', 0, ['class' => 'form-control text-right '.add_error($errors, 'total_biaya'), 'id' => 'total_biaya', 'data-input-type' => 'number-format', 'readonly']) !!}
                    @error('total_biaya')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="bayar">Bayar</label>
                    {!! Form::text('bayar', 0, ['class' => 'form-control text-right '.add_error($errors, 'bayar'), 'id' => 'bayar', 'data-input-type' => 'number-format', 'onkeyup' => 'setPembayaran()']) !!}
                    @error('bayar')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kembali">Kembali</label>
                    {!! Form::text('kembali', 0, ['class' => 'form-control text-right '.add_error($errors, 'kembali'), 'id' => 'kembali', 'data-input-type' => 'number-format', 'readonly']) !!}
                    @error('kembali')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group text-right">
                    <a href="javascript:void();" class="btn btn-secondary" id="batal" data-dismiss="modal">Batal</a>
                    <button type="button" onclick="submitForm(true)" class="btn btn-success">Bayar</button>
                </div>
            </div>
        </div>
    </div>
</div>
