<?php

test('index works', function () {
    $response = $this->get('/crdt');

    $response->assertStatus(200);
    $response->assertSee('crdt');
    $response->assertSee('Metadata');
    $response->assertSee('Property Operation');
});

test('show works', function () {
    $response = $this->get('/crdt/PropertyOperation');

    $response->assertStatus(200);
    $response->assertSee('Property Operation');
});
