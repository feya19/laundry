{!! Form::open(['id' => 'formCreate']) !!}
    @include('master.pelanggan.form')
    <div class="text-right">
        <button type="button" class="btn btn-secondary" onclick="bootbox.hideAll()">Batal</button>
        <button type="button" class="btn btn-primary" onclick="store()">Tambah</button>
    </div>
{!! Form::close() !!}

<script>
    $('#formCreate').buildForm();
</script>