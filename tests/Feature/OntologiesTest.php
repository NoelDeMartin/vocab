<?php

test('show ontology', function () {
    $response = $this->get('/crdt');

    $response->assertStatus(200);
    $response->assertSee('Conflict-Free Replicated Datatypes (CRDT)');
    $response->assertSee('The Conflict-Free Replicated Datatypes (CRDT) ontology');
    $response->assertSee('Metadata');
    $response->assertSee('Property Operation');
    $response->assertSee('Remove Property Operation');
    $response->assertSee('Tombstone');
});

test('show class', function () {
    $response = $this->get('/crdt/PropertyOperation');

    $response->assertStatus(200);
    $response->assertSee('Property Operation');
    $response->assertSee('Operation affecting a resource\'s property');
    $response->assertSee('property');
    $response->assertSee('rdf:Property');
    $response->assertSee('Property affected by the operation');
    $response->assertSeeText('Properties inherited from Operation');
    $response->assertSee('resource');
    $response->assertSee('rdfs:Resource');
    $response->assertSee('Resource which the metadata makes reference to');
    $response->assertSee('date');
    $response->assertSee('xsd:dateTime');
    $response->assertSee('Time at which the operation was performed');
    $response->assertSeeText('Classes that extend PropertyOperation');
    $response->assertSee('Set Property Operation');
    $response->assertSee('Unset Property Operation');
    $response->assertSee('Add Property Operation');
    $response->assertSee('Remove Property Operation');
});

test('show property', function () {
    $response = $this->get('/crdt/resource');

    $response->assertStatus(200);
    $response->assertSee('resource');
    $response->assertSee('Resource which the metadata makes reference to');
    $response->assertSee('Metadata');
    $response->assertSee('Operation');
});
