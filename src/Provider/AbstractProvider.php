<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Provider;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\MessageInterface;
use Throwable;
use JuCloud\EasyOrganization\Contract\HttpClientInterface;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Contract\ProviderInterface;
use JuCloud\EasyOrganization\Contract\ShortcutInterface;
use JuCloud\EasyOrganization\Direction\ArrayDirection;
use JuCloud\EasyOrganization\Event;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\Exception;
use JuCloud\EasyOrganization\Exception\InvalidConfigException;
use JuCloud\EasyOrganization\Exception\InvalidParamsException;
use JuCloud\EasyOrganization\Exception\InvalidResponseException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;
use JuCloud\EasyOrganization\Supports\Pipeline;

use function JuCloud\EasyOrganization\should_do_http_request;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @return null|array|Collection|MessageInterface
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function call(string $plugin, array $params = [])
    {
        if (!class_exists($plugin) || !in_array(ShortcutInterface::class, class_implements($plugin))) {
            throw new InvalidParamsException(Exception::SHORTCUT_NOT_FOUND, "[{$plugin}] is not incompatible");
        }

        /* @var ShortcutInterface $money */
        $money = EasyOrganization::get($plugin);

        $plugins = $money->getPlugins($params);

        if (empty($params['_no_common_plugins'])) {
            $plugins = $this->mergeCommonPlugins($plugins);
        }

        return $this->ready($plugins, $params);
    }

    /**
     * @return null|array|Collection|MessageInterface
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     */
    public function ready(array $plugins, array $params)
    {
        Logger::info('[AbstractProvider] 即将进行 ready 操作', func_get_args());

        Event::dispatch(new Event\QueryStarted($plugins, $params, null));

        $this->verifyPlugin($plugins);

        /* @var Pipeline $pipeline */
        $pipeline = EasyOrganization::make(Pipeline::class);

        /* @var Rocket $rocket */
        $rocket = $pipeline
            ->send((new Rocket())->setParams($params)->setPayload(new Collection()))
            ->through($plugins)
            ->via('assembly')
            ->then(fn ($rocket) => $this->ignite($rocket))
        ;

        Event::dispatch(new Event\QueryFinish($rocket));

        $destination = $rocket->getDestination();

        if (ArrayDirection::class === $rocket->getDirection() && $destination instanceof Collection) {
            return $destination->toArray();
        }

        return $destination;
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidResponseException
     * @throws InvalidConfigException
     */
    public function ignite(Rocket $rocket): Rocket
    {
        if (!should_do_http_request($rocket->getDirection())) {
            return $rocket;
        }

        /* @var HttpClientInterface $http */
        $http = EasyOrganization::get(HttpClientInterface::class);

        if (!$http instanceof ClientInterface) {
            throw new InvalidConfigException(Exception::HTTP_CLIENT_CONFIG_ERROR);
        }

        Logger::info('[AbstractProvider] 准备请求支付服务商 API', $rocket->toArray());

        Event::dispatch(new Event\ApiRequesting($rocket));

        try {
            $response = $http->sendRequest($rocket->getRadar());

            $contents = (string) $response->getBody();

            $rocket->setDestination($response->withBody(Utils::streamFor($contents)))
                ->setDestinationOrigin($response->withBody(Utils::streamFor($contents)))
            ;
        } catch (Throwable $e) {
            Logger::error('[AbstractProvider] 请求支付服务商 API 出错', ['message' => $e->getMessage(), 'rocket' => $rocket->toArray(), 'trace' => $e->getTrace()]);

            throw new InvalidResponseException(Exception::REQUEST_RESPONSE_ERROR, $e->getMessage(), [], $e);
        }

        Logger::info('[AbstractProvider] 请求支付服务商 API 成功', ['response' => $response, 'rocket' => $rocket->toArray()]);

        Event::dispatch(new Event\ApiRequested($rocket));

        return $rocket;
    }

    abstract public function mergeCommonPlugins(array $plugins): array;

    /**
     * @throws InvalidParamsException
     */
    protected function verifyPlugin(array $plugins): void
    {
        foreach ($plugins as $plugin) {
            if (is_callable($plugin)) {
                continue;
            }

            if ((is_object($plugin) || (is_string($plugin) && class_exists($plugin))) && in_array(PluginInterface::class, class_implements($plugin))) {
                continue;
            }

            throw new InvalidParamsException(Exception::PLUGIN_ERROR, "[{$plugin}] is not incompatible");
        }
    }
}
