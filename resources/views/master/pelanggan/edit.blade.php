{!! Form::model($model, ['id' => 'formEdit']) !!}
    @include('master.pelanggan.form')
    <div class="text-right">
        <button type="button" class="btn btn-secondary" onclick="bootbox.hideAll()">Batal</button>
        <button type="button" class="btn btn-primary" onclick="update({{$model->id}})">Perbarui</button>
    </div>
{!! Form::close() !!}

<script>
    $('#formEdit').buildForm();
</script>