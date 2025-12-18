<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $path = config('database.connections.sqlite.database');

            if (! file_exists($path)) {
                touch($path);
            }
        }
    }
}
