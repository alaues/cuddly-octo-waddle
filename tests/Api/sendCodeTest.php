<?php

namespace Api;

class sendCodeTest extends \TestCase
{

    public function testUnauthorized()
    {
        $url = '/api/sendCode';
        $response = $this->call('POST', $url);
        $this->assertEquals(401, $response->status());
    }

    public function testAuthorizedNoEmail()
    {
        $url = '/api/sendCode';
        $params = [];
        $header = [
            'x-api-key' => env('APP_API_KEY')
        ];

        $response = $this->json('POST', $url, $params, $header);
        $response->assertResponseOk();

        $this->json('POST', $url, $params, $header)
            ->seeJson(['result' => 'error']);

        $this->json('POST', $url, $params, $header)
            ->seeJsonEquals(['result' => 'error', 'message' => 'Invalid email']);
    }
}
