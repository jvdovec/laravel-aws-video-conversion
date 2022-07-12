<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ConversionTest extends TestCase
{
    public function testCanShowUploadForm()
    {
        $response = $this->get('/');

        $response->assertOk();

        $response->assertSeeText('Do Conversion');
    }

    public function testConversionShouldNotStartWithoutFile()
    {
        $response = $this->post('do-conversion');

        $response->assertSeeText('The file field is required');
    }
}
