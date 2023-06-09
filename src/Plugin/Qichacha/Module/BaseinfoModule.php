<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qichacha\Module;

use Closure;
use JuCloud\EasyOrganization\Plugin\Qichacha\GeneralPlugin;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;

/**
 * @see https://openapi.qcc.com/dataApi/410
 */
class BaseinfoModule extends GeneralPlugin
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
            'keyword' => $rocket->getPayload()->get('keyword')
        ]);

        return 'ECIV4/GetBasicDetailsByName?' . $query->query();
    }
}
