<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Contract;

interface ShortcutInterface
{
    /**
     * @return PluginInterface[]|string[]
     */
    public function getPlugins(array $params): array;
}
