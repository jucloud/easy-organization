<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Exception;

use Throwable;

class InvalidParamsException extends Exception
{
    /**
     * @param mixed $extra
     */
    public function __construct(int $code = self::PARAMS_ERROR, string $message = 'Params Error', $extra = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $extra, $previous);
    }
}
