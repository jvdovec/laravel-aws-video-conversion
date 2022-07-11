<?php

namespace Tests\Feature;

use Tests\TestCase;

class ConversionTest extends TestCase
{
    public function testCanShowUploadForm()
    {
        $response = $this->get('/');

        $response->assertOk();

        $response->assertSeeText('Do Conversion');
    }
}
