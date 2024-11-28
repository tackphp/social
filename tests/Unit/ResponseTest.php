<?php


namespace Social\Tests\Unit;

use GuzzleHttp\Psr7\Response as Psr7Response;
use Mockery;
use Psr\Http\Message\StreamInterface;
use Social\Response;

/** Mock */
$streamInterface = Mockery::mock(StreamInterface::class);
$streamInterface->shouldReceive('getContents')->andReturn(
    json_encode([
        'ok' => true,
        'result' => [
            'success' => true,
            'message' => 'successfully',
        ],
    ])
);

$psr7response = Mockery::mock(Psr7Response::class);
$psr7response->shouldReceive('getBody')->andReturn($streamInterface);

test('the body method of response is worked', function () use ($psr7response) {
   $response = new Response($psr7response);
   $body = $response->body();

   expect($body['ok'])->toEqual(true);
   expect($result = $body['result'] ?? '')->toBeArray();
   expect($result['success'])->toEqual(true);
   expect($result['message'])->toEqual('successfully');
});

