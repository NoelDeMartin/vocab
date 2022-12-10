@extends('layouts.main')

@section('title', trans('app.home.title'))
@section('content')
    <h1>@lang('app.home.title')</h1>
    @foreach (trans('app.home.info') as $line)
        <p>{!! $line !!}</p>
    @endforeach
    <ul>
        @foreach (Ontologies::all() as $ontology)
            <li>
                <a href="{{ $ontology->route('index') }}">
                    {{ $ontology->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
