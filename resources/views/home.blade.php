@extends('layouts.main')

@section('title', trans('app.home.title'))
@section('content')
    <h1>@lang('app.home.title')</h1>

    <ul>
        @foreach (Ontologies::all() as $ontology)
            <li>
                <a href="{{ route("ontologies.{$ontology->shortId()}.index") }}">
                    {{ $ontology->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
