@extends('layouts.main')

@section('title', $ontology->name)
@section('content')
    <h1 class="mb-1 text-sky-900">{{ $ontology->name }}</h1>
    <code>{{ $ontology->id }}</code>
    {!! Str::markdown($ontology->description) !!}

    @unless (empty($ontology->classes))
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
    @endunless

    @unless (empty($ontology->orphanProperties))
        <p>@lang('app.ontologies.index.orphanProperties')</p>
        <ul>
            @foreach ($ontology->orphanProperties as $property)
                <li>
                    <a href="{{ $ontology->route($property) }}">
                        {{ $property->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endunless
@endsection
