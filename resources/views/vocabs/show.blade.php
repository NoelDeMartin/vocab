@extends('layouts.main')

@section('title', $term)
@section('content')
    <h1>{{ $term }}</h1>
    <a href="{{ route("vocabs.{$vocab->name}.index") }}">@lang('app.show.back')</a>
@endsection
