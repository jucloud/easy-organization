<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qixin;

use Closure;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\Exception;
use JuCloud\EasyOrganization\Exception\InvalidConfigException;
use JuCloud\EasyOrganization\Exception\InvalidParamsException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;
use JuCloud\EasyOrganization\Supports\Str;

use function JuCloud\EasyOrganization\get_qixin_sign;
use function JuCloud\EasyOrganization\get_qixin_config;

class RadarSignPlugin implements PluginInterface
{

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
     public function assembly(Rocket $rocket, Closure $next): Rocket
     {
         Logger::debug('[qixin][RadarSignPlugin] 插件开始装载', ['rocket' => $rocket]);

         $rocket->setRadar($this->sign($rocket));

         Logger::info('[qixin][RadarSignPlugin] 插件装载完毕', ['rocket' => $rocket]);

         return $next($rocket);
     }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     * @throws \Exception
     */
    protected function sign(Rocket $rocket): RequestInterface
    {

        $timestamp = sprintf('%.0f', round(microtime(true) * 1000));

        $rocket->mergePayload([
            'timestamp' => $timestamp
        ]);

        $rocket->mergePayload([
            'sign' => get_qixin_sign($rocket->getParams(), $rocket->getPayload()->all()),
        ]);

        $config = get_qixin_config($rocket->getParams());

        $radar = $rocket->getRadar()->withHeader('Content-Type', 'application/json');
        $radar = $radar->withHeader('Auth-version', '2.0');
        $radar = $radar->withHeader('appkey', $config['app_key']);
        $radar = $radar->withHeader('timestamp', $timestamp);
        $radar = $radar->withHeader('sign', $rocket->getPayload()->get('sign'));

        return $radar;
    }
}
