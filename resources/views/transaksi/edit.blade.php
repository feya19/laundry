@extends('layouts.app')
@php
    $sidebar = false;
@endphp
@section('content')
{!! Form::model($model, ['method' => 'PATCH', 'route' => ['transaksi.update', ['transaksi' => $model->id]] ,'id' => 'formTransaksi']) !!}
    @include('transaksi.form')
    <div class="text-right">
        <button type="button" class="btn btn-secondary" onclick="bootbox.hideAll()">Batal</button>
        <button type="button" onclick="submitForm()" class="btn btn-primary">Perbarui</button>
    </div>
{!! Form::close() !!}
@endsection
@push('script')
@include('transaksi.form_script')
@endpush