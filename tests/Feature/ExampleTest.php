<?php

test('returns a successful response', function () {
    $response = $this->get(route('buchungen'));

    $response->assertOk();
});