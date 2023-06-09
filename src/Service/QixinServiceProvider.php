<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Service;

use JuCloud\EasyOrganization\Contract\ServiceProviderInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Provider\Qixin;

class QixinServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $service = new Qixin();

        EasyOrganization::set(Qixin::class, $service);
        EasyOrganization::set('qixin', $service);
    }
}
