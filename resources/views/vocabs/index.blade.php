@extends('layouts.main')

@section('title', $vocab->name)
@section('content')
    <h1>{{ $vocab->name }}</h1>

    <ul>
        @foreach ($vocab->classes as $class)
            <li><a href="{{ route("vocabs.{$vocab->name}.show", [$class]) }}">{{ $class }}</a></li>
        @endforeach
    </ul>
@endsection
