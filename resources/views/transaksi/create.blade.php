@extends('layouts.app')
@php
    $sidebar = false;
@endphp
@section('content')
{!! Form::open(['method' => 'POST','route' => 'transaksi.store', 'id' => 'formTransaksi']) !!}
    @include('transaksi.form')
    <div class="text-right">
        <button type="button" class="btn btn-secondary" onclick="bootbox.hideAll()">Batal</button>
        <button type="button" onclick="submitForm()" class="btn btn-primary">Tambah</button>
    </div>
{!! Form::close() !!}
@endsection
@push('script')
@include('transaksi.form_script')
@endpush