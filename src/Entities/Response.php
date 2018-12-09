<?php

namespace PayGreen\ApiClientBundle\Entities;


class Response
{
    /** @var string Data in raw format. */
    private $rawData;

    /** @var Request The original request. */
    private $request;

    /** @var object|array Data of the response.*/
    public $data;

    /**
     * Response constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getRawData(): string
    {
        return $this->rawData;
    }

    /**
     * @param string $rawData
     */
    public function setRawData(string $rawData) : self
    {
        $this->rawData = $rawData;

        $this->data = json_decode($rawData);

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest() : Request
    {
        return $this->request;
    }
}