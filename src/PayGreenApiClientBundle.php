<?php

namespace PayGreen\ApiClientBundle;

use PayGreen\ApiClientBundle\DependencyInjection\PayGreenApiClientExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PayGreenApiClientBundle extends Bundle
{
    public function __construct()
    {
//        die('Chargement du bundle PayGreenApiClientBundle.');
    }

    public function getContainerExtension()
    {
        return new PayGreenApiClientExtension();
    }
}