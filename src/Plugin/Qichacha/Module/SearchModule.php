<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qichacha\Module;

use Closure;
use JuCloud\EasyOrganization\Plugin\Qichacha\GeneralPlugin;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;

/**
 * @see https://openapi.qcc.com/dataApi/886
 */
class SearchModule extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $query = new Collection([
            'key' => $rocket->getPayload()->get('key'),
            'searchName' => $rocket->getPayload()->get('keyword')
        ]);

        return 'NameSearch/GetList?' . $query->query();
    }
}
