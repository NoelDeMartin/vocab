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
    $response = $this->get('/crdt/Metadata');

    $response->assertStatus(200);
    $response->assertSee('Metadata');
    $response->assertSee('Metadata about a resource');
    $response->assertSee('resource');
    $response->assertSee('rdfs:Resource');
    $response->assertSee('Resource which the metadata makes reference to');
    $response->assertSee('createdAt');
    $response->assertSee('xsd:dateTime');
    $response->assertSee('Time at which the resource was created');
});

test('show property', function () {
    $response = $this->get('/crdt/resource');

    $response->assertStatus(200);
    $response->assertSee('resource');
    $response->assertSee('Resource which the metadata makes reference to');
    $response->assertSee('Metadata');
    $response->assertSee('Operation');
});
