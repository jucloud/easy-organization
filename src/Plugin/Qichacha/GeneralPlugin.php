<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qichacha;

use Closure;
use Psr\Http\Message\RequestInterface;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Provider\Qichacha;
use JuCloud\EasyOrganization\Request;
use JuCloud\EasyOrganization\Rocket;

use function JuCloud\EasyOrganization\get_qichacha_config;
use function JuCloud\EasyOrganization\get_qichacha_sign;

abstract class GeneralPlugin implements PluginInterface
{
    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[qichacha][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $this->doSomethingBefore($rocket);

        $rocket->setRadar($this->getRequest($rocket));

        $this->doSomething($rocket);

        Logger::info('[qichacha][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getRequest(Rocket $rocket): RequestInterface
    {
        return new Request(
            $this->getMethod(),
            $this->getUrl($rocket),
            $this->getHeaders($rocket),
        );
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getUrl(Rocket $rocket): string
    {
        $url = $this->getUri($rocket);

        if (0 === strpos($url, 'http')) {
            return $url;
        }
        
        $config = get_qichacha_config($rocket->getParams());

        return Qichacha::URL[$config['mode'] ?? EasyOrganization::MODE_NORMAL] . $url;
    }

    protected function getHeaders(Rocket $rocket): array
    {
        $timestamp = time();
        $config = get_qichacha_config($rocket->getParams());

        $rocket->mergePayload([
            'timestamp' => $timestamp
        ]);

        $rocket->mergePayload([
            'token' => get_qichacha_sign($rocket->getParams(), $rocket->getPayload()->all()),
        ]);

        return [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
            'Timespan' => $timestamp,
            'Token' => $rocket->getPayload()->get('token')
        ];
    }

    protected function doSomethingBefore(Rocket $rocket): void
    {
        $config = get_qichacha_config($rocket->getParams());

        $rocket->mergePayload([
            'key' => $config['app_key'] ?? '',
        ]);
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;
}
