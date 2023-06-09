<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qixin\Shortcut;

use JuCloud\EasyOrganization\Contract\ShortcutInterface;
use JuCloud\EasyOrganization\Plugin\Qixin\Module\Combine;
use JuCloud\EasyOrganization\Plugin\Qixin\Module\SearchModule;
use JuCloud\EasyOrganization\Supports\Str;

class SearchShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        if (isset($params['method']) || isset($params['org_type']) || isset($params['area_code']) || isset($params['industry_code']) || isset($params['econ_type']) || isset($params['date_from']) || isset($params['date_to'])) {
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
            Combine\AdvanceSearchModule::class,
        ];
    }
}
