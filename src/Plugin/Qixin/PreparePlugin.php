<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qixin;

use Closure;
use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Str;

class PreparePlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[qixin][PreparePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload($this->getPayload($rocket->getParams()));

        Logger::info('[qixin][PreparePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getPayload(array $params): array
    {
        return array_filter($params, fn ($v, $k) => !Str::startsWith(strval($k), '_'), ARRAY_FILTER_USE_BOTH);
    }
}
