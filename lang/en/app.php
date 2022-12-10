<?php

return [
    'home' => [
        'title' => "Noel De Martin's Vocabulary",
        'info' => [
            'This is a collection of <a href="https://www.w3.org/RDF/" target="_blank">RDF ontologies</a> I have created for <a href="https://noeldemartin.com/projects" target="_blank">my apps</a>.',
            "When possible, I try to leverage existing ontologies. But it isn't always possible, so this website contains the ones I've had to create from scratch.",
            'These are the ones I have so far:',
        ],
    ],

    'ontologies' => [
        'index' => [
            'classes' => 'These are the classes included in this ontology:',
        ],
        'show' => [
            'full' => 'View full ontology',
        ],
    ],

    'terms' => [
        'class' => [
            'propertyId' => 'Property',
            'propertyRange' => 'Expected type',
            'propertyDescription' => 'Description',
        ],
        'property' => [
            'classes' => 'This property can be found in the following classes:',
        ],
    ],

    'footer' => [
        'home' => 'home',
        'source' => 'source',
    ],
];
