<?php

namespace PayGreen\ApiClientBundle\Services\Requesters;

use PayGreen\ApiClientBundle\Entities\Request;
use PayGreen\ApiClientBundle\Interfaces\Requester;

class FopenRequester implements Requester
{
    public function isValid(Request $request): bool
    {
        return ini_get('allow_url_fopen');
    }

    public function send(Request $request) : string
    {
        $content = $request->getContent();

        $opts = [
            'http' => [
                'method'  => $request->getMethod(),
                'header'  => join("\r\n", $request->getHeaders()),
                'content' => $content
            ]
        ];

        $context = stream_context_create($opts);

        return @file_get_contents($request->getFinalUrl(), false, $context);
    }

}