<div class="form-group">
    <label for="nama">Nama <span class="text-danger">*</span></label>
    {!! Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama']) !!}
</div>
<div class="form-group">
    <label for="alamat">Alamat <span class="text-danger">*</span></label>
    {!! Form::textArea('alamat', null, ['class' => 'form-control', 'id' => 'alamat', 'rows' => 3]) !!}
</div>
<div class="form-group">
    <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
    {!! Form::select('jenis_kelamin', $jenis_kelamin, null, ['class' => 'form-control', 'id' => 'jenis_kelamin']) !!}
</div>
<div class="form-group">
    <label for="telepon">Telepon <span class="text-danger">*</span>&nbsp;<span class="h6">(Sertakan Kode Dial Negara)</span></label>
    <div class="input-group" id="groupTelepon">
        <div class="input-group-prepend">
            <span class="input-group-text">+</span>
        </div>
        {!! Form::text('telepon', null, ['class' => 'form-control', 'id' => 'telepon', 'data-input-type' => 'number', 'onkeyup' => "checkNomor()"]) !!}
    </div>
</div>