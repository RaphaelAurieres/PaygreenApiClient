<?php

namespace PayGreen\ApiClientBundle\Interfaces;

use PayGreen\ApiClientBundle\Entities\Request;

interface Requester
{
    /**
     * @param Request $request
     * @return bool
     */
    public function isValid(Request $request) : bool;

    /**
     * @param Request $request
     * @return mixed
     * @throw Exception
     */
    public function send(Request $request) : array;
}