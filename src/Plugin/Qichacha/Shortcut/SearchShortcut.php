<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qichacha\Shortcut;

use JuCloud\EasyOrganization\Contract\ShortcutInterface;
use JuCloud\EasyOrganization\Plugin\Qixin\Module\Combine;
use JuCloud\EasyOrganization\Plugin\Qichacha\Module\SearchModule;
use JuCloud\EasyOrganization\Supports\Str;

class SearchShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        if (isset($params['province_code']) || isset($params['city_code']) || isset($params['page_size']) || isset($params['page_index'])) {
            return $this->combinePlugins();
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
            SearchModule::class,
        ];
    }

    protected function combinePlugins(): array
    {
        return [
            Combine\FuzzySearchModule::class,
        ];
    }
}
