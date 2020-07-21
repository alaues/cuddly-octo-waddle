<?php

namespace Api;

use App\Models\EmailAddress;

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

    public function testCheckWrongCode()
    {
        $email = 'example@domain.com';
        $code = 1234;

        factory('App\Models\EmailAddress')->create(
            [
                'email' => $email,
                'code' => $code
            ]
        );

        $model = EmailAddress::where('email', $email)->first();

        $model->checkCode(1235);
        $model = EmailAddress::find($model->id);
        $this->assertEquals(1, $model->check_counter);

        $model->checkCode(1235);
        $model = EmailAddress::find($model->id);
        $this->assertEquals(2, $model->check_counter);

        $model->checkCode(1235);
        $model = EmailAddress::find($model->id);
        $this->assertEmpty($model);

    }

    public function testCheckCorrectCode()
    {
        $email = 'example@domain.com';
        $code = 1234;

        factory('App\Models\EmailAddress')->create(
            [
                'email' => $email,
                'code' => $code
            ]
        );

        $model = EmailAddress::where('email', $email)->first();

        $model->checkCode($code);
        $model = EmailAddress::find($model->id);

        $this->assertEmpty($model);
    }
}
