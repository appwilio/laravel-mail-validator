@extends('layout')
@section('content')
    @if(isset($message))
        <div class="row">
        <pre>
            <pre>
                {{ var_export($message, true) }}
            </pre>
        </pre>
        </div>
    @endif
    @if(isset($added))
        <div class="row">
            <div class="alert alert-info">
                Added <strong>{{$added}}</strong> new emails
            </div>
        </div>
    @endif
    @include("forms.upload")
@endsection