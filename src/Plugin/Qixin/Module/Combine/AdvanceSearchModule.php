<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qixin\Module\Combine;

use JuCloud\EasyOrganization\Plugin\Qixin\GeneralPlugin;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;

/**
 * @see https://data.qixin.com/api-detail?categoryId=1309333f837748bbafda78c9d02f40d8&apiId=1.2&from=qxb-c-api
 */
class AdvanceSearchModule extends GeneralPlugin
{

    protected function getUri(Rocket $rocket): string
    {
        $query = new Collection([
            'keyword' => $rocket->getPayload()->get('keyword')
        ]);

        // 通过页数和每页大小计算跳过条目数
        if($rocket->getPayload()->get('page_index')) {
            $query->put('skip', $rocket->getPayload()->get('page_index') * $rocket->getPayload()->get('page_size'));
        }
        
        return 'search/advanceSearchNew?' . $query->query();
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([]);
    }
}
