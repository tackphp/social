<?php

namespace Social;

use GuzzleHttp\Psr7\Response as Psr7Response;

class Response
{
    public  function __construct(private Psr7Response $response)
    {
        //
    }

    public function body()
    {
        $contents = $this->response->getBody()->getContents();

        if(is_string($contents)) {
            $contents = json_decode($contents, true);
        }

        return $contents;
    }
}