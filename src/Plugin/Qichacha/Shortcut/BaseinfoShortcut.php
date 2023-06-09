<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qichacha\Shortcut;

use JuCloud\EasyOrganization\Contract\ShortcutInterface;
use JuCloud\EasyOrganization\Plugin\Qichacha\Module\BaseinfoModule;
use JuCloud\EasyOrganization\Supports\Str;

class BaseinfoShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            BaseinfoModule::class,
        ];
    }
}
