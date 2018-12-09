<?php

namespace PayGreen\ApiClientBundle\Entities;


class Request
{
    /** @var string Name of the request. */
    private $name;

    /** @var string Method of the request. */
    private $method = 'GET';

    /** @var array List of request headers. */
    private $headers = [];

    /** @var string URL of the request. */
    private $url;

    /** @var array List of request parameters. */
    private $parameters = [];

    /** @var array Content values. */
    private $content = [];

    /** @var bool If request is already sent. */
    private $sent = false;

    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @param array $parameters
     * @return Request
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param array $headers
     * @return Request
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param array $headers
     * @return Request
     */
    public function addHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * @param string $method
     * @return Request
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param array $content
     */
    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getRawUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getFinalUrl(): string
    {
        $url = $this->url;

        if (preg_match_all('/({(?<keys>[A-Z-_]+)})/i', $url, $results)) {
            foreach($results['keys'] as $key) {
                if (!array_key_exists($key, $this->parameters)) {
                    throw new \LogicException("Unable to retrieve parameter : '$key'.");
                }

                $url = preg_replace('/{' . $key . '}/i', $this->parameters[$key], $url);
            }
        }

        return $url;
    }

    /**
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getContent() : array
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isSent() : bool
    {
        return $this->sent;
    }

    /**
     * @return Request
     */
    public function markAsSent() : self
    {
        $this->sent = true;

        return $this;
    }
}