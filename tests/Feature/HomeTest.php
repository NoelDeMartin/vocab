<?php

it('shows ontologies', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Conflict-Free Replicated Datatypes (CRDT)');
    $response->assertSee('Solid Extended (solid-extra)');
});
