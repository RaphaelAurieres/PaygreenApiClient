<?php

namespace PayGreen\ApiClientBundle\Services\Requesters;

use function json_encode;
use PayGreen\ApiClientBundle\Entities\Request;
use PayGreen\ApiClientBundle\Interfaces\Requester;

class CurlRequester implements Requester
{
    public function isValid(Request $request): bool
    {
        return extension_loaded('curl');
    }

    public function send(Request $request) : array
    {
        $ch = curl_init();

        $content = $request->getContent();

        curl_setopt_array($ch, [
            // CURLOPT_SSL_VERIFYPEER => false,
            // CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => $request->getFinalUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_POSTFIELDS => empty($content) ? '' : json_encode($content),
            CURLOPT_HTTPHEADER => $request->getHeaders()
        ]);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}