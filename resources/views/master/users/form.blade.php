<div class="form-group">
    <label for="username">Username <span class="text-danger">*</span></label>
    {!! Form::text('username', null, ['class' => 'form-control', 'id' => 'username']) !!}
</div>
<div class="form-group">
    <label for="nama">Nama <span class="text-danger">*</span></label>
    {!! Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) !!}
</div>
<div class="form-group">
    <label for="role">Role <span class="text-danger">*</span></label>
    {!! Form::select('role', $roles, null, ['class' => 'form-control', 'id' => 'alamat', 'data-input-type' => 'select2']) !!}
</div>
<div class="form-group">
    <label for="user_outlet">User Outlet</label>
    {!! Form::select('user_outlet[]', $outlet, null, ['class' => 'form-control', 'id' => 'outlet', 'data-input-type' => 'select2', 'multiple']) !!}
</div>
<div class="form-group">
    <label for="FotoProfile">Foto</label>
    {!! Form::file('file', ['class' => 'd-none file-upload-input ', 'id' => 'FotoProfil', 'accept' => 'image/*', 'style' => 'max-width: 250px;', 'onchange' => 'fileFoto()' ]) !!}
    <div class="input-group">
        {!! Form::text('photo', null, ['class' => 'form-control', 'id' => 'FileNama', 'readonly']) !!}
        <div class="input-group-append">
            <button class="btn btn-secondary" type="button" id="btn-browse" onclick="browse_file()">Tambah File</button>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="password">Password <span class="text-danger">{{ isset($model['id']) ? '' : '*'}}</span></label>
    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
</div>
<div class="form-group">
    <label for="konfirmasi-password">Konfirmasi Password <span class="text-danger">{{ isset($model['id']) ? '' : '*'}}</span></label>
    {!! Form::password('konfirmasi_password', ['class' => 'form-control', 'id' => 'konfirmasi-password']) !!}
</div>