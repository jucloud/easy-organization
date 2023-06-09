<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qichacha;

use Closure;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\InvalidConfigException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;


use function JuCloud\EasyOrganization\get_qichacha_sign;
use function JuCloud\EasyOrganization\get_qichacha_config;

class RadarSignPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[qichacha][PreparePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->sign($rocket));

        Logger::info('[qichacha][PreparePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
     protected function sign(Rocket $rocket): RequestInterface
     {
         $timestamp = time();
         $config = get_qichacha_config($rocket->getParams());

         $rocket->mergePayload([
             'key' => $config['app_key'] ?? '',
             'timestamp' => $timestamp
         ]);

         $rocket->mergePayload([
             'token' => get_qichacha_sign($rocket->getParams(), $rocket->getPayload()->all()),
         ]);

         $radar = $rocket->getRadar()->withHeader('Content-Type', 'application/json');
         $radar = $radar->withHeader('Timespan', $timestamp);
         $radar = $radar->withHeader('Token', $rocket->getPayload()->get('token'));

         return $radar;
     }
}
