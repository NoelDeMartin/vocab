<?php

namespace App\Models;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 */
interface OntologyTerm
{
    public function shortId(): string;
}
