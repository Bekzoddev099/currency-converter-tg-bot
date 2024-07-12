<?php

declare(strict_types=1);

use GuzzleHttp\Client;

$token = "7430675498:AAFFHNpKebcZ-rvwTnsABXrDeAVzSpS5WBI";
$tgApi = "https://api.telegram.org/bot$token/";

$http     = new Client(['base_uri' => $tgApi]);
$currency = new Currency();
if (isset($update->message)) {
    $message          = $update->message;
    $chat_id          = $message->chat->id;
    $type             = $message->chat->type;
    $miid             = $message->message_id;
    $name             = $message->from->first_name;
    $user             = $message->from->username ?? '';
    $fromid           = $message->from->id;
    $text             = $message->text;
    $title            = $message->chat->title;
    $chatuser         = $message->chat->username;
    $chatuser         = $chatuser ? $chatuser : "Shaxsiy Guruh!";
    $caption          = $message->caption;
    $entities         = $message->entities;
    $entities         = $entities[0];
    $left_chat_member = $message->left_chat_member;
    $new_chat_member  = $message->new_chat_member;
    $photo            = $message->photo;
    $video            = $message->video;
    $audio            = $message->audio;
    $voice            = $message->voice;
    $reply            = $message->reply_markup;
    $fchat_id         = $message->forward_from_chat->id;
    $fid              = $message->forward_from_message_id;
}

$input             = explode(':', $text);
$original_currency = $input[0];
$target_currency   = $input[1];
$amount            = (float) $input[2];

$converted_amount = $currency->convert(
    $chat_id,
    $original_currency,
    $target_currency,
    $amount);


$http->post('sendMessage', [
    'form_params' => [
        'chat_id' => $chat_id,
        'text'    => "$converted_amount $target_currency"
    ]
]);