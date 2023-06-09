<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Contract;

use Closure;
use JuCloud\EasyOrganization\Rocket;

interface PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket;
}
