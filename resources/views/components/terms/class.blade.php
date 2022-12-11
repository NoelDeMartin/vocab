@php
    $ontology = $term->ontology;
@endphp

<div class="overflow-auto">
    <table class="min-w-table">
        <thead>
            <tr>
                <th>@lang('app.terms.class.propertyId')</th>
                <th>@lang('app.terms.class.propertyRange')</th>
                <th>@lang('app.terms.class.propertyDescription')</th>
            </tr>
        </thead>
        @foreach ($term->hierarchy() as $class)
            @unless ($class === $term)
                <tr>
                    <td colspan="3" class="italic">
                        @markdown('app.terms.class.inherited', [
                            'name' => $class->name,
                            'url' => $term->ontology->route($class),
                        ], true)
                    </td>
                </tr>
            @endunless
            @foreach ($class->properties as $property)
                <tr>
                    <td>
                        <a href="{{ $ontology->route($property) }}">
                            {{ $property->shortId }}
                        </a>
                    </td>
                    <td>
                        @forelse ($property->range as $class)
                            @if ($class->isExtraneous())
                                <a href="{{ $class->id }}" target="_blank">
                                    {{ $class->shortId }}
                                </a>
                            @else
                                <a href="{{ $class->ontology->route($class) }}">
                                    {{ $class->shortId }}
                                </a>
                            @endif

                            @if (!$loop->last)
                                <span class="mx-1">|</span>
                            @endif
                        @empty
                            <code>*</code>
                        @endforelse
                    </td>
                    <td>{{ $property->description }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>
</div>

@unless (empty($term->childClasses))
    @markdown('app.terms.class.children', ['name' => $term->shortId])

    <ul>
        @foreach ($term->childClasses as $childClass)
            <li>
                <a href="{{ $term->ontology->route($childClass) }}">
                    {{ $childClass->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endunless
