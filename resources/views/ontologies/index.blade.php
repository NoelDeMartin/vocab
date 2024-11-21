@extends('layouts.main')

@section('title', $ontology->name)
@section('content')
    <h1 class="mb-1 text-sky-900">{{ $ontology->name }}</h1>
    <code>{{ $ontology->id }}</code>
    {!! Str::markdown($ontology->description) !!}
    <p>@lang('app.ontologies.index.classes')</p>
    <ul>
        @foreach ($ontology->classes as $class)
            <li>
                <a href="{{ $ontology->route($class) }}">
                    {{ $class->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
