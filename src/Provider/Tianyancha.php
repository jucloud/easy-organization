<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Provider;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use JuCloud\EasyOrganization\Event;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\InvalidParamsException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Plugin\Tianyancha\CallbackPlugin;
use JuCloud\EasyOrganization\Plugin\Tianyancha\LaunchPlugin;
use JuCloud\EasyOrganization\Plugin\Tianyancha\PreparePlugin;
use JuCloud\EasyOrganization\Plugin\Tianyancha\RadarSignPlugin;
use JuCloud\EasyOrganization\Plugin\ParserPlugin;
use JuCloud\EasyOrganization\Supports\Collection;
use JuCloud\EasyOrganization\Supports\Str;

/**
 * @method ResponseInterface search(array $param)           搜索
 * @method ResponseInterface baseinfo(string $keyword)      企业工商照面
 */
class Tianyancha extends AbstractProvider
{
    public const URL = [
        EasyOrganization::MODE_NORMAL => 'http://open.api.tianyancha.com/services/open/',
        EasyOrganization::MODE_SANDBOX => 'http://open.api.tianyancha.com/services/open/',
        EasyOrganization::MODE_SERVICE => 'http://open.api.tianyancha.com/services/open/',
    ];

    /**
     * @return null|array|Collection|MessageInterface
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function __call(string $shortcut, array $params)
    {
        $plugin = '\\JuCloud\\EasyOrganization\\Plugin\\Tianyancha\\Shortcut\\' . Str::studly($shortcut).'Shortcut';

        return $this->call($plugin, ...$params);
    }

    /**
     * @param array $order
     *
     * @return array|Collection
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function search($param)
    {
        if (!is_array($param)) {
            throw new InvalidParamsException(Exception::PARAMS_ERROR);
        }

        return $this->__call('search', [$param]);
    }

    /**
     * @param string $keyword
     *
     * @return array|Collection
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function baseinfo($keyword)
    {
        if (!is_array($keyword)) {
            throw new InvalidParamsException(Exception::PARAMS_ERROR);
        }

        return $this->__call('baseinfo', [$keyword]);
    }

    public function mergeCommonPlugins(array $plugins): array
    {
        return array_merge(
            [PreparePlugin::class],
            $plugins,
            [RadarSignPlugin::class],
            [LaunchPlugin::class, ParserPlugin::class],
        );
    }

}
