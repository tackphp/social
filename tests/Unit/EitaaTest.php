<?php


namespace Social\Tests\Unit;

use Social\Eitaa\Eitaa;

function getEitaa() {
    return (new Eitaa(new \GuzzleHttp\Client(), token()));
}

const INVALID_CHAT=1000;
$isRealTest = env('EITAA_REAL_TEST');
$chatId = env('EITAA_CHAT_ID_TEST');

if($isRealTest == "true"){
    test("the send message is success", function () use ($chatId) {
        $response = getEitaa()->sendMessage(
            $chatId,
            'Text message',
            'Text title',
            1,
        );
        $body = $response->body();

        expect($body['ok'])->toEqual(true);
        /** result */
        expect($result = $body['result'] ?? '')->toBeArray();
        /** result['message_id'] */
        expect($result['message_id'])->toBeInt();
        /** result['from'] */
        expect($from = $result['from'] ?? '')->toBeArray();
        expect($from['id'])->toBeint();
        expect($from['is_bot'])->toBeBool();
        expect($from['first_name'])->toBeString();
        expect($from['last_name'])->toBeString();
        expect($from['username'])->toBeString();
        expect($from['can_join_groups'])->toBeBool();
        expect($from['can_read_all_group_messages'])->toBeBool();
        expect($from['supports_inline_queries'])->toBeBool();
        /** result['chat'] */
        expect($chat = $result['chat'] ?? '')->toBeArray();
        expect($chat['id'])->toBeNumeric();
        expect($chat['username'])->toBeString();
        expect($chat['type'])->toBeString();
        /** result['date'] */
        expect($result['date'])->toBeInt();
        /** result['text'] */
        expect($result['text'])->toBeString();
    });

    test("the reply of message is successfully", function () use ($chatId) {
        $response = getEitaa()->sendMessage($chatId, 'Text message');
        $responseReply = getEitaa()->sendMessage(
            $chatId,
            'Text message 2',
            replyToMessageId: $response->body()['result']['message_id']
        );
        $body = $responseReply->body();

        expect($body['result']['reply_to_message_id'])->toBeInt();
    });

    test("the send message is failed cause text_id cannot be found", function () use ($chatId) {
        $response = getEitaa()->sendMessage(INVALID_CHAT, 'Text message');
        $body = $response->body();

        expect($body['ok'])->toBeFalse();
        expect($body['error_code'])->toBeInt();
        expect($body['error_code'])->toEqual(400);
        expect($body['description'])->toEqual('Bad Request: chat not found');
    });

    test('the get me is success', function () {
        $response = getEitaa()->getMe();
        $body = $response->body();

        expect($body['ok'])->toEqual(true);
        /** result */
        expect($result = $body['result'] ?? '')->toBeArray();
        expect($result['id'])->toBeint();
        expect($result['is_bot'])->toBeBool();
        expect($result['first_name'])->toBeString();
        expect($result['last_name'])->toBeString();
        expect($result['username'])->toBeString();
        expect($result['can_join_groups'])->toBeBool();
        expect($result['can_read_all_group_messages'])->toBeBool();
        expect($result['supports_inline_queries'])->toBeBool();
    });
}

