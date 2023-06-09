<?php

declare(strict_types=1);

namespace JuCloud\EasyOrganization\Direction;

use Psr\Http\Message\ResponseInterface;
use JuCloud\EasyOrganization\Contract\DirectionInterface;
use JuCloud\EasyOrganization\Contract\PackerInterface;
use JuCloud\EasyOrganization\Exception\Exception;
use JuCloud\EasyOrganization\Exception\InvalidResponseException;

class OriginResponseDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        if (!is_null($response)) {
            return $response;
        }

        throw new InvalidResponseException(Exception::INVALID_RESPONSE_CODE);
    }
}
