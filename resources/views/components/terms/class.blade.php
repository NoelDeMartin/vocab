@php
    $ontology = $term->ontology;
@endphp

@unless (empty($term->properties))
    <div class="overflow-auto">
        <table class="min-w-table">
            <thead>
                <tr>
                    <th>@lang('app.terms.class.propertyId')</th>
                    <th>@lang('app.terms.class.propertyRange')</th>
                    <th>@lang('app.terms.class.propertyDescription')</th>
                </tr>
            </thead>
            @foreach ($term->properties as $property)
                <tr>
                    <td>
                        <a href="{{ $ontology->route('show', $property->shortId) }}">
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
                                <a href="{{ $class->ontology->route('show', $class->shortId) }}">
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
        </table>
    </div>
@endunless
