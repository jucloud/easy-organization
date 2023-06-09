<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Service;

use JuCloud\EasyOrganization\Contract\ConfigInterface;
use JuCloud\EasyOrganization\Contract\LoggerInterface;
use JuCloud\EasyOrganization\Contract\ServiceProviderInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Supports\Logger;

class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function register($data = null): void
    {
        /* @var ConfigInterface $config */
        $config = EasyOrganization::get(ConfigInterface::class);

        if (class_exists(\Monolog\Logger::class) && true === $config->get('logger.enable', false)) {
            $logger = new Logger(array_merge(
                ['identify' => 'jucloud.organization'],
                $config->get('logger', [])
            ));

            EasyOrganization::set(LoggerInterface::class, $logger);
        }
    }
}
