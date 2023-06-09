<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Event;

use JuCloud\EasyOrganization\Contract\PluginInterface;
use JuCloud\EasyOrganization\Rocket;

class QueryStarted extends Event
{
    /**
     * @var PluginInterface[]
     */
    public array $plugins;

    public array $params;

    public function __construct(array $plugins, array $params, ?Rocket $rocket = null)
    {
        $this->plugins = $plugins;
        $this->params = $params;

        parent::__construct($rocket);
    }
}
