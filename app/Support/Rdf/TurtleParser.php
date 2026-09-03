<?php

namespace App\Support\Rdf;

use EasyRdf\Parser\Turtle;

class TurtleParser extends Turtle
{
    /**
     * @return array<string, string>
     */
    public function getNamespaces(): array
    {
        /** @var array<string, string> $namespaces */
        $namespaces = (array) $this->namespaces;

        return $namespaces;
    }
}
