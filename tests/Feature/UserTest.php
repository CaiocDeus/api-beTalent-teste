<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Test', function () {
    it('/user (GET)', function () {
        $response = $this->get('/user');

        $response->assertStatus(200);
    });

    it('/user (POST)', function () {
        $response = $this->post('/user');

        $response->assertStatus(200);
    });

    it('/user (PUT)', function () {
        $response = $this->put('/user');

        $response->assertStatus(200);
    });

    it('/user (DELETE)', function () {
        $response = $this->delete('/user');

        $response->assertStatus(200);
    });


    $response = $this->get('/');

    $response->assertStatus(200);
});
