<?php

test('index works', function () {
    $response = $this->get('/crdt');

    $response->assertStatus(200);
    $response->assertSee('crdt');
    $response->assertSee('Foo');
    $response->assertSee('Bar');
});

test('show works', function () {
    $response = $this->get('/crdt/Foo');

    $response->assertStatus(200);
    $response->assertSee('Foo');
});
