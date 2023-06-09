<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qixin;

use Closure;
use Psr\Http\Message\RequestInterface;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\Request;
use JuCloud\EasyOrganization\Rocket;

use function JuCloud\EasyOrganization\get_qixin_base_uri;
use function JuCloud\EasyOrganization\get_qixin_config;
use function JuCloud\EasyOrganization\get_qixin_sign;

abstract class GeneralPlugin implements PluginInterface
{
    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[qixin][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));

        $this->doSomething($rocket);

        Logger::info('[qixin][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

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
        $params = $rocket->getParams();

        $url = EasyOrganization::MODE_SERVICE === (get_qixin_config($params)['mode'] ?? null) ? $this->getPartnerUri($rocket) : $this->getUri($rocket);

        return 0 === strpos($url, 'http') ? $url : (get_qixin_base_uri($params).$url);
    }

    protected function getHeaders(Rocket $rocket): array
    {

        $timestamp = sprintf('%.0f', round(microtime(true) * 1000));

        $rocket->mergePayload([
            'timestamp' => $timestamp
        ]);

        $rocket->mergePayload([
            'sign' => get_qixin_sign($rocket->getParams(), $rocket->getPayload()->all()),
        ]);

        $config = get_qixin_config($rocket->getParams());

        return [
            'Auth-Version' => '2.0',
            'Content-Type' => 'application/json; charset=utf-8',
            'appkey' => $config['app_key'],
            'timestamp' => $timestamp,
            'sign' => $rocket->getPayload()->get('sign')
        ];
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;

    protected function getPartnerUri(Rocket $rocket): string
    {
        return $this->getUri($rocket);
    }
}
