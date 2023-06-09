<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Tianyancha\Shortcut;

use JuCloud\EasyOrganization\Contract\ShortcutInterface;
use JuCloud\EasyOrganization\Plugin\Tianyancha\Module\BaseinfoModule;
use JuCloud\EasyOrganization\Plugin\Tianyancha\Module\Combine;
use JuCloud\EasyOrganization\Supports\Str;

class BaseinfoShortcut implements ShortcutInterface
{

    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        if (isset($params['entity_type'])) {
            return $this->specialPlugins();
        }

        $typeMethod = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Query action [{$typeMethod}] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            BaseinfoModule::class,
        ];
    }

    protected function baseinfov2Plugins(): array
    {
        return [
            Combine\BaseinfoV2Module::class,
        ];
    }

    protected function specialPlugins(): array
    {
        return [
            Combine\BaseinfoSpecialModule::class,
        ];
    }
}
