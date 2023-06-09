<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization;

use Closure;
use Throwable;
use Illuminate\Container\Container as LaravelContainer;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use JuCloud\EasyOrganization\Contract\DirectionInterface;
use JuCloud\EasyOrganization\Contract\PackerInterface;
use JuCloud\EasyOrganization\Contract\ServiceProviderInterface;
use JuCloud\EasyOrganization\Direction\CollectionDirection;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\ContainerNotFoundException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Packer\JsonPacker;
use JuCloud\EasyOrganization\Provider\Tianyancha;
use JuCloud\EasyOrganization\Provider\Qichacha;
use JuCloud\EasyOrganization\Provider\Qixin;
use JuCloud\EasyOrganization\Service\TianyanchaServiceProvider;
use JuCloud\EasyOrganization\Service\QichachaServiceProvider;
use JuCloud\EasyOrganization\Service\QixinServiceProvider;
use JuCloud\EasyOrganization\Service\ConfigServiceProvider;
use JuCloud\EasyOrganization\Service\ContainerServiceProvider;
use JuCloud\EasyOrganization\Service\EventServiceProvider;
use JuCloud\EasyOrganization\Service\HttpServiceProvider;
use JuCloud\EasyOrganization\Service\LoggerServiceProvider;

/**
 * @method static Tianyancha tianyancha(array $config = [], $container = null)
 * @method static Qichacha qichacha(array $config = [], $container = null)
 * @method static Qixin qixin(array $config = [], $container = null)
 */
class EasyOrganization
{
    /**
     * 正常模式.
     */
    public const MODE_NORMAL = 0;

    /**
     * 沙箱模式.
     */
    public const MODE_SANDBOX = 1;

    /**
     * 服务商模式.
     */
    public const MODE_SERVICE = 2;

    public const ENTITY_TYPE_ENTERPRICE     = 0;  //大陆企业
    public const ENTITY_TYPE_ASSOCIATION    = 1;  //社会组织
    public const ENTITY_TYPE_HKENTERPRICE   = 3;  //中国香港公司
    public const ENTITY_TYPE_INSTITUTION    = 4;  //事业单位
    public const ENTITY_TYPE_TWENTERPRICE   = 5;  //中国台湾公司
    public const ENTITY_TYPE_FOUNDACTION    = 6;  //基金会
    public const ENTITY_TYPE_HOSPITAL       = 7;  //医院
    public const ENTITY_TYPE_OVERSEA        = 8;  //海外公司
    public const ENTITY_TYPE_LAWFIRM        = 9;  //律师事务所
    public const ENTITY_TYPE_SCHOOL         = 10; //学校
    public const ENTITY_TYPE_GOVERNMENT     = 11; //机关单位
    public const ENTITY_TYPE_OTHER          = -1; //其他


	public static $entityTypeMap = [
        self::ENTITY_TYPE_ENTERPRICE     =>  '大陆企业',
        self::ENTITY_TYPE_ASSOCIATION    =>  '社会组织',
        self::ENTITY_TYPE_HKENTERPRICE   =>  '中国香港公司',
        self::ENTITY_TYPE_INSTITUTION    =>  '事业单位',
        self::ENTITY_TYPE_TWENTERPRICE   =>  '中国台湾公司',
        self::ENTITY_TYPE_FOUNDACTION    =>  '基金会',
        self::ENTITY_TYPE_HOSPITAL       =>  '医院',
        self::ENTITY_TYPE_OVERSEA        =>  '海外公司',
        self::ENTITY_TYPE_LAWFIRM        =>  '律师事务所',
        self::ENTITY_TYPE_SCHOOL         =>  '学校',
        self::ENTITY_TYPE_GOVERNMENT     =>  '机关单位',
        self::ENTITY_TYPE_OTHER          =>  '其他',
	];

    /**
     * @var string[]
     */
    protected array $service = [
        TianyanchaServiceProvider::class,
        QichachaServiceProvider::class,
        QixinServiceProvider::class,
    ];

    /**
     * @var string[]
     */
    private array $coreService = [
        ContainerServiceProvider::class,
        ConfigServiceProvider::class,
        LoggerServiceProvider::class,
        EventServiceProvider::class,
        HttpServiceProvider::class,
    ];

    /**
     * @var null|Closure|ContainerInterface
     */
    private static $container;

    /**
     * @param null|Closure|ContainerInterface $container
     *
     * @throws ContainerException
     */
    private function __construct(array $config, $container = null)
    {
        $this->registerServices($config, $container);

        EasyOrganization::set(DirectionInterface::class, CollectionDirection::class);
        EasyOrganization::set(PackerInterface::class, JsonPacker::class);
    }

    /**
     * @return mixed
     *
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public static function __callStatic(string $service, array $config)
    {
        if (!empty($config)) {
            self::config(...$config);
        }

        return self::get($service);
    }

    /**
     * @param null|Closure|ContainerInterface $container
     *
     * @throws ContainerException
     */
    public static function config(array $config = [], $container = null): bool
    {
        if (self::hasContainer() && !($config['_force'] ?? false)) {
            return false;
        }

        new self($config, $container);

        return true;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $value
     *
     * @throws ContainerException
     */
    public static function set(string $name, $value): void
    {
        try {
            $container = EasyOrganization::getContainer();

            if ($container instanceof LaravelContainer) {
                $container->singleton($name, $value instanceof Closure ? $value : static fn () => $value);

                return;
            }

            if (method_exists($container, 'set')) {
                $container->set(...func_get_args());

                return;
            }
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException($e->getMessage());
        }

        throw new ContainerException('Current container does NOT support `set` method');
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     *
     * @throws ContainerException
     */
    public static function make(string $service, array $parameters = [])
    {
        try {
            $container = EasyOrganization::getContainer();

            if (method_exists($container, 'make')) {
                return $container->make(...func_get_args());
            }
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException($e->getMessage());
        }

        $parameters = array_values($parameters);

        return new $service(...$parameters);
    }

    /**
     * @return mixed
     *
     * @throws ServiceNotFoundException
     * @throws ContainerException
     */
    public static function get(string $service)
    {
        try {
            return EasyOrganization::getContainer()->get($service);
        } catch (NotFoundExceptionInterface $e) {
            throw new ServiceNotFoundException($e->getMessage());
        } catch (ContainerNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException($e->getMessage());
        }
    }

    /**
     * @throws ContainerNotFoundException
     */
    public static function has(string $service): bool
    {
        return EasyOrganization::getContainer()->has($service);
    }

    /**
     * @param null|Closure|ContainerInterface $container
     */
    public static function setContainer($container): void
    {
        self::$container = $container;
    }

    /**
     * @throws ContainerNotFoundException
     */
    public static function getContainer(): ContainerInterface
    {
        if (self::$container instanceof ContainerInterface) {
            return self::$container;
        }

        if (self::$container instanceof Closure) {
            return (self::$container)();
        }

        throw new ContainerNotFoundException('`getContainer()` failed! Maybe you should `setContainer()` first', Exception\Exception::CONTAINER_NOT_FOUND);
    }

    public static function hasContainer(): bool
    {
        return self::$container instanceof ContainerInterface || self::$container instanceof Closure;
    }

    public static function clear(): void
    {
        self::$container = null;
    }

    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public static function registerService(string $service, $data): void
    {
        $var = new $service();

        if ($var instanceof ServiceProviderInterface) {
            $var->register($data);
        }
    }

    /**
     * @param null|Closure|ContainerInterface $container
     *
     * @throws ContainerException
     */
    private function registerServices(array $config, $container = null): void
    {
        foreach (array_merge($this->coreService, $this->service) as $service) {
            self::registerService($service, ContainerServiceProvider::class == $service ? $container : $config);
        }
    }
}
