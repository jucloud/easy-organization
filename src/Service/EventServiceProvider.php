<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use JuCloud\EasyOrganization\Contract\EventDispatcherInterface;
use JuCloud\EasyOrganization\Contract\ServiceProviderInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\EasyOrganization;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        if (class_exists(EventDispatcher::class)) {
            EasyOrganization::set(EventDispatcherInterface::class, new EventDispatcher());
        }
    }
}
