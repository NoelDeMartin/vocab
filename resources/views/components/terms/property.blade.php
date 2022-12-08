@unless (empty($term->domain))
    <p>@lang('app.terms.property.classes')</p>

    <ul>
        @foreach ($term->domain as $class)
            <li>
                <a href="{{ $term->ontology->route('show', $class->shortId) }}">
                    {{ $class->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endunless
