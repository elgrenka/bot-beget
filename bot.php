<?php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
//file_put_contents('message.txt', print_r($data, true));

$data    = $data['message'];
$message = $data['text'];
$method    = 'sendMessage';

$send_data = match ($message) {
    '/start' => [
        'text' => 'Привет!',
    ],
    default => [
        'text' => "Вы написали: $message",
    ],
};

$send_data['chat_id'] = $data['chat']['id'];

sendTelegram($method, $send_data);

function sendTelegram($method, $data, $headers = []) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_POST           => true,
        CURLOPT_HEADER         => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL            => 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/' . $method,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => array_merge(["Content-Type: application/json"], $headers),
    ]);

    $result = curl_exec($curl);
    curl_close($curl);

    return (json_decode($result, true) ?? $result);
}


