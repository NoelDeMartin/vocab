<?php

namespace App\Support\Rdf;

use EasyRdf\Parser\Turtle;

class TurtleParser extends Turtle
{
    /**
     * @return string[]
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }
}
