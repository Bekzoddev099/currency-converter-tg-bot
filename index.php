<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$token = "7430675498:AAFFHNpKebcZ-rvwTnsABXrDeAVzSpS5WBI";

$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$response = $client->post( 'sendMessage', [
    'form_params' => [
        'chat_id' => "863518385",
        'text' => 'HI!'
    ]
]);

$json = $response->getBody()->getContents();

print_r(json_decode($json, true));