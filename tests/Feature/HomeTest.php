<?php

test('show ontologies', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Conflict-Free Replicated Datatypes (CRDT)');
});
