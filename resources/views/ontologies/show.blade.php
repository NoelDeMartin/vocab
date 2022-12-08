@extends('layouts.main')

@section('title', $term->name)
@section('content')
    <h1 class="text-sky-900">{{ $ontology->name }}</h1>
    <h2 class="mb-1 text-sky-700">{{ $term->name }}</h2>
    <code>{{ $term->id }}</code>
    <p>{{ $term->description }}</p>
    <x-dynamic-component :component="'terms.' . $term->type" :$term />
    <a class="float-right" href="{{ $ontology->route('index') }}">
        @lang('app.ontologies.show.full') →
    </a>
@endsection
