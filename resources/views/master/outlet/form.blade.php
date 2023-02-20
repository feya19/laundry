<div class="form-group">
    <label for="nama">Nama <span class="text-danger">*</span></label>
    {!! Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) !!}
</div>
<div class="form-group">
    <label for="alamat">Alamat <span class="text-danger">*</span></label>
    {!! Form::textArea('alamat', null, ['class' => 'form-control', 'id' => 'alamat', 'rows' => 3]) !!}
</div>
<div class="form-group">
    <label for="telepon">Telepon <span class="text-danger">*</span></label>
    {!! Form::text('telepon', null, ['class' => 'form-control', 'id' => 'telepon', 'data-input-type' => 'number']) !!}
</div>