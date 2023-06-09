<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qixin\Module\Combine;

use JuCloud\EasyOrganization\Plugin\Qixin\GeneralPlugin;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;

/**
 * @see https://data.qixin.com/api-detail?categoryId=27C4602EBB38429EK08QR7fy&apiId=46.1&from=qxb-c-api
 */
class BaseinfoHKenterpriseModule extends GeneralPlugin
{

    protected function getUri(Rocket $rocket): string
    {
        $query = new Collection([
            'keyword' => $rocket->getPayload()->get('keyword')
        ]);

        return 'hkenterprise/getHKDetailByName?' . $query->query();
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([]);
    }
}
