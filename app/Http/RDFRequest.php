<?php

namespace App\Http;

use App\Support\Macros\MacroMixin;
use EasyRdf\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class RDFRequest extends MacroMixin
{
    private const RDFS_FORMATS = ['turtle', 'rdfxml', 'jsonld', 'n3', 'ntriples', 'json', 'php'];

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function wantsRDF(): bool
    {
        return ! is_null($this->rdfFormat());
    }

    public function rdfFormat(): ?Format
    {
        $contentTypes = $this->request->getAcceptableContentTypes();

        if (! isset($contentTypes[0]) || $contentTypes[0] === 'text/html') {
            return null;
        }

        $formats = Cache::remember('ontologies.formats', config('ontologies.cache_ttl'), function () {
            return array_reduce(RDFRequest::RDFS_FORMATS, function ($formats, $name) {
                $format = Format::getFormat($name);
                $mimeTypes = array_keys($format->getMimeTypes());

                foreach ($mimeTypes as $mimeType) {
                    $formats[$mimeType] = $name;
                }

                return $formats;
            }, []);
        });

        foreach ($contentTypes as $contentType) {
            if (! array_key_exists($contentType, $formats)) {
                continue;
            }

            return Format::getFormat($formats[$contentType]);
        }

        return null;
    }

    public function rdfContentType(): ?string
    {
        $format = $this->rdfFormat();

        if (is_null($format)) {
            return null;
        }

        $mimeTypes = $format->getMimeTypes();

        return Arr::first($this->request->getAcceptableContentTypes(), fn ($mimeType) => array_key_exists($mimeType, $mimeTypes))
            ?? $format->getDefaultMimeType();
    }
}
