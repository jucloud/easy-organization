<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Service;

use Closure;
use Hyperf\Pimple\ContainerFactory as DefaultContainer;
use Hyperf\Utils\ApplicationContext as HyperfContainer;
use Illuminate\Container\Container as LaravelContainer;
use Psr\Container\ContainerInterface;
use JuCloud\EasyOrganization\Contract\ServiceProviderInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\ContainerNotFoundException;
use JuCloud\EasyOrganization\Exception\Exception;
use JuCloud\EasyOrganization\EasyOrganization;

/**
 * @codeCoverageIgnore
 */
class ContainerServiceProvider implements ServiceProviderInterface
{
    private array $detectApplication = [
        'laravel' => LaravelContainer::class,
        'hyperf' => HyperfContainer::class,
    ];

    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        if ($data instanceof ContainerInterface || $data instanceof Closure) {
            EasyOrganization::setContainer($data);

            return;
        }

        if (EasyOrganization::hasContainer()) {
            return;
        }

        foreach ($this->detectApplication as $framework => $application) {
            $method = $framework.'Application';

            if (class_exists($application) && method_exists($this, $method) && $this->{$method}()) {
                return;
            }
        }

        $this->defaultApplication();
    }

    /**
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    protected function laravelApplication(): bool
    {
        EasyOrganization::setContainer(static fn () => LaravelContainer::getInstance());

        EasyOrganization::set(\JuCloud\EasyOrganization\Contract\ContainerInterface::class, LaravelContainer::getInstance());

        if (!EasyOrganization::has(ContainerInterface::class)) {
            EasyOrganization::set(ContainerInterface::class, LaravelContainer::getInstance());
        }

        return true;
    }

    /**
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    protected function hyperfApplication(): bool
    {
        if (!HyperfContainer::hasContainer()) {
            return false;
        }

        EasyOrganization::setContainer(static fn () => HyperfContainer::getContainer());

        EasyOrganization::set(\JuCloud\EasyOrganization\Contract\ContainerInterface::class, HyperfContainer::getContainer());

        if (!EasyOrganization::has(ContainerInterface::class)) {
            EasyOrganization::set(ContainerInterface::class, HyperfContainer::getContainer());
        }

        return true;
    }

    /**
     * @throws ContainerNotFoundException
     */
    protected function defaultApplication(): void
    {
        if (!class_exists(DefaultContainer::class)) {
            throw new ContainerNotFoundException('Init failed! Maybe you should install `hyperf/pimple` first', Exception::CONTAINER_NOT_FOUND);
        }

        $container = (new DefaultContainer())();

        EasyOrganization::setContainer($container);
    }
}
