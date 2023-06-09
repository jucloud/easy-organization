<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qichacha\Module\Combine;

use Closure;
use JuCloud\EasyOrganization\Plugin\Qichacha\GeneralPlugin;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;

/**
 * @see https://openapi.qcc.com/dataApi/886
 */
class FuzzySearchModule extends GeneralPlugin
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
            'searchKey' => $rocket->getPayload()->get('keyword')
        ]);

        if($rocket->getPayload()->get('province_code')) {
            $query->put('provinceCode', $rocket->getPayload()->get('province_code'));
        }

        if($rocket->getPayload()->get('city_code')) {
            $query->put('cityCode', $rocket->getPayload()->get('city_code'));
        }

        if($rocket->getPayload()->get('page_size')) {
            $query->put('pageSize', $rocket->getPayload()->get('page_size'));
        }

        if($rocket->getPayload()->get('page_index')) {
            $query->put('pageIndex', $rocket->getPayload()->get('page_index'));
        }

        return 'FuzzySearch/GetList?' . $query->query();
    }
}
