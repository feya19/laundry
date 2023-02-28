{!! Form::model($model,['id' => 'formStatus']) !!}   
    <div class="form-group">
        <label for="status">Status <span class="text-danger">*</span></label>
        {!! Form::hidden('lastStatus', $model->latestStatus->status) !!}
        {!! Form::select('status', $status, null, ['class' => 'form-control', 'id' => 'statusEdit']) !!}
    </div>
    <div id="pembayaran">
        <div class="form-group">
            <label for="subtotal">Subtotal</label>
            {!! Form::text('subtotal', $model->subtotal ?? 0, ['class' => 'form-control text-right', 'id' => 'subtotal', 'readonly', 'data-input-type' => 'number-format']) !!}
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="diskon">Diskon</label>
                <div class="input-group">
                    {!! Form::text('diskon', $model->diskon ?? 0, ['class' => 'form-control text-right', 'id' => 'diskon', 'data-input-type' => 'number-format']) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-percent"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group col-6">
                <label for="potongan">Potongan</label>
                {!! Form::text('potongan', $model->potongan ?? 0, ['class' => 'form-control text-right', 'id' => 'potongan', 'data-input-type' => 'number-format']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="biaya_tambahan">Biaya Tambahan</label>
            {!! Form::text('biaya_tambahan', $model->biaya_tambahan ?? 0, ['class' => 'form-control text-right', 'id' => 'biaya_tambahan', 'data-input-type' => 'number-format']) !!}
        </div>
        <div class="form-group">
            <label for="total">Total Biaya</label>
            {!! Form::text('total', $model->total ?? 0, ['class' => 'form-control text-right', 'id' => 'total', 'data-input-type' => 'number-format', 'readonly']) !!}
        </div>
        <div class="form-group">
            <label for="bayar">Bayar</label>
            {!! Form::text('bayar', $model->bayar ?? 0, ['class' => 'form-control text-right', 'id' => 'bayar', 'data-input-type' => 'number-format', 'onkeyup' => 'setPembayaran()']) !!}
        </div>
        <div class="form-group">
            <label for="kembali">Kembali</label>
            {!! Form::text('kembali', $model->kembali ?? 0, ['class' => 'form-control text-right', 'id' => 'kembali', 'data-input-type' => 'number-format', 'readonly']) !!}
        </div>
    </div>
    <div class="text-right">
        <button type="button" class="btn btn-secondary" onclick="bootbox.hideAll()">Batal</button>
        <button type="button" onclick="updateStatus({{$model->id}})" class="btn btn-primary">Perbarui</button>
    </div>
{!! Form::close() !!}
<script>
    $(() => {
        $('#formStatus').buildForm();
        $('#diskon').keyup(function(e){
            if($(this).val() == ''){
                $('#potongan').prop('disabled', false);
            }else if(parseFloat($(this).val()) != 0){
                $('#potongan').val(0).prop('disabled', true);
            }else{
                $('#potongan').prop('disabled', false);
            }
        });

        $('#potongan').keyup(function(e){
            if($(this).val() == ''){
                $('#diskon').prop('disabled', false);
            }else if(parseFloat($(this).val()) != 0){
                $('#diskon').val(0).prop('disabled', true);
            }else{
                $('#diskon').prop('disabled', false);
            }
        });
        $('#potongan,#diskon,#biaya_tambahan').keyup(() => {setTotal()});
    })

    function setTotal(){
        var subtotal = toFloat($('#subtotal').val());
        var diskon = toFloat($('#diskon').val());
        var potongan = toFloat($('#potongan').val());
        var biaya_tambahan = toFloat($('#biaya_tambahan').val());
        $('#total').val(subtotal - potongan - (subtotal * diskon / 100) + biaya_tambahan);
    }

    function setPembayaran(){
        var total =  $('#total').val();
        var bayar = $('#bayar').val();
        if (toFloat(bayar) > toFloat(total)) {
            $('#kembali').val(toFloat(bayar) - toFloat(total));
        } else {
            $('#kembali').val(0);
        }
    }
    
</script>