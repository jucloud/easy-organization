<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Service;

use GuzzleHttp\Client;
use JuCloud\EasyOrganization\Contract\ConfigInterface;
use JuCloud\EasyOrganization\Contract\HttpClientInterface;
use JuCloud\EasyOrganization\Contract\ServiceProviderInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Supports\Config;

class HttpServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function register($data = null): void
    {
        /* @var Config $config */
        $config = EasyOrganization::get(ConfigInterface::class);

        if (class_exists(Client::class)) {
            $service = new Client($config->get('http', []));

            EasyOrganization::set(HttpClientInterface::class, $service);
        }
    }
}
