<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Tianyancha;

use Closure;
use Psr\Http\Message\RequestInterface;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Request;
use JuCloud\EasyOrganization\Rocket;

use function JuCloud\EasyOrganization\get_tianyancha_base_uri;
use function JuCloud\EasyOrganization\get_tianyancha_config;

abstract class GeneralPlugin implements PluginInterface
{
    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[tianyancha][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));

        $this->doSomething($rocket);

        Logger::info('[tianyancha][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

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
            $this->getHeaders($rocket->getParams()),
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

        $url = EasyOrganization::MODE_SERVICE === (get_tianyancha_config($params)['mode'] ?? null) ? $this->getPartnerUri($rocket) : $this->getUri($rocket);

        return 0 === strpos($url, 'http') ? $url : (get_tianyancha_base_uri($params).$url);
    }

    protected function getHeaders($params): array
    {
        $config = get_tianyancha_config($params);

        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => $config['token']
        ];
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;

    protected function getPartnerUri(Rocket $rocket): string
    {
        return $this->getUri($rocket);
    }
}
