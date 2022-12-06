<?php

test('index works', function () {
    $response = $this->get('/crdt');

    $response->assertStatus(200);
    $response->assertSee('crdt');
    $response->assertSee('Metadata');
    $response->assertSee('Operation');
});

test('show works', function () {
    $response = $this->get('/crdt/Metadata');

    $response->assertStatus(200);
    $response->assertSee('Metadata');
});
