<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Tianyancha;

use Closure;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\Exception;
use JuCloud\EasyOrganization\Exception\InvalidConfigException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Provider\Tianyancha;
use JuCloud\EasyOrganization\Request;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;
use JuCloud\EasyOrganization\Supports\Str;

use function JuCloud\EasyOrganization\get_tianyancha_config;
use function JuCloud\EasyOrganization\get_private_cert;

class RadarSignPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[tianyancha][RadarSignPlugin] 插件开始装载', ['rocket' => $rocket]);

        Logger::info('[tianyancha][RadarSignPlugin] 插件装载完毕', ['rocket' => $rocket]);
        return $next($rocket);
    }
}
