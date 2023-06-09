<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Plugin\Tianyancha\Module;

use Closure;
use JuCloud\EasyOrganization\Plugin\Tianyancha\GeneralPlugin;
use JuCloud\EasyOrganization\Logger;
use JuCloud\EasyOrganization\Rocket;
use JuCloud\EasyOrganization\Supports\Collection;

/**
 * @see https://open.tianyancha.com/open/816
 */
class SearchModule extends GeneralPlugin
{
    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $query = new Collection([
            'word' => $rocket->getPayload()->get('keyword')
        ]);

        if($rocket->getPayload()->get('page_size')) {
            $query->put('pageSize', $rocket->getPayload()->get('page_size'));
        }

        if($rocket->getPayload()->get('page_index')) {
            $query->put('pageNum', $rocket->getPayload()->get('page_index'));
        }

        return 'search/2.0?' . $query->query();
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }
}
