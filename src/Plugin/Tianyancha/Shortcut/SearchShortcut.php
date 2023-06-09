<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Tianyancha\Shortcut;

use JuCloud\EasyOrganization\Contract\ShortcutInterface;
use JuCloud\EasyOrganization\Plugin\Tianyancha\Module\SearchModule;

class SearchShortcut implements ShortcutInterface
{

    public function getPlugins(array $params): array
    {
        return [
            SearchModule::class,
        ];
    }
}
