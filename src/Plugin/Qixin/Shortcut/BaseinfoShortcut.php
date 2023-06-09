<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qixin\Shortcut;

use JuCloud\EasyOrganization\EasyOrganization;
use JuCloud\EasyOrganization\Contract\ShortcutInterface;
use JuCloud\EasyOrganization\Plugin\Qixin\Module\Combine;
use JuCloud\EasyOrganization\Plugin\Qixin\Module\BaseinfoModule;
use JuCloud\EasyOrganization\Supports\Str;

class BaseinfoShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {

        if (isset($params['entity_type']) && $params['entity_type'] === EasyOrganization::ENTITY_TYPE_LAWFIRM) {
            return $this->lawfirmPlugins();
        }

        if (isset($params['entity_type']) && $params['entity_type'] === EasyOrganization::ENTITY_TYPE_ASSOCIATION) {
            return $this->associationPlugins();
        }

        if (isset($params['entity_type']) && $params['entity_type'] === EasyOrganization::ENTITY_TYPE_HKENTERPRICE) {
            return $this->hkenterprisePlugins();
        }

        if (isset($params['entity_type']) && $params['entity_type'] === EasyOrganization::ENTITY_TYPE_INSTITUTION) {
            return $this->institutionPlugins();
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

    protected function lawfirmPlugins(): array
    {
        return [
            Combine\BaseinfoLawfirmModule::class,
        ];
    }

    protected function associationPlugins(): array
    {
        return [
            Combine\BaseinfoAssociationModule::class,
        ];
    }

    protected function hkenterprisePlugins(): array
    {
        return [
            Combine\BaseinfoHKenterpriseModule::class,
        ];
    }

    protected function institutionPlugins(): array
    {
        return [
            Combine\BaseinfoInstitutionModule::class,
        ];
    }
}
