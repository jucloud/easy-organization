<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Qixin\Module;

use Closure;
use JuCloud\EasyOrganization\Plugin\Qixin\GeneralPlugin;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;

/**
 * @see https://data.qixin.com/api-detail?categoryId=1309333f837748bbafda78c9d02f40d8&apiId=1.31&from=qxb-c-api
 */
class SearchModule extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([]);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        // 企业全名/注册号/统一社会信用代码，输入字数大于等于2个或以上，且不允许仅输入公司和有限公司
        $query = new Collection([
            'keyword' => $rocket->getPayload()->get('keyword')
        ]);

        // 通过页数和每页大小计算跳过条目数
        if($rocket->getPayload()->get('page_index')) {
            $query->put('skip', $rocket->getPayload()->get('page_index') * $rocket->getPayload()->get('page_size'));
        }

        return 'v2/search/advSearch?' . $query->query();
    }
}
