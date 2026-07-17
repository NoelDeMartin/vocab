@unless (empty($term->range))
    <p>@lang('app.terms.property.range')</p>

    <ul>
        @foreach ($term->range as $class)
            <li>
                @if ($class->isExtraneous())
                    <a href="{{ $class->id }}" target="_blank">
                        {{ $class->shortId }}
                    </a>
                @else
                    <a href="{{ $term->ontology->route($class) }}">
                        {{ $class->name }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
@endunless

@unless (empty($term->domain))
    <p>@lang('app.terms.property.classes')</p>

    <ul>
        @foreach ($term->domain as $class)
            <li>
                @if (is_string($class))
                    <a href="{{ $class }}" target="_blank">
                        {{ $class }}
                    </a>
                @else
                    <a href="{{ $term->ontology->route($class) }}">
                        {{ $class->name }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
@endunless
