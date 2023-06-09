<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Contract;

use JuCloud\EasyOrganization\Exception\ContainerException;

interface ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void;
}
