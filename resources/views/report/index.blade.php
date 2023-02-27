@php
    $users = old('user') ? App\Models\User::whereIn('id', old('user'))->pluck('username', 'id') : [];
@endphp
@extends('layouts.app')
@section('content')
<div class="portlet">
    @include('layouts.message')
    <div class="portlet-header">
        <p class="portlet-title">Laporan Transaksi</p>
    </div>
    <div class="portlet-body">
        {!! Form::open(['route' => 'report.download', 'method' => 'get', 'id' => 'formReport']) !!}
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Periode Awal</label>
                            <div class="input-group">
                                {!! Form::text('periode_awal', date('01-m-Y'), ['class' => 'form-control '.add_error($errors, 'periode_awal'), 'id' => 'periodeAwal', 'data-input-type' => 'dateinput', 'onchange' => 'getShiftAktif()']) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                @error('periode_awal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Periode Akhir</label>
                            <div class="input-group">
                                {!! Form::text('periode_akhir', date('d-m-Y'), ['class' => 'form-control '.add_error($errors, 'periode_akhir'), 'id' => 'periodeAkhir', 'data-input-type' => 'dateinput']) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                @error('periode_akhir')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Status</label>
                            {!! Form::select('status', ['' => 'Semua'] +$status, null, ['class' => 'form-control '.add_error($errors, 'status'), 'id' => 'status']) !!}
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>User</label>
                            {!! Form::select('user[]', $users, null, ['class' => 'form-control', 'id' => 'user', 'data-input-type' => 'select2', 'multiple']) !!}
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    {!! Form::submit('Download', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
@push('script')
<script>
    $(() => {
        $("#user").select2({templateResult: (state) => {
                if (state.loading) return "Searching...";
                return $state = $(`<label>${state.text}</label>`)
            },
            ajax: {
                url: '{{route('user.json')}}',
                type: "GET",
                dataType: 'json',
                delay: 500,
                data: function(params) {
                    return {
                        q: params.term,
                        kasir: true,
                        limit: 15,
                    }
                },
                processResults: function (response) {
                    return {
                        results: $.map(response.data, (item) => {
                            return {
                                text: item.username,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    })
</script>
@endpush