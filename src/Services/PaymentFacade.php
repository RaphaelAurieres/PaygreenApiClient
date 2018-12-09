<?php

namespace PayGreen\ApiClientBundle\Services;

use PayGreen\ApiClientBundle\Entities\Response;
use PayGreen\ApiClientBundle\Exceptions\PaymentException;
use PayGreen\ApiClientBundle\Exceptions\PaymentRequestException;

class PaymentFacade
{
    /** @var RequestSender */
    private $requestSender;

    /** @var RequestFactory */
    private $requestFactory;

    /**
     * PaymentFacade constructor.
     * @param RequestSender $requestSender
     * @param RequestFactory $requestFactory
     */
    public function __construct(RequestSender $requestSender, RequestFactory $requestFactory)
    {
        $this->requestSender = $requestSender;
        $this->requestFactory = $requestFactory;
    }

    /**
     * To check if private Key and Unique Id are valids
     *
     * @return bool
     */
    public function validIdShop() : bool
    {
        /** @var Response $response */
        $response = $this->requestFactory->buildRequest('are_valid_ids');

        return (bool) $response->data;
    }

    /**
     * Authentication to server paygreen
     *
     * @param string $email
     * @param string $name
     * @param string|null $phone
     * @param string|null $ipAddress
     * @return Response
     * @throws PaymentRequestException
     */
    public function getOAuthServerAccess(string $email, string $name, string $phone = null, string $ipAddress = null)
    {
        $request = $this->requestFactory->buildRequest('oAuth_access')->setContent([
            "ipAddress" => $ipAddress ?: $_SERVER['ADDR'],
            "email" => $email,
            "name" => $name
        ]);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param int $pid
     * @return Response
     * @throws PaymentRequestException
     */
    public function getTransactionInfo(int $pid)
    {
        $request = $this->requestFactory->buildRequest('get_datas', [
            'pid' => $pid
        ]);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * Get Status of the shop
     *
     * @return Response
     * @throws PaymentRequestException
     */
    public function getStatusShop()
    {
        $request = $this->requestFactory->buildRequest('get_data', [
            'type'=>'shop'
        ]);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * Refund an order
     *
     * @param int $pid
     * @param float $amount
     * @return Response
     * @throws PaymentRequestException
     */
    public function refundOrder(int $pid, float $amount)
    {
        if (empty($pid)) {
            return false;
        }

        $request = $this->requestFactory->buildRequest('refund', [
            'pid' => $pid,
            'content' => [
                'amount' => $amount * 100
            ]
        ]);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param $data
     * @return Response
     * @throws PaymentRequestException
     */
    public function sendFingerprintDatas($data)
    {
        $request = $this->requestFactory->buildRequest('send_ccarbone')->setContent($data);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * To validate the shop
     *
     * @param bool $activate
     * @return Response
     * @throws PaymentRequestException
     */
    public function validateShop(bool $activate)
    {
        $request = $this->requestFactory->buildRequest('validate_shop')->setContent([
            'activate' => $activate
        ]);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * Get shop informations
     *
     * @return array
     * @throws PaymentException
     * @throws PaymentRequestException
     */
    public function getAccountInfos()
    {
        $infosAccount = [];

        $request = $this->requestFactory->buildRequest('get_data', [
            'type'=>'account'
        ]);

        $account = $this->requestSender->sendRequest($request);

        if (empty($account)) {
            throw new PaymentException('Account is empty.');
        }

        $infosAccount['siret'] = $account->data->siret;

        $request = $this->requestFactory->buildRequest('get_data', [
            'type' => 'bank'
        ]);

        $bank  = $this->requestSender->sendRequest($request);

        if (empty($bank)) {
            throw new PaymentException('Bank is empty.');
        }

        foreach($bank->data as $rib){
            if($rib->isDefault == "1") {
                $infosAccount['IBAN']  = $rib->iban;
            }
        }

        $request = $this->requestFactory->buildRequest('get_data', [
            'type'=> 'shop'
        ]);

        $shop = $this->requestSender->sendRequest($request);

        if (empty($shop)) {
            throw new PaymentException('Shop is empty.');
        }

        $infosAccount['url'] = $shop->data->url;
        $infosAccount['modules'] = $shop->data->modules;
        $infosAccount['solidarityType'] = $shop->data->extra->solidarityType;

        if(isset($shop->data->businessIdentifier)) {
            $infosAccount['siret'] = $shop->data->businessIdentifier;
        }

        $infosAccount['valide'] = true;

        if (empty($infosAccount['url']) && empty($infosAccount['siret']) && empty($infosAccount['IBAN'])) {
            $infosAccount['valide'] = false;
        }

        return $infosAccount;
    }

    /**
     * Get rounding informations for $paiementToken
     *
     * @param array $data
     * @return Response
     * @throws PaymentRequestException
     */
    public function getRoundingInfo(array $data)
    {
        $request = $this->requestFactory->buildRequest('get_rounding', $data);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param array $data
     * @return Response
     * @throws PaymentRequestException
     */
    public function validateRounding(array $data)
    {
        $request = $this->requestFactory->buildRequest('validate_rounding', $data);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param array $data
     * @return Response
     * @throws PaymentRequestException
     */
    public function refundRounding(array $data)
    {
        $request = $this->requestFactory->buildRequest('refund_rounding', $data)->setContent([
            'paymentToken' => $data['paymentToken']
        ]);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param int $pid
     * @return Response
     * @throws PaymentRequestException
     */
    public function validDeliveryPayment(int $pid)
    {
        $request = $this->requestFactory->buildRequest('delivery', [
            'pid' => $pid
        ]);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param array $data
     * @return Response
     * @throws PaymentRequestException
     */
    public function createCash(array $data)
    {
        $request = $this->requestFactory->buildRequest('create_cash', $data);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param array $data
     * @return Response
     * @throws PaymentRequestException
     */
    public function createXTime(array $data)
    {
        $request = $this->requestFactory->buildRequest('create_xtime', $data);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param array $data
     * @return Response
     * @throws PaymentRequestException
     */
    public function createSubscription(array $data)
    {
        $request = $this->requestFactory->buildRequest('create_subscription', $data);

        return $this->requestSender->sendRequest($request);
    }

    /**
     * @param array $data
     * @return Response
     * @throws PaymentRequestException
     */
    public function createTokenize(array $data)
    {
        $request = $this->requestFactory->buildRequest('create_tokenize', $data);

        return $this->requestSender->sendRequest($request);
    }

}