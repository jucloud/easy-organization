<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Service;

use JuCloud\EasyOrganization\Contract\ServiceProviderInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Provider\Qichacha;

class QichachaServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $service = new Qichacha();

        EasyOrganization::set(Qichacha::class, $service);
        EasyOrganization::set('qichacha', $service);
    }
}
