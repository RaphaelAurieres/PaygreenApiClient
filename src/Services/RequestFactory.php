<?php

namespace PayGreen\ApiClientBundle\Services;

use PayGreen\ApiClientBundle\Entities\Request;
use Symfony\Component\Yaml\Yaml;

class RequestFactory
{
    /** @var array List of request definitions. */
    private $requestDefinition = [];

    /** @var array List of common headers shared between all requests. */
    private $sharedHeaders = [];

    /** @var array List of common parameters shared between all requests. */
    private $sharedParameters = [];

    /**
     * RequestFactory constructor.
     * @param array $sharedHeaders
     * @param array $sharedParameters
     */
    public function __construct(array $sharedHeaders = [], array $sharedParameters = [])
    {
        $this->sharedHeaders = $sharedHeaders;
        $this->sharedParameters = $sharedParameters;

        $config = Yaml::parseFile(__DIR__ . '/../resources/config/requests.yaml');

        $this->requestDefinition = $config['requests'] ?? [];
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return Request
     */
    public function buildRequest(string $name, array $parameters = [])
    {
        if(!array_key_exists($name, $this->requestDefinition)) {
            throw new \LogicException("Unknown request type : '$name'.");
        }

        return (new Request($name, $this->requestDefinition[$name]['url']))
            ->setMethod($this->requestDefinition[$name]['method'] ?? 'GET')
            ->addHeaders($this->sharedHeaders)
            ->addHeaders($this->requestDefinition[$name]['headers'] ?? [])
            ->setParameters(array_merge($this->sharedParameters, $parameters))
        ;
    }
}