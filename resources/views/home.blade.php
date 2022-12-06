@extends('layouts.main')

@section('title', trans('app.home.title'))
@section('content')
    <h1>@lang('app.home.title')</h1>

    <ul>
        @foreach (Vocabs::all() as $vocab)
            <li><a href="{{ route("vocabs.{$vocab->name}.index") }}">{{ $vocab->name }}</a></li>
        @endforeach
    </ul>
@endsection
