<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Service;

use JuCloud\EasyOrganization\Contract\ConfigInterface;
use JuCloud\EasyOrganization\Contract\ServiceProviderInterface;
use JuCloud\EasyOrganization\Exception\ContainerException;
use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Supports\Config;

class ConfigServiceProvider implements ServiceProviderInterface
{
    private array $config = [
        'logger' => [
            'enable' => false,
            'file' => null,
            'identify' => 'jucloud.organization',
            'level' => 'debug',
            'type' => 'daily',
            'max_files' => 30,
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 3.0,
            'headers' => [
                'User-Agent' => 'jucloud/easy-organization-v1',
            ],
        ],
        'mode' => EasyOrganization::MODE_NORMAL,
    ];

    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $config = new Config(array_replace_recursive($this->config, $data ?? []));

        EasyOrganization::set(ConfigInterface::class, $config);
    }
}
