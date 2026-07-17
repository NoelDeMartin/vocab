<?php

test('show ontology', function () {
    $response = $this->get('/crdt/');

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
    $response->assertSee('Operation affecting a resource\'s property', $escaped = false);
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
    $response->assertSee('This property expects values of the following type:');
    $response->assertSee('rdfs:Resource');
    $response->assertSee('Metadata');
    $response->assertSee('Operation');
});

test('show unionOf property', function () {
    $response = $this->get('/crdt/value');

    $response->assertStatus(200);
    $response->assertSee('value');
    $response->assertSee('Property value used by the operation');
    $response->assertSee('Set Property Operation');
    $response->assertSee('Add Property Operation');
    $response->assertSee('Remove Property Operation');
});

test('show solid-extra ontology with orphan properties', function () {
    $response = $this->get('/solid-extra/');

    $response->assertStatus(200);
    $response->assertSee('Solid Extended (solid-extra)');
    $response->assertSee('This ontology defines some extensions to the Solid Protocol');
    $response->assertSee('These are the properties included in this ontology that are not associated with any class:');
    $response->assertSee('deepLastModified');
    $response->assertDontSee('These are the classes included in this ontology:');
});

test('show orphan property with external domain', function () {
    $response = $this->get('/solid-extra/deepLastModified');

    $response->assertStatus(200);
    $response->assertSee('deepLastModified');
    $response->assertSee('Time at which a container or any of its nested documents were last modified.');
    $response->assertSee('xsd:dateTime');
    $response->assertSee('http://www.w3.org/ns/ldp#Container');
});

test('get property rdf', function () {
    $response = $this->withHeaders(['Accept' => 'text/turtle'])->get('/crdt/resource');

    $response->assertStatus(303);
    $response->assertRedirect('/crdt');
});

test('get turtle', function () {
    $response = $this->withHeaders(['Accept' => 'text/turtle'])->get('/crdt/');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/turtle; charset=UTF-8');

    $this->assertStringContainsString('<https://vocab.noeldemartin.com/crdt/>', $response->content());
    $this->assertStringContainsString('a owl:Ontology ;', $response->content());
});

test('get rdfxml', function () {
    $response = $this->withHeaders(['Accept' => 'application/rdf+xml'])->get('/crdt/');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/rdf+xml');

    $this->assertStringContainsString('<owl:Ontology rdf:about="https://vocab.noeldemartin.com/crdt/">', $response->content());
});

test('get jsonld', function () {
    $response = $this->withHeaders(['Accept' => 'application/ld+json'])->get('/crdt/');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/ld+json');

    $this->assertStringContainsString('{"@id":"https://vocab.noeldemartin.com/crdt/"}', $response->content());
});

test('gets most relevant format', function () {
    $response = $this->withHeaders(['Accept' => 'text/html;q=0.7, text/turtle'])->get('/crdt/');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/turtle; charset=UTF-8');

    $this->assertStringContainsString('<https://vocab.noeldemartin.com/crdt/>', $response->content());
    $this->assertStringContainsString('a owl:Ontology ;', $response->content());
});

test('get unsupported format', function () {
    $response = $this->withHeaders(['Accept' => 'image/png'])->get('/crdt/');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    $response->assertSee('Conflict-Free Replicated Datatypes (CRDT)');
});

test('get unknown format', function () {
    $response = $this->withHeaders(['Accept' => 'acme/proprietary'])->get('/crdt/');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    $response->assertSee('Conflict-Free Replicated Datatypes (CRDT)');
});
