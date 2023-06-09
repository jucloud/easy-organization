<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin;

use Closure;
use Psr\Http\Message\ResponseInterface;
use JuCloud\EasyOrganization\Contract\DirectionInterface;
use JuCloud\EasyOrganization\Contract\PackerInterface;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\Exception;
use JuCloud\EasyOrganization\Exception\InvalidConfigException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Rocket;

class ParserPlugin implements PluginInterface
{
    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     * @throws InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        /* @var ResponseInterface $response */
        $response = $rocket->getDestination();

        return $rocket->setDestination(
            $this->getDirection($rocket)->parse($this->getPacker($rocket), $response)
        );
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    protected function getDirection(Rocket $rocket): DirectionInterface
    {
        $packer = EasyOrganization::get($rocket->getDirection());

        $packer = is_string($packer) ? EasyOrganization::get($packer) : $packer;

        if (!$packer instanceof DirectionInterface) {
            throw new InvalidConfigException(Exception::INVALID_PARSER);
        }

        return $packer;
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    protected function getPacker(Rocket $rocket): PackerInterface
    {
        $packer = EasyOrganization::get($rocket->getPacker());

        $packer = is_string($packer) ? EasyOrganization::get($packer) : $packer;

        if (!$packer instanceof PackerInterface) {
            throw new InvalidConfigException(Exception::INVALID_PACKER);
        }

        return $packer;
    }
}
