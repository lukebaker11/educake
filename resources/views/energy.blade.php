@extends('layouts.app')

@section('content')

<energy-list :data="{{ json_encode($data) }}"
             form-route="{{ route('dates') }}">
</energy-list>

@endsection
