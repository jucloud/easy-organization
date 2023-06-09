<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Exception;

use Throwable;

class InvalidResponseException extends Exception
{
    public ?Throwable $exception = null;

    /**
     * @var mixed
     */
    public $response;

    /**
     * @param mixed $extra
     */
    public function __construct(
        int $code = self::RESPONSE_ERROR,
        string $message = 'Provider response Error',
        $extra = null,
        ?Throwable $exception = null,
        Throwable $previous = null
    ) {
        $this->response = $extra;
        $this->exception = $exception;

        parent::__construct($message, $code, $extra, $previous);
    }
}
