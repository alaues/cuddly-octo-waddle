<?php

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->get('sendCode',
        [
            'uses' => 'CodeController@send',
            'middleware' => ['apiKey', 'sendCode']
        ]
    );

    $router->post('checkCode',
        [
            'uses' => 'CodeController@check',
            'middleware' => ['apiKey', 'checkCode']
        ]
    );

});
