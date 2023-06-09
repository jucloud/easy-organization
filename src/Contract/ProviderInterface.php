<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Contract;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\InvalidParamsException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Supports\Collection;

interface ProviderInterface
{
    /**
     * @return null|array|Collection|MessageInterface
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function ready(array $plugins, array $params);

    /**
     * @param array|string $order
     *
     * @return array|Collection
     */
    public function search($order);
}
