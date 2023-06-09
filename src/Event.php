<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization;

use JuCloud\EasyOrganization\Contract\EventDispatcherInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\InvalidConfigException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;

/**
 * @method static Event\Event dispatch(object $event)
 */
class Event
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidConfigException
     */
    public static function __callStatic(string $method, array $args): void
    {
        if (!EasyOrganization::hasContainer() || !EasyOrganization::has(EventDispatcherInterface::class)) {
            return;
        }

        $class = EasyOrganization::get(EventDispatcherInterface::class);

        if ($class instanceof \Psr\EventDispatcher\EventDispatcherInterface) {
            $class->{$method}(...$args);

            return;
        }

        throw new InvalidConfigException(Exception\Exception::EVENT_CONFIG_ERROR);
    }
}
