@extends('layouts.app')
@php
    $sidebar = false;
    $header = false;
@endphp
@section('content')
    <div class="container mt-5">
        <div class="portlet">
            @include('layouts.message')
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-4">
                        <h3 class="content-title m-t-0"><i class="fa fa-user"></i> Profil</h3>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['route' => 'changeProfile' , 'id' => 'formProfil', 'method' => 'POST', 'enctype' => "multipart/form-data"]) !!}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-5">
                                        @php
                                            $alt_img = '<div class="avatar bg-purple widget13-avatar" id="img-alt">
                                                            <div class="avatar-display">
                                                                <i class="fa fa-user-alt"></i>
                                                            </div>
                                                        </div>
                                                        <img src="" alt="" id="img-photo" class="d-none" style="max-width: 100px">';
                                            if(isset(auth()->user()->photo)){
                                                $path = "upload/profile/".auth()->user()->photo;
                                                if(file_exists(public_path($path))){
                                                    echo '<img src="'.asset($path).'" alt="" id="img-photo" style="max-width: 100px">';
                                                }else{
                                                    echo $alt_img;
                                                }
                                            }else{
                                                echo $alt_img;
                                            }
                                        @endphp
                                    </div>
                                    <div class="col-md-7">
                                        {!! Form::file('file', ['class' => 'd-none file-upload-input ', 'id' => 'FotoProfil', 'accept' => 'image/*', 'style' => 'max-width: 250px;' ]) !!}
                                        <button type="button" class="btn btn-secondary text-center" onclick="browse_file()">Pilih File</button>
                                        <span id="pilih">Tidak ada file yang dipilih</span>
                                        @error('file')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama <span class="text-danger">*</span></label>
                                {!! Form::text('name', $user['name'], ['class' => 'form-control '.add_error($errors, 'name'), 'id' => 'Name']) !!}
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <h3 class="content-title m-t-0"><i class="fa fa-lock"></i> Keamanan</h3>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['route' => 'changePassword' , 'id' => 'formPassword', 'method' => 'POST']) !!}            
                            <div class="form-group">
                                <label for="oldPw">Password Lama</label>
                                {!! Form::password('oldpassword', ['class' => 'form-control '.add_error($errors, 'oldpassword'), 'id' => 'oldPw']) !!}
                                @error('oldpassword')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="newPw">Password Baru</label>
                                {!! Form::password('newpassword', ['class' => 'form-control '.add_error($errors, 'newpassword'), 'id' => 'newPw']) !!}
                                @error('newpassword')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="confrimPw">Konfirmasi Password Baru</label>
                                {!! Form::password('confirmed', ['class' => 'form-control'.add_error($errors, 'confirmed'), 'id' => 'confirmPw']) !!}
                                @error('confirmed')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Ubah Password</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script>
    $(function() {
        $('#FotoProfil').change(function() {
            var reader = new FileReader();
            reader.onload = function (e) {
                if(e.total > 2048000){
                    alertDialog('Ukuran file tidak boleh melebihi 2 MB');
                    return false;
                }
                $('#img-photo').prop('src', e.target.result).removeClass('d-none');
                $('#img-alt,#pilih').addClass('d-none');
            }
            reader.readAsDataURL($('#FotoProfil')[0].files[0]);
        });
    });

    function browse_file() {
        $('#FotoProfil').click();
    }
</script>
@endpush
