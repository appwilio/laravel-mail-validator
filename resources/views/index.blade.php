@extends('layout')

@section('content')
    <div class="row">
        @include('list.validators')
    </div>
    <div class="row">
        @include('forms.upload')
    </div>
@endsection