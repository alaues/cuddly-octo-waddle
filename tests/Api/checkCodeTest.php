<?php

namespace Api;

class checkCodeTest extends \TestCase
{
    public function testUnauthorized()
    {
        $url = '/api/checkCode';
        $response = $this->call('POST', $url);
        $this->assertEquals(401, $response->status());
    }

    public function testAuthorizedNoEmail()
    {
        $url = '/api/checkCode';
        $params = [];
        $header = [
            'x-api-key' => env('APP_API_KEY')
        ];

        $response = $this->json('POST', $url, $params, $header);
        $response->assertResponseOk();

        $this->json('POST', $url, $params, $header)
            ->seeJsonEquals(['result' => 'error', 'message' => 'Invalid email']);

        $params = [
            'email' => 'example@domain.com'
        ];
        $this->json('POST', $url, $params, $header)
            ->seeJsonEquals(['result' => 'error', 'message' => 'Invalid code']);
    }
}
