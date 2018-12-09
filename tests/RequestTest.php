<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PayGreen\ApiClientBundle\Entities\Request;

class RequestTest extends TestCase
{
    public function testBasicUseCase(): void
    {
        $name = 'test1';
        $url = 'http://test/url';

        /** @var Request $request */
        $request = new Request($name, $url);

        $this->assertEquals(
            $name,
            $request->getName(),
            "getName() must return '$name'."
        );

        $this->assertEquals(
            $url,
            $request->getRawUrl(),
            "getRawUrl() must return '$url'."
        );

        $this->assertEquals(
            $url,
            $request->getFinalUrl(),
            "getFinalUrl() must return '$url'."
        );
    }

    public function testParametersUseCase(): void
    {
        $name = 'test2';
        $url = 'http://test/url/{id}';
        $finalUrl = 'http://test/url/1';
        $parameters = ['id' => 1];

        /** @var Request $request */
        $request = new Request($name, $url);

        $request->setParameters($parameters);

        $this->assertEquals(
            $name,
            $request->getName(),
            "getName() must return '$name'."
        );

        $this->assertEquals(
            $parameters,
            $request->getParameters(),
            "getParameters() must return ['id' => 1]."
        );

        $this->assertEquals(
            $url,
            $request->getRawUrl(),
            "getRawUrl() must return '$url'."
        );

        $this->assertEquals(
            $finalUrl,
            $request->getFinalUrl(),
            "getFinalUrl() must return '$finalUrl'."
        );
    }
}