@extends('layouts.main')

@section('title', trans('app.home.title'))
@section('content')
    <h1>@lang('app.home.title')</h1>
    @markdown('app.home.info')
    <ul>
        @foreach (Ontologies::all() as $ontology)
            <li>
                <a href="{{ $ontology->route() }}">
                    {{ $ontology->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
