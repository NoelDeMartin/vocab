@extends('layouts.main')

@section('title', $ontology->name)
@section('content')
    <h1 class="mb-1 text-sky-900">{{ $ontology->name }}</h1>
    <code>{{ $ontology->id }}</code>
    <p>{{ $ontology->description }}</p>
    <p>@lang('app.ontologies.index.classes')</p>
    <ul>
        @foreach ($ontology->classes as $class)
            <li>
                <a href="{{ route("ontologies.{$ontology->shortId()}.show", [$class->shortId()]) }}">
                    {{ $class->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
