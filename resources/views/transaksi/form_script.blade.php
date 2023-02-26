<script>
    var used = [];
    var invoice = '{{session('invoice') ? route('transaksi.invoice'. ['id' => session('invoice')]) : '' }}';
    $(() => {
        if(invoice) window.open(invoice, '_blank');
        $('#batas_waktu').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            autoclose: true
        }).attr("placeholder", "yyyy-mm-dd hh:ii");
        $("#pelanggan").select2({
            templateResult: (state) => {
                if (state.loading) return "Searching...";
                return $state = $(`<label>${state.text}&ensp;+${state.telepon}</label>`)
            },
            ajax: {
                url: '{{route('pelanggan.json')}}',
                type: "GET",
                dataType: 'json',
                delay: 500,
                data: function(params) {
                    return {
                        q: params.term,
                        limit: 15,
                    }
                },
                processResults: function (response) {
                    return {
                        results: $.map(response.data, (item) => {
                            return {
                                text: item.nama,
                                id: item.id,
                                telepon: item.telepon,
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $.each($('[id^="produk-produks_id"]'), (i, row) => {
            used.push($('#'+row.id).val());
        });
        $('#form-add-produk-produks_id').val() && resetForm();
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
        $('#subtotal').change(() => {
            $.each($('[id^="harga-harga-"]'), (i, row) => {
                $('#'+row.id).keyup();
            });
        })

        $('#form-add-produk-nama_produk').select2({
            templateResult: (state) => {
                if (state.loading) return "Searching...";
                return $state = $(`<label>${state.text}&ensp;${state.label}</label>`)
            },
            ajax: {
                url: '{{route('produk.json')}}',
                type: "GET",
                dataType: 'json',
                delay: 500,
                data: function(params) {
                    return {
                        outlet: '{{session('outlets_id')}}',
                        q: params.term,
                        limit: 15,
                        not_in: used
                    }
                },
                processResults: function (response) {
                    return {
                        results: $.map(response.data, (item) => {
                            var jenis = $.map(item.produk_jenis, (item) => {
                                return `<span class="badge badge-outline-success">${item.jenis.jenis}</span>`;
                            }).join(' ')
                            return {
                                text: item.nama,
                                id: item.id,
                                harga: item.harga,
                                label: jenis
                            }
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select',function(e){
            var data = e.params.data;
            $('#form-add-produk-produks_id').val(data.id);
            $('#form-add-produk-produk').val(data.text);
            $('#form-add-produk-harga').val(toFloat(data.harga));
            $('#form-add-produk-produk').val(data.text);
        });

        $('#potongan,#diskon,#biaya_tambahan').keyup(() => {setTotal()});
    });

    function onRemoveHarga(id) {
        $(`#pengaturan_harga-${id}`).remove();
    }

    function onAddProduk(){
        var id_produk_detail = $('#form-add-produk-produks_id').val();
        var nama_produk = $('#form-add-produk-produk').val();
        var jumlah = $('#form-add-produk-jumlah').val();
        var harga = $('#form-add-produk-harga').val();
        var total = $('#form-add-produk-total').val();

        if (id_produk_detail == '') {
            Swal.fire('Produk Belum Diisi');
            return false;
        }
        if (jumlah == '' || jumlah == '0') {
            Swal.fire('Jumlah Produk Harus Diisi');
            return false;
        }

        if ($('#table-produk tbody tr[data-row-id="'+id_produk_detail+'"]').length == 0) {
            var html_row = '<tr data-row-id="'+id_produk_detail+'">';
            html_row += '<td class="align-middle">';
            html_row += '<input type="hidden" name="produk['+id_produk_detail+'][produks_id]" value="'+id_produk_detail+'" id="produk-id_produk-'+id_produk_detail+'">';
            html_row += '<input type="hidden" name="produk['+id_produk_detail+'][nama]" value="'+nama_produk+'" id="produk-id_produk-'+id_produk_detail+'">';
            html_row += nama_produk;
            html_row += '</td>';
            html_row += '<td><input type="text" name="produk['+id_produk_detail+'][harga]" value="'+localization.number(harga)+'"  id="produk-harga-'+id_produk_detail+'" class="form-control input-sm text-right" data-input-type="number-format" onchange="countTotal('+id_produk_detail+')" readonly></td>';
            html_row += '<td><input type="text" name="produk['+id_produk_detail+'][jumlah]" value="'+localization.number(jumlah)+'"  id="produk-jumlah-'+id_produk_detail+'" class="form-control input-sm text-center" data-input-type="number-format" onkeyup="countTotal('+id_produk_detail+')" data-thousand-separator="false"></td>';
            html_row += '<td><input type="text" name="produk['+id_produk_detail+'][total]" value="'+localization.number(total)+'"  id="produk-total-'+id_produk_detail+'" class="form-control input-sm text-right" data-input-type="number-format" readonly></td>';
            html_row += '<td class="text-center"><button type="button" class="btn btn-danger btn-md" onclick="produk_remove('+id_produk_detail+')"><i class="fa fa-minus"></i></button></td>';
            html_row += '</tr>';
            $('#form-add-produk').after(html_row);
            $('#table-produk tbody tr[data-row-id]').buildForm();
            resetForm();
            $('#select2-form-add-produk-nama_produk-container').text('Pilih');
            used.push(id_produk_detail);
        } else {
            Swal.fire('Produk Sudah Ada Di Daftar');
        }
    }

    function produk_remove(id_produk_detail) {
        $('#table-produk tbody tr[data-row-id="'+id_produk_detail+'"]').remove();
        used = $.grep(used, function(value) {
            return value != id_produk_detail;
        });
        countTotal();
    }

    function countTotal(id = false){
        var harga,jumlah,total,grand_total = 0;
        if(id){
            harga = $('#produk-harga-'+id).val();
            jumlah = $('#produk-jumlah-'+id).val();
            total = toFloat(harga) * toFloat(jumlah);
            $('#produk-total-'+id).val(total);
        }else{
            harga = $('#form-add-produk-harga').val();
            jumlah = $('#form-add-produk-jumlah').val();
            total = toFloat(harga) * toFloat(jumlah);
            $('#form-add-produk-total').val(total);
        }
        $.each($("[id^='produk-total-']"), (i, row) => {
            grand_total += toFloat($('#'+row.id).val());
        });
        grand_totals = grand_total + toFloat($('#form-add-produk-total').val());
        $('#subtotal').val(grand_totals).change();
    }

    function setTotal(){
        var subtotal = toFloat($('#subtotal').val());
        var diskon = toFloat($('#diskon').val());
        var potongan = toFloat($('#potongan').val());
        var biaya_tambahan = toFloat($('#biaya_tambahan').val());
        $('#total,#total_biaya').val(subtotal - potongan - (subtotal * diskon / 100) + biaya_tambahan);
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

    function formAddPelanggan(){
        axios.get('{{ route('master.pelanggan.create') }}').then((response) => {
            bootbox.dialog({
                title: 'Tambah Pelanggan',
                message: response.data
            })
        }).catch((error) => {
            console.log(error)
        });
    }

    function store(){
        axios.post('{{ route('master.pelanggan.store') }}', $('#formCreate').serialize()).then((response) => {
            toastr.success('Success', response.data.message);
            $("#pelanggan").append(new Option(response.data.data.nama, response.data.data.id));
            $("#pelanggan option[value=" + response.data.data.id +"]").attr("selected","selected");
            $("#pelanggan").buildForm();
            bootbox.hideAll();
        }).catch((error) => {
            switch (error.response.status) {
                case 422:
                    var response = JSON.parse(error.request.responseText);
                    validation(response);
                    break;
                default:
                    toastr.error('Failed', error.response.data.message);
                    break;
            }
        });
    }

    function submitForm(payment = false){
        if($('#status :selected').val() == 'taken' && payment == false){
            $('#modal-dialog').modal('show');
        }else{
            var message = $('#status :selected').val() == 'taken' ? 'proses tidak dapat dibatalkan' : '';
            confirmDialog('Apakah anda yakin mengirim data ini?', message, () => {
                $('#formTransaksi').submit();
            });
        }
    }

    function checkNomor(){
        let hp = $('#telepon').val();
        let first = hp.substr(0,1);
        if(!hp){
            $('#telepon').addClass('is-invalid');
            $('#groupTelepon .error_message').remove();
            $('#telepon').parent().append(`
                <span class="invalid-feedback error_message nomor" role="alert">
                    <strong>Telepon Harus Diisi</strong>
                </span>`
            );
        }else if(inArray(first, ['0', '-', ']' , '`'])){
            $('#telepon').addClass('is-invalid');
            $('#groupTelepon .error_message').remove();
            $('#telepon').parent().append(`
                <span class="invalid-feedback error_message nomor" role="alert">
                    <strong>Telepont Nomor Salah</strong>
                </span>`
            );
        }else if(hp.length > 15){
            $('#telepon').addClass('is-invalid');
            $('#groupTelepon .error_message').remove();
            $('#telepon').parent().append(`
                <span class="invalid-feedback error_message nomor" role="alert">
                    <strong>Telepon maksimal berisi 15 karakter</strong>
                </span>`
            );
        }else if(hp.length < 10){
            $('#telepon').addClass('is-invalid');
            $('#groupTelepon .error_message').remove();
            $('#telepon').parent().append(`
                <span class="invalid-feedback error_message nomor" role="alert">
                    <strong>Telepon minimal berisi 10 karakter</strong>
                </span>`
            );
        }else{
            $('#telepon').removeClass('is-invalid');
            $('#groupTelepon .error_message').remove();
        }
    }

    function resetForm(){
        $('#form-add-produk-produks_id').val('');
        $('#form-add-produk-nama_produk').val('');
        $('#form-add-produk-produk').val('');
        $('#form-add-produk-jumlah').val('');
        $('#form-add-produk-harga').val('');
        $('#form-add-produk-total').val('');
    }
</script>