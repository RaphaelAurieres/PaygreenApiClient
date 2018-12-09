<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PayGreen\ApiClientBundle\Entities\Response;
use PayGreen\ApiClientBundle\Entities\Request;

class ResponseTest extends TestCase
{
    public function testBasicUseCase(): void
    {
        $name = 'test';
        $url = 'http://test/url';
        $rawData = '{"key": "val"}';

        /** @var Request $request */
        $request = new Request($name, $url);

        /** @var Response $response */
        $response = new Response($request);

        $response->setRawData($rawData);

        $this->assertEquals(
            $name,
            $response->getRequest()->getName(),
            "getRequest()->getName() must return '$name'."
        );

        $this->assertEquals(
            $rawData,
            $response->getRawData(),
            "getRawData() must return '$rawData'."
        );

        $this->assertEquals(
            true,
            is_object($response->data),
            "data must be an object."
        );

        $this->assertEquals(
            true,
            property_exists($response->data, 'key'),
            "data object must contains 'key' field."
        );

        $this->assertEquals(
            'val',
            $response->data->key,
            "data->key must be equals to 'val'."
        );

        $this->assertEquals(
            json_decode($rawData),
            $response->data,
            "data must equals to decoded value of '$rawData'."
        );
    }
}