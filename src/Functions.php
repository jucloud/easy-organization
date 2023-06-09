<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use JuCloud\EasyOrganization\Contract\ConfigInterface;
use JuCloud\EasyOrganization\Direction\NoHttpRequestDirection;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\Exception\Exception;
use JuCloud\EasyOrganization\Exception\InvalidConfigException;
use JuCloud\EasyOrganization\Exception\InvalidParamsException;
use JuCloud\EasyOrganization\Exception\InvalidResponseException;
use JuCloud\EasyOrganization\Exception\ServiceNotFoundException;
use JuCloud\EasyOrganization\Plugin\ParserPlugin;
use JuCloud\EasyOrganization\Provider\Tianyancha;
use JuCloud\EasyOrganization\Provider\Qixin;
use JuCloud\EasyOrganization\Supports\Str;

if (!function_exists('should_do_http_request')) {
    function should_do_http_request(string $direction): bool
    {
        return NoHttpRequestDirection::class !== $direction
            && !in_array(NoHttpRequestDirection::class, class_parents($direction));
    }
}

if (!function_exists('get_tenant')) {
    function get_tenant(array $params = []): string
    {
        return strval($params['_config'] ?? 'default');
    }
}

if (!function_exists('get_tianyancha_config')) {
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    function get_tianyancha_config(array $params = []): array
    {
        $tianyancha = EasyOrganization::get(ConfigInterface::class)->get('tianyancha');
        return $tianyancha[get_tenant($params)] ?? [];
    }
}

if (!function_exists('get_tianyancha_base_uri')) {
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    function get_tianyancha_base_uri(array $params): string
    {
        $config = get_tianyancha_config($params);

        return Tianyancha::URL[$config['mode'] ?? EasyOrganization::MODE_NORMAL];
    }
}

if (!function_exists('get_qixin_config')) {
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    function get_qixin_config(array $params): array
    {
        $qixin = EasyOrganization::get(ConfigInterface::class)->get('qixin');

        return $qixin[get_tenant($params)] ?? [];
    }
}

if (!function_exists('get_qixin_base_uri')) {
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    function get_qixin_base_uri(array $params): string
    {
        $config = get_qixin_config($params);

        return Qixin::URL[$config['mode'] ?? EasyOrganization::MODE_NORMAL];
    }
}

if (!function_exists('get_qixin_sign')) {
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidConfigException
     */
    function get_qixin_sign(array $params, array $contents): string
    {
        $config = get_qixin_config($params);
        return hash('md5', $config['app_key'] . $contents['timestamp'] . $config['secret_key']);
    }
}

if (!function_exists('get_qichacha_config')) {
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    function get_qichacha_config(array $params): array
    {
        $qichacha = EasyOrganization::get(ConfigInterface::class)->get('qichacha');

        return $qichacha[get_tenant($params)] ?? [];
    }
}

if (!function_exists('get_qichacha_sign')) {
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidConfigException
     */
    function get_qichacha_sign(array $params, array $contents): string
    {
        $config = get_qichacha_config($params);
        return Str::upper(hash('md5', $config['app_key'] . $contents['timestamp'] . $config['secret_key']));
    }
}
