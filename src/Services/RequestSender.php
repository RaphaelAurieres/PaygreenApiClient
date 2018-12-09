<?php

namespace PayGreen\ApiClientBundle\Services;

use Exception;
use PayGreen\ApiClientBundle\Entities\Request;
use PayGreen\ApiClientBundle\Entities\Response;
use PayGreen\ApiClientBundle\Exceptions\PaymentRequestException;
use PayGreen\ApiClientBundle\Interfaces\Requester;
use Psr\Log\LoggerInterface;

class RequestSender
{
    /** @var LoggerInterface|null Service to log requests, responses and errors. */
    private $logger = null;

    /** @var array List of available requesters. */
    private $requesters = [];

    /**
     * RequestSender constructor.
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param Requester $requester
     */
    public function addRequesters(Requester $requester): void
    {
        $this->requesters[] = $requester;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws PaymentRequestException
     */
    public function sendRequest(Request $request) : Response
    {
        $this->log('Sending a payment response.', $request);

        /** @var Response $response */
        $response = new Response($request);

        try {
            /** @var Requester $requester */
            foreach ($this->requesters as $requester) {
                if (!$request->isSent() && $requester->isValid($request)) {
                    /** @var string $data */
                    $data = $requester->send($request);

                    $request->markAsSent();

                    $response->setRawData($data);
                }
            }
        } catch (Exception $exception) {
            throw new PaymentRequestException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $this->log('Receive a payment response.', $request, $response);

        return $response;
    }

    /**
     * @param string $message
     * @param Request $request
     * @param Response|null $response
     */
    private function log(string $message, Request $request, Response $response = null)
    {
        if ($this->logger !== null) {
            $data = [
                'type' => $request->getName(),
                'method' => $request->getMethod(),
                'headers' => $request->getHeaders(),
                'parameters' => $request->getParameters(),
                'raw_url' => $request->getRawUrl(),
                'final_url' => $request->getFinalUrl()
            ];

            if ($response !== null) {
                $data = array_merge($data, [
                    'data' => $response->getData(),
                    'error' => $response->isError()
                ]);
            }

            $this->logger->info($message, $data);
        }
    }


}